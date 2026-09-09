<?php

namespace Plugin\Carrier\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Plugin\Carrier\Models\ShippingCarrier;
use Plugin\TlcommerceCore\Models\ShippingCourier;
use Plugin\TlcommerceCore\Models\ShippingCourierProperties;
use Plugin\TlcommerceCore\Models\ShippingCourierOrders;
use Plugin\TlcommerceCore\Models\Orders;
use Plugin\Carrier\Services\ArmadaService;
use Plugin\Carrier\Services\KarrixService;
use Plugin\Carrier\Services\SmoothService;
use Illuminate\Http\Request;
use Core\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Crypt;
use Plugin\TlcommerceCore\Notifications\CourierOrderUpdateNotification;


class CarrierRepository
{
    protected $armadaService;
    protected $karrixService;
    protected $smoothService;

    public function __construct(ArmadaService $armadaService, KarrixService $karrixService, SmoothService $smoothService)
    {
        $this->armadaService = $armadaService;
        $this->karrixService = $karrixService;
        $this->smoothService = $smoothService;
    }
    /**
     * Will store new courier service
     *
     * @param Object $request
     * @return bool
     */
    public function storeNewCourier($request)
    {
        try {
            $courier = new ShippingCarrier;
            $courier->name = $request['name'];
            $courier->tracking_url = $request['tracking_url'];
            $courier->logo = $request['logo'];
            $courier->save();
            return true;
        } catch (\Exception $e) {
            return false;
        } catch (\Error $e) {
            return false;
        }
    }
    /**
     * Will return all courier list
     *
     * @return collections
     */
    public function couriers($status = null)
    {
        if ($status != null) {
            return ShippingCarrier::where('status', $status)->get()->map(function ($courier) {
                return [
                    'id' => $courier->id,
                    'name' => $courier->name,
                    'tracking_url' => $courier->tracking_url,
                    'logo' => $courier->logo,
                    'status' => $courier->status
                ];
            });
        } else {
            return ShippingCarrier::all()->map(function ($courier) {
                return [
                    'id' => $courier->id,
                    'name' => $courier->name,
                    'logo' => $courier->logo,
                    'tracking_url' => $courier->tracking_url,
                    'status' => $courier->status
                ];
            });
        }
    }
    /**
     * Will update courier status
     *
     * @param Int $id
     * @return bool
     */
    public function updateCourierStatus($id)
    {
        try {
            DB::beginTransaction();
            $courier = ShippingCarrier::findOrFail($id);
            $status = $courier->status == config('settings.general_status.in_active') ? config('settings.general_status.active') : config('settings.general_status.in_active');
            $courier->status = $status;
            $courier->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }
    /**
     * Will delete a courier
     *
     * @param Int $id
     * @return bool
     */
    public function deleteCourier($id)
    {
        try {
            DB::beginTransaction();
            $courier = ShippingCarrier::findOrFail($id);
            $courier->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Will return courier info
     *
     * @param Int $id
     * @return collection
     */
    public function courierDetails($id)
    {
        return ShippingCarrier::findOrFail($id);
    }
    /**
     * Will update courier details
     *
     * @param Object $request
     * @return bool
     */
    public function updateCourier($request)
    {
        try {
            DB::beginTransaction();
            $courier = ShippingCarrier::findOrFail($request['id']);
            $courier->name = $request['name'];
            $courier->tracking_url = $request['tracking_url'];
            $courier->logo = $request['edit_logo'];
            $courier->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


    /**
     * Will return courier properties
     *
     * @param Int $id
     * @return collection
     */
    public function courierProperties($id)
    {
        Log::info("courierProperties method called:", ['id' => $id]);

        $courier = ShippingCourier::where('id', $id)->with('properties')->first();

        if (!$courier) {
            Log::warning("Courier or properties not found for ID: " . $id);
            return [
                'shipping_courier_id' => $id,
                'provider' => 'unknown',
            ];
        }

        $provider = $this->courierProvider($courier);

        if (!$courier->properties) {
            return [
                'shipping_courier_id' => $id,
                'provider' => $provider,
            ];
        }

        $properties = $courier->properties;

        return [
            'shipping_courier_id' => $id,
            'provider' => $provider,
            // Never send Karrix/Smooth secrets back into the HTML response.
            'api_key' => in_array($provider, ['karrix', 'smooth'], true) ? '' : $properties->api_key,
            'api_secret' => '',
            'branch_id' => $properties->branch_id,
            'is_configured' => $provider === 'smooth' ? $this->smoothService->isConfigured($courier) : null,
        ];
    }

     /**
     * Will save courier properties
     *
     * @param Object $request
     * @return bool
     */
    public function submitCourierProperties(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info("submitCourierProperties method called!");
        
            $courier = ShippingCourier::findOrFail($request->shipping_courier_id);
            $provider = $this->courierProvider($courier);

            // Smooth uses only an API key + pre-shared Store Slug in the supplied API schema.
            // Keep both nullable while credentials are still pending. Missing values do not
            // affect Armada/Karrix and make no outbound Smooth request.
            if ($provider === 'smooth') {
                $request->validate([
                    'shipping_courier_id' => 'required|integer',
                    'api_key' => 'nullable|string|max:500',
                    'branch_id' => 'nullable|string|max:255',
                ]);

                $propertiesData = [];
                $apiKey = trim((string) $request->api_key);
                $storeSlug = trim((string) $request->branch_id);

                if ($apiKey !== '') {
                    $propertiesData['api_key'] = Crypt::encryptString($apiKey);
                }

                // Store Slug is not secret and can be edited/cleared normally.
                if ($request->has('branch_id')) {
                    $propertiesData['branch_id'] = $storeSlug;
                }

                if (!empty($propertiesData)) {
                    ShippingCourierProperties::updateOrCreate(
                        ['shipping_courier_id' => $request->shipping_courier_id],
                        $propertiesData
                    );
                }

                DB::commit();
                return true;
            }

            $request->validate([
                'shipping_courier_id' => 'required|integer',
                'api_key' => 'nullable|string|max:500',
                'api_secret' => 'nullable|string|max:500',
                'branch_id' => 'required|string|max:255',
            ]);

            Log::info('Courier properties submission', [
                'shipping_courier_id' => $request->shipping_courier_id,
                'provider' => $provider,
                'has_api_key' => trim((string) $request->api_key) !== '',
                'has_secret' => trim((string) $request->api_secret) !== '',
            ]);

            $propertiesData = ['branch_id' => trim((string) $request->branch_id)];

            $apiKey = trim((string) $request->api_key);
            if ($apiKey !== '') {
                $propertiesData['api_key'] = $provider === 'karrix'
                    ? Crypt::encryptString($apiKey)
                    : $apiKey;
            } elseif (!$courier->properties || trim((string) $courier->properties->api_key) === '') {
                DB::rollBack();
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'api_key' => [translate($provider === 'karrix' ? 'Karrix API token is required.' : 'API key is required.')],
                ]);
            }

            $apiSecret = trim((string) $request->api_secret);
            if ($apiSecret !== '') {
                $propertiesData['api_secret'] = $apiSecret; // auto-encrypted by model mutator
            } elseif (!$courier->properties || trim((string) ($courier->properties->api_secret ?? '')) === '') {
                DB::rollBack();
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'api_secret' => [translate($provider === 'karrix'
                        ? 'Karrix webhook signing secret is required.'
                        : 'API secret is required.')],
                ]);
            }

            ShippingCourierProperties::updateOrCreate(
                ['shipping_courier_id' => $request->shipping_courier_id],
                $propertiesData
            );
            DB::commit();
            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("properties submission failure: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Will save courier properties
     *
     * @param Object $request
     * @return bool
     */
    public function submitCourierRequest($request)
    {
        try {
            // DB::beginTransaction();

            Log::info("submitCourierRequest 2 method called!");
        
            Log::info('Courier request received', [
                'order_id' => $request->order_id,
                'shipping_courier_id' => $request->available_couriers,
            ]);

            $order_id = $request->order_id;
            $shipping_courier_id = $request->available_couriers;

            $courier = ShippingCourier::where('id', $shipping_courier_id)->with('properties')->first();

            if (!$courier || !$courier->properties) {
                Log::warning("Courier or properties not found for ID: " . $shipping_courier_id);
                return [
                    'success' => false,
                    'message' => translate('Courier or courier properties not found.'),
                ];
            }

            $order = Orders::where('id', $order_id)->with('shipping_details.country', 'shipping_details.state', 'shipping_details.city')->first();

            if (!$order) {
                Log::warning("Order not found for ID: " . $order_id);
                return [
                    'success' => false,
                    'message' => translate('Order not found.'),
                ];
            }

            $address = $order->shipping_details;

            if (!$address) {
                Log::warning("Shipping address not found for order", [
                    'order_id' => $order_id,
                    'shipping_address_id' => $order->shipping_address,
                ]);
                return [
                    'success' => false,
                    'message' => translate('Shipping address not found for this order.'),
                ];
            }

            $provider = $this->courierProvider($courier);

            // Prevent repeated clicks from creating the same external delivery twice.
            $existingCourierOrder = ShippingCourierOrders::where('order_id', $order->id)
                ->where('shipping_courier_id', $courier->id)
                ->first();
            if ($existingCourierOrder) {
                return [
                    'success' => true,
                    'message' => translate('A delivery request already exists for this order and courier.'),
                ];
            }

            if ($provider === 'karrix') {
                $carrierResponse = $this->karrixService->createDelivery($courier, $order, $address);
            } elseif ($provider === 'smooth') {
                $carrierResponse = $this->smoothService->createDelivery($courier, $order, $address);
            } else {
                // Armada remains the default for existing/legacy carrier records.
                $carrierResponse = $this->armadaService->createDelivery($courier, $order, $address);
            }
           
            if ($carrierResponse['success']) {
                $data = $carrierResponse['data'];

                try {
                    if ($provider === 'karrix') {
                        $normalized = $this->normalizeKarrixPayload(is_array($data) ? $data : []);
                    } elseif ($provider === 'smooth') {
                        // SmoothService already returns the common ShippingCourierOrders shape.
                        $normalized = is_array($data) ? $data : [];
                    } else {
                        $normalized = $this->normalizeArmadaWebhookPayload(is_array($data) ? $data : []);
                    }

                    ShippingCourierOrders::create([
                        'order_id'             => $order->id,
                        'shipping_courier_id'  => $courier->id,
                        'code'                 => $normalized['code'] ?? $normalized['id'] ?? null,
                        'status'               => $normalized['status'],
                        'amount'               => $normalized['amount'],
                        'delivery_fee'         => $normalized['delivery_fee'],
                        'currency'             => $normalized['currency'],
                        'driver_name'          => $normalized['driver_name'],
                        'driver_phone'         => $normalized['driver_phone'],
                        'driver_latitude'      => $normalized['driver_latitude'],
                        'driver_longitude'     => $normalized['driver_longitude'],
                        'estimated_distance'   => $normalized['estimated_distance'],
                        'estimated_duration'   => $normalized['estimated_duration'],
                        'tracking_url'         => $normalized['tracking_url'],
                        'pickup_qr_url'        => $normalized['pickup_qr_url'],
                    ]);

                    return [
                        'success' => true,
                        'message' => $carrierResponse['message'] ?? translate('Delivery request submitted successfully.'),
                    ];

                }
                catch (\Exception $e) {
                    Log::error("ShippingCourierOrder Submit failure: " . $e->getMessage());
                    return [
                        'success' => false,
                        'message' => translate('Delivery was created but saving courier order failed: ') . $e->getMessage(),
                    ];
                }

            }
            else {
                Log::warning("Courier Error", ['provider' => $provider, 'error' => $carrierResponse['message'] ?? null]);
                return [
                    'success' => false,
                    'message' => $carrierResponse['message'] ?? translate('Could not submit courier request.'),
                ];
            }
        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error("Courier Requst failure: " . $e->getMessage());
            return [
                'success' => false,
                'message' => translate('Courier request failed: ') . $e->getMessage(),
            ];
        }
    }

    /**
     * Process a signed Karrix webhook independently from the Armada webhook.
     */
    public function updateKarrixCourierOrder(Request $request): array
    {
        try {
            $rawBody = $request->getContent();
            $signature = trim((string) $request->header('X-Karrix-Signature', ''));
            $payload = $request->json()->all();

            $tracking = $payload['tracking_number'] ?? data_get($payload, 'order.tracking_number');
            $shippingCourierOrder = $tracking
                ? ShippingCourierOrders::where('code', $tracking)->first()
                : null;

            if (!$shippingCourierOrder) {
                Log::warning('Karrix webhook: courier order not found', ['tracking_number' => $tracking]);
                return ['success' => true, 'status' => 200];
            }

            $courier = ShippingCourier::with('properties')->find($shippingCourierOrder->shipping_courier_id);
            if (!$courier || $this->courierProvider($courier) !== 'karrix') {
                return ['success' => false, 'status' => 404];
            }

            $secret = trim((string) ($courier->properties->api_secret ?? ''));
            if ($secret !== '') {
                $expected = hash_hmac('sha256', $rawBody, $secret);
                if ($signature === '' || !hash_equals($expected, $signature)) {
                    Log::warning('Karrix webhook signature verification failed', [
                        'shipping_courier_id' => $courier->id,
                    ]);
                    return ['success' => false, 'status' => 401];
                }
            }

            $normalized = $this->normalizeKarrixPayload($payload);
            $updateData = array_filter([
                'status' => $normalized['status'],
                'amount' => $normalized['amount'],
                'delivery_fee' => $normalized['delivery_fee'],
                'currency' => $normalized['currency'],
                'driver_name' => $normalized['driver_name'],
                'driver_phone' => $normalized['driver_phone'],
                'tracking_url' => $normalized['tracking_url'],
            ], static function ($value) {
                return $value !== null;
            });

            if (!empty($updateData)) {
                $shippingCourierOrder->update($updateData);
            }

            $this->notifyAdminsOfCourierUpdate($shippingCourierOrder);

            return ['success' => true, 'status' => 200];
        } catch (\Throwable $e) {
            Log::error('Karrix webhook processing failure', ['message' => $e->getMessage()]);
            return ['success' => false, 'status' => 500];
        }
    }

    private function normalizeKarrixPayload(array $payload): array
    {
        $data = is_array($payload['data'] ?? null) ? $payload['data'] : $payload;
        $order = is_array($data['order'] ?? null) ? $data['order'] : $data;
        $dc = is_array($order['dc'] ?? null) ? $order['dc'] : [];
        $driver = is_array($order['driver'] ?? null) ? $order['driver'] : [];
        $tracking = $data['tracking_number'] ?? $order['tracking_number'] ?? null;

        return [
            'id' => $order['id'] ?? null,
            'code' => $tracking ?? $order['reference'] ?? null,
            'reference' => $order['client_reference'] ?? $order['reference'] ?? null,
            'status' => $order['state'] ?? $order['status'] ?? null,
            'amount' => $order['order_total'] ?? $order['price'] ?? null,
            'delivery_fee' => $order['price'] ?? $order['delivery_fee'] ?? null,
            'currency' => $order['currency'] ?? 'KWD',
            'estimated_distance' => $order['distance_km'] ?? $order['actual_distance_km'] ?? null,
            'estimated_duration' => $order['estimated_duration'] ?? null,
            'tracking_url' => $data['tracking_url']
                ?? $order['tracking_url']
                ?? ($tracking ? 'https://karrix.sh/track/' . rawurlencode($tracking) : null),
            'pickup_qr_url' => null,
            'driver_name' => $driver['name'] ?? $dc['driver_name'] ?? null,
            'driver_phone' => $driver['phone'] ?? $dc['driver_phone'] ?? null,
            'driver_latitude' => $driver['latitude'] ?? null,
            'driver_longitude' => $driver['longitude'] ?? null,
        ];
    }


    /**
     * Process Smooth's documented OrderStatusUpdate callback.
     * No Armada or Karrix webhook code is shared or modified here.
     */
    public function updateSmoothCourierOrder(Request $request, string $ownerSlug, string $orderId): array
    {
        try {
            $payload = $request->json()->all();
            if (empty($payload)) {
                $payload = $request->all();
            }

            $normalized = $this->smoothService->normalizeStatusCallback(
                is_array($payload) ? $payload : [],
                $orderId
            );

            if (!$this->smoothService->isAllowedStatus((string) ($normalized['status'] ?? ''))) {
                Log::warning('Smooth status callback rejected: unknown status.', [
                    'owner_slug' => $ownerSlug,
                    'order_id' => $orderId,
                    'status' => $normalized['status'] ?? null,
                ]);

                return ['success' => false, 'status' => 422];
            }

            $shippingCourierOrder = null;
            $trackingId = trim((string) ($normalized['code'] ?? ''));

            // Smooth sends tracking_id in the documented callback when available.
            if ($trackingId !== '') {
                $candidate = ShippingCourierOrders::where('code', $trackingId)->first();
                if ($candidate) {
                    $candidateCourier = ShippingCourier::with('properties')->find($candidate->shipping_courier_id);
                    if ($candidateCourier && $this->courierProvider($candidateCourier) === 'smooth') {
                        $shippingCourierOrder = $candidate;
                    }
                }
            }

            // The callback path also carries order_id. Match it to the store reference_id
            // (our existing order_code) and select only the Smooth courier row.
            if (!$shippingCourierOrder && $orderId !== '') {
                $order = Orders::where('order_code', $orderId)->first();
                if ($order) {
                    $candidates = ShippingCourierOrders::where('order_id', $order->id)->get();
                    foreach ($candidates as $candidate) {
                        $candidateCourier = ShippingCourier::with('properties')->find($candidate->shipping_courier_id);
                        if ($candidateCourier && $this->courierProvider($candidateCourier) === 'smooth') {
                            $shippingCourierOrder = $candidate;
                            break;
                        }
                    }
                }
            }

            if (!$shippingCourierOrder) {
                Log::warning('Smooth status callback: courier order not found.', [
                    'owner_slug' => $ownerSlug,
                    'order_id' => $orderId,
                    'tracking_id' => $trackingId ?: null,
                ]);

                // Acknowledge unknown/stale callbacks so Smooth does not keep retrying.
                return ['success' => true, 'status' => 200];
            }

            $courier = ShippingCourier::with('properties')->find($shippingCourierOrder->shipping_courier_id);
            if (!$courier || $this->courierProvider($courier) !== 'smooth') {
                return ['success' => true, 'status' => 200];
            }

            // Smooth's callback URL includes owner_slug. Ensure it matches this courier's
            // configured pre-shared Store Slug before any local row is changed.
            $configuredStoreSlug = trim((string) ($courier->properties->branch_id ?? ''));
            if ($configuredStoreSlug !== '' && !hash_equals($configuredStoreSlug, trim($ownerSlug))) {
                Log::warning('Smooth status callback: Store Slug mismatch.', [
                    'shipping_courier_id' => $courier->id,
                ]);
                return ['success' => false, 'status' => 403];
            }

            $updateData = [
                'status' => $normalized['status'],
            ];

            if (!empty($normalized['tracking_url'])) {
                $updateData['tracking_url'] = $normalized['tracking_url'];
            }

            // If Smooth did not return a tracking ID during create but supplies it later,
            // store it without changing an existing identifier.
            if ($trackingId !== '' && trim((string) ($shippingCourierOrder->code ?? '')) === '') {
                $updateData['code'] = $trackingId;
            }

            $shippingCourierOrder->update($updateData);
            $this->notifyAdminsOfCourierUpdate($shippingCourierOrder);

            return ['success' => true, 'status' => 200];
        } catch (\Throwable $e) {
            Log::error('Smooth status callback failure', ['message' => $e->getMessage()]);
            return ['success' => false, 'status' => 500];
        }
    }

    private function courierProvider($courier): string
    {
        $name = mb_strtolower(trim((string) ($courier->name ?? '')));
        $trackingUrl = mb_strtolower(trim((string) ($courier->tracking_url ?? '')));

        if (str_contains($name, 'smooth logistics')
            || str_contains($name, 'smooth')
            || str_contains($trackingUrl, 'smoothlogistics')) {
            return 'smooth';
        }

        return str_contains($name, 'karrix') || str_contains($trackingUrl, 'karrix.sh')
            ? 'karrix'
            : 'armada';
    }

    private function notifyAdminsOfCourierUpdate($shippingCourierOrder): void
    {
        $order = Orders::find($shippingCourierOrder->order_id);
        if (!$order) {
            return;
        }

        $data = [
            'message' => 'Update received from courier, Order code ' . $order->order_code,
            'link' => '/orders/order-details/' . $shippingCourierOrder->order_id,
        ];

        $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))
            ->where('status', config('settings.general_status.active'))
            ->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new CourierOrderUpdateNotification($data));
        }
    }

    /**
     * Will update courier order from Armada webhook (v0/v1/v2 compatible)
     *
     * @param Object $request
     * @return bool
     */
    public function updateShippingCourierOrders($request)
    {
        try {
            $raw = $request->all();
            $payload = $this->normalizeArmadaWebhookPayload($raw);

            Log::info("updateShippingCourierOrders method called:", [
                'payload' => $raw,
                'normalized' => $payload,
            ]);

            // Armada dashboard "Send delivery test" uses a canned body — acknowledge without DB work.
            if (($payload['code'] ?? null) === 'TEST123') {
                Log::info("Armada Test Webhook (TEST123) acknowledged successfully.");
                return true;
            }

            $shippingCourierOrder = $this->findShippingCourierOrder($payload);

            if (!$shippingCourierOrder) {
                Log::warning("Courier Order Update: No record found", [
                    'code' => $payload['code'] ?? null,
                    'id' => $payload['id'] ?? null,
                    'reference' => $payload['reference'] ?? null,
                ]);
                // Return true so Armada gets 2xx and does not retry on unknown/test codes.
                return true;
            }

            $updateData = array_filter([
                'status'             => $payload['status'],
                'amount'             => $payload['amount'],
                'delivery_fee'       => $payload['delivery_fee'],
                'estimated_distance' => $payload['estimated_distance'],
                'estimated_duration' => $payload['estimated_duration'],
                'tracking_url'       => $payload['tracking_url'],
                'pickup_qr_url'      => $payload['pickup_qr_url'],
                'driver_name'        => $payload['driver_name'],
                'driver_phone'       => $payload['driver_phone'],
                'driver_latitude'    => $payload['driver_latitude'],
                'driver_longitude'   => $payload['driver_longitude'],
            ], static function ($value) {
                return $value !== null;
            });

            if (!empty($updateData)) {
                $shippingCourierOrder->update($updateData);
            }

            $order = Orders::where('id', $shippingCourierOrder->order_id)->first();

            if (!$order) {
                Log::warning("Courier Order Update: No order found for id " . $shippingCourierOrder->order_id);
                return true;
            }

            $link = '/orders/order-details/' . $shippingCourierOrder->order_id;
            $message = "Update received from courier, Order code " . $order->order_code;
            $data = [
                'message' => $message,
                'link' => $link,
            ];

            $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))
                ->where('status', config('settings.general_status.active'))
                ->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new CourierOrderUpdateNotification($data));
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Courier Order Update failure: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Normalize Armada v0 / v1 / v2 create + webhook bodies to one internal shape.
     *
     * @param array $payload
     * @return array
     */
    private function normalizeArmadaWebhookPayload(array $payload): array
    {
        $logistics = is_array($payload['logistics'] ?? null) ? $payload['logistics'] : [];
        $driver = is_array($payload['driver'] ?? null) ? $payload['driver'] : [];
        $driverLocation = is_array($payload['driverLocation'] ?? null) ? $payload['driverLocation'] : [];

        return [
            'id' => $payload['id'] ?? null,
            'code' => $payload['code'] ?? null,
            'reference' => $payload['reference'] ?? null,
            'status' => $payload['status'] ?? $payload['orderStatus'] ?? null,
            'amount' => $payload['amount'] ?? null,
            'delivery_fee' => $payload['delivery_fee'] ?? $payload['deliveryFee'] ?? null,
            'currency' => $payload['currency'] ?? null,
            'estimated_distance' => $logistics['estimated_distance']
                ?? $payload['distance']
                ?? $payload['estimatedDistance']
                ?? null,
            'estimated_duration' => $logistics['estimated_duration']
                ?? $payload['duration']
                ?? $payload['estimatedDuration']
                ?? null,
            'tracking_url' => $logistics['tracking_url']
                ?? $payload['trackingLink']
                ?? $payload['tracking_url']
                ?? null,
            'pickup_qr_url' => $logistics['pickup_qr_url']
                ?? $payload['qrCodeLink']
                ?? $payload['pickup_qr_url']
                ?? null,
            'driver_name' => $driver['name'] ?? $payload['driverName'] ?? null,
            'driver_phone' => $driver['phone']
                ?? $driver['phoneNumber']
                ?? $payload['driverPhone']
                ?? null,
            'driver_latitude' => $driver['latitude']
                ?? $driverLocation['latitude']
                ?? null,
            'driver_longitude' => $driver['longitude']
                ?? $driverLocation['longitude']
                ?? null,
        ];
    }

    /**
     * Resolve ShippingCourierOrders from webhook identity fields.
     *
     * @param array $payload Normalized payload
     * @return ShippingCourierOrders|null
     */
    private function findShippingCourierOrder(array $payload)
    {
        if (!empty($payload['code'])) {
            $row = ShippingCourierOrders::where('code', $payload['code'])->first();
            if ($row) {
                return $row;
            }
        }

        // Create may have stored v2 delivery id as code when short code was absent.
        if (!empty($payload['id'])) {
            $row = ShippingCourierOrders::where('code', $payload['id'])->first();
            if ($row) {
                return $row;
            }
        }

        if (!empty($payload['reference'])) {
            $order = Orders::where('order_code', $payload['reference'])->first();
            if ($order) {
                return ShippingCourierOrders::where('order_id', $order->id)->first();
            }
        }

        return null;
    }

    /**
     * Will return courier properties
     *
     * @param Int $id
     * @return collection
     */
    public function getCarriersOrderUpdates($id)
    {
        Log::info("getCarriersOrderUpdates method called:", ['Order_id' => $id]);

        $shippingCourierOrder =  ShippingCourierOrders::where('order_id', $id)->first();

        if (!$shippingCourierOrder) {
            Log::warning("Courier order updates not found for Order ID: " . $id);
            // return [
            //     'shipping_courier_id' => $id
            // ];
            return null; 
        }

        return [
            'shipping_courier_id' => $shippingCourierOrder->id,
            'status' => $shippingCourierOrder->status,
            'driver_name' => $shippingCourierOrder->driver_name,
            'driver_phone' => $shippingCourierOrder->driver_phone,
        ];
    }
}
