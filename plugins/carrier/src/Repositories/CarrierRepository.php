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
use Illuminate\Http\Request;
use Core\Models\User;
use Illuminate\Support\Facades\Notification;
use Plugin\TlcommerceCore\Notifications\CourierOrderUpdateNotification;


class CarrierRepository
{
    protected $armadaService;

    public function __construct(ArmadaService $armadaService)
    {
        $this->armadaService = $armadaService;
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

        if (!$courier || !$courier->properties) {
            Log::warning("Courier or properties not found for ID: " . $id);
            return [
                'shipping_courier_id' => $id
            ];
            // return null; 
        }

        $properties = $courier->properties;

        return [
            'shipping_courier_id' => $id,
            'api_key' => $properties->api_key,
            'api_secret' => $properties->api_secret,
            'branch_id' => $properties->branch_id,
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
        
            Log::info("Courier Properties data:", ['request' => $request->all()]);

            $propertiesData = [
                'api_key'   => trim((string) $request->api_key),
                'branch_id' => trim((string) $request->branch_id),
            ];

            $apiSecret = trim((string) $request->api_secret);
            if ($apiSecret !== '') {
                $propertiesData['api_secret'] = $apiSecret; // auto-encrypted by model mutator
            }

            ShippingCourierProperties::updateOrCreate(
                ['shipping_courier_id' => $request->shipping_courier_id],
                $propertiesData
            );
            DB::commit();
            return true;
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
        
            Log::info("Courier Properties data:", ['request' => $request->all()]);

            $order_id = $request->order_id;
            $shipping_courier_id = $request->available_couriers;

            $courier = ShippingCourier::where('id', $shipping_courier_id)->with('properties')->first();

            Log::info("Courier data:", ['courier' => json_encode($courier)]);

            if (!$courier || !$courier->properties) {
                Log::warning("Courier or properties not found for ID: " . $shipping_courier_id);
                return [
                    'shipping_courier_id' => $shipping_courier_id
                ];
            }

            $order = Orders::where('id', $order_id)->with('shipping_details.country', 'shipping_details.state', 'shipping_details.city')->first();

            if (!$order) {
                Log::warning("Order not found for ID: " . $order_id);
                return [
                    'order_id' => $order_id
                ];
            }

            Log::info("order data:", ['order' => json_encode($order)]);

            $address = $order->shipping_details;

            if (!$address) {
                Log::warning("Shipping address not found for order", [
                    'order_id' => $order_id,
                    'shipping_address_id' => $order->shipping_address,
                ]);
                return false;
            }

            Log::info("address data with relations:", ['address' => $address->toArray()]);

            $armadaResponse = $this->armadaService->createDelivery($courier, $order, $address);
           
            if ($armadaResponse['success']) {
                $data = $armadaResponse['data'];

                try {
                    $normalized = $this->normalizeArmadaWebhookPayload(is_array($data) ? $data : []);

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

                    return true;

                }
                catch (\Exception $e) {
                    Log::error("ShippingCourierOrder Submit failure: " . $e->getMessage());
                    return false;
                }

            }
            else {
                Log::warning("Courier Error", ['error' => $armadaResponse['message']]);
                return false;
            }
            // DB::commit();
            return true;
        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error("Courier Requst failure: " . $e->getMessage());
            return false;
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

