<?php

namespace Plugin\Carrier\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Plugin\TlcommerceCore\Models\OrderHasProducts;
use Plugin\TlcommerceCore\Models\Product;

class SmoothService
{
    /** @var ArmadaService Reuse the existing geocoder only; Armada's delivery flow is not changed. */
    protected $geocoder;

    public function __construct(ArmadaService $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    /**
     * Smooth is considered configured only when its documented credentials are present.
     * Existing DB fields are reused:
     *   api_key   => Smooth API key
     *   branch_id => Smooth pre-shared store slug
     */
    public function isConfigured($courier): bool
    {
        if (!$courier || !$courier->properties) {
            return false;
        }

        return $this->apiKey($courier->properties) !== ''
            && trim((string) ($courier->properties->branch_id ?? '')) !== ''
            && trim((string) config('smooth.base_url')) !== '';
    }

    /**
     * Create an order in Smooth.
     * SmoothAPI: POST /api/v1/orders/{store_slug}/
     */
    public function createDelivery($courier, $order, $address): array
    {
        if (!$this->isConfigured($courier)) {
            return [
                'success' => false,
                'configured' => false,
                'message' => translate('Smooth Logistics is not configured. Add the API key and Store Slug first.'),
            ];
        }

        try {
            $storeSlug = mb_strtolower(trim((string) $courier->properties->branch_id));
            $payload = $this->buildOrderPayload($order, $address, $storeSlug);
            $url = $this->orderCollectionUrl($storeSlug);

            Log::info('Smooth: creating order', [
                'order' => $order->order_code ?? null,
                'store_slug' => $storeSlug,
                'line_count' => count($payload['order_lines'] ?? []),
            ]);

            $response = $this->client($courier)
                ->timeout(max(5, (int) config('smooth.timeout', 20)))
                ->post($url, $payload);

            if ($response->failed()) {
                // Smooth documents error code 1023 for duplicate orders. If a previous
                // request reached Smooth but the local save failed, recover that order.
                $errorCode = data_get($response->json(), 'error.errorCode');
                if ((int) $errorCode === 1023) {
                    $existing = $this->retrieveOrder($courier, (string) $order->order_code);
                    if ($existing !== null) {
                        return $this->successResponse($existing, true);
                    }
                }

                Log::error('Smooth API refused order creation', [
                    'order' => $order->order_code ?? null,
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => $this->responseErrorMessage($response),
                ];
            }

            $data = $response->json();

            return $this->successResponse(is_array($data) ? $data : []);
        } catch (ConnectionException $e) {
            Log::error('Smooth connection failure', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => translate('Connection to Smooth Logistics timed out. Please try again.'),
            ];
        } catch (\Throwable $e) {
            Log::error('Smooth create order failure', [
                'order' => $order->order_code ?? null,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => translate('Smooth Logistics order could not be created: ') . $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve an existing Smooth order by your store order reference.
     * SmoothAPI: GET /api/v1/orders/{store_slug}/{reference_id}/
     */
    public function retrieveOrder($courier, string $referenceId): ?array
    {
        if (!$this->isConfigured($courier)) {
            return null;
        }

        $storeSlug = mb_strtolower(trim((string) $courier->properties->branch_id));
        $response = $this->client($courier)
            ->timeout(max(5, (int) config('smooth.timeout', 20)))
            ->get($this->orderItemUrl($storeSlug, $referenceId));

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        return is_array($data) ? $data : null;
    }

    /**
     * Normalize Smooth create/retrieve data into the existing ShippingCourierOrders shape.
     */
    public function normalizeOrderPayload(array $payload): array
    {
        $shipment = is_array($payload['shipment'] ?? null) ? $payload['shipment'] : [];
        $trackingId = $payload['tracking_id'] ?? null;
        $reference = $payload['reference_id'] ?? null;
        $shipmentReference = $shipment['reference_id'] ?? null;

        return [
            'id' => $shipmentReference ?? $reference,
            'code' => $trackingId ?? $shipmentReference ?? $reference,
            'reference' => $reference,
            'status' => $payload['status'] ?? $shipment['status'] ?? 'NEW',
            'amount' => $payload['amount'] ?? null,
            'delivery_fee' => $payload['delivery_fee'] ?? null,
            'currency' => (string) config('smooth.currency', 'KWD'),
            'driver_name' => null,
            'driver_phone' => null,
            'driver_latitude' => null,
            'driver_longitude' => null,
            'estimated_distance' => null,
            'estimated_duration' => null,
            'tracking_url' => $payload['tracking_url'] ?? null,
            'pickup_qr_url' => null,
        ];
    }

    /**
     * Smooth's documented OrderStatus callback fields are:
     * status, route_id, tracking_id and tracking_url.
     */
    public function normalizeStatusCallback(array $payload, string $orderId): array
    {
        $status = strtoupper(trim((string) ($payload['status'] ?? '')));

        return [
            'code' => $payload['tracking_id'] ?? null,
            'reference' => $orderId,
            'status' => $status,
            'tracking_url' => $payload['tracking_url'] ?? null,
            'route_id' => $payload['route_id'] ?? null,
        ];
    }

    public function isAllowedStatus(string $status): bool
    {
        return in_array(strtoupper(trim($status)), $this->allowedStatuses(), true);
    }

    public function allowedStatuses(): array
    {
        return [
            'NEW',
            'RELEASED',
            'PICKING',
            'PICKED',
            'STAGED',
            'COMPLETED',
            'FULFILLED',
            'PARTIALLY_FULFILLED',
            'CANCELLED',
            'SELF_PICKED_UP',
            'PACKING',
            'PACKED',
            'LM_NEW',
            'LM_CONFIRMED',
            'LM_COLLECTED',
            'LM_DRIVER_ON_THE_WAY_TO_PICKUP',
            'LM_DRIVER_ON_THE_WAY',
            'LM_DRIVER_CLOSE_BY',
            'LM_DRIVER_ARRIVED_TO_DROP_OFF',
            'LM_PARTIALLY_DELIVERED',
            'LM_DELIVERED',
            'LM_ARRIVED_WAREHOUSE',
            'LM_REJECTED',
            'LM_RESCHEDULED',
        ];
    }

    protected function successResponse(array $payload, bool $recovered = false): array
    {
        return [
            'success' => true,
            'data' => $this->normalizeOrderPayload($payload),
            'message' => $recovered
                ? translate('Existing Smooth Logistics order recovered successfully.')
                : translate('Delivery request submitted to Smooth Logistics successfully.'),
        ];
    }

    /**
     * Build Smooth's documented OrderModel request.
     */
    protected function buildOrderPayload($order, $address, string $storeSlug): array
    {
        $orderLines = $this->buildOrderLines($order);
        if (empty($orderLines)) {
            throw new \RuntimeException('Smooth requires at least one order line with a valid SKU.');
        }

        $isPaid = $order->payment_status == config('tlecommercecore.order_payment_status.paid');

        $payload = [
            'store' => $storeSlug,
            'reference_id' => (string) $order->order_code,
            'confirmed' => true,
            'order_lines' => $orderLines,
            'shipping_address' => $this->buildShippingAddress($order, $address),
            'order_type' => $this->orderType(),
            'delivery_mode' => $this->deliveryMode(),
            'amount' => $this->decimal($order->total_payable_amount ?? 0),
            'payment_status' => $isPaid ? 'PAID' : 'UNPAID',
            'requires_proof_of_delivery' => (bool) config('smooth.requires_proof_of_delivery', false),
            'partner_metadata' => [
                'local_order_id' => (string) ($order->id ?? ''),
                'source' => 'laravel_store',
            ],
        ];

        return $payload;
    }

    protected function buildOrderLines($order): array
    {
        $items = OrderHasProducts::where('order_id', $order->id)->get();
        $lines = [];
        $position = 0;

        foreach ($items as $item) {
            $position++;
            $productId = $this->modelValue($item, ['product_id']);
            $product = null;

            if ($productId !== null && $productId !== '') {
                $product = Product::with(['single_price', 'variations'])->find($productId);
            }

            $quantity = (int) ($this->modelValue($item, ['quantity', 'qty', 'product_quantity']) ?? 1);
            if ($quantity < 1) {
                $quantity = 1;
            }

            $sku = trim((string) ($this->modelValue($item, [
                'sku',
                'product_sku',
                'variant_sku',
                'variation_sku',
            ]) ?? ''));

            $variation = null;
            if ($product && isset($product->variations)) {
                $variationId = $this->modelValue($item, [
                    'product_variation_id',
                    'variation_id',
                    'variant_id',
                ]);

                if ($variationId !== null && $variationId !== '') {
                    $variation = $product->variations->first(function ($row) use ($variationId) {
                        return (string) ($row->id ?? '') === (string) $variationId;
                    });
                }

                if (!$variation) {
                    $variantCode = trim((string) ($this->modelValue($item, [
                        'variant',
                        'product_variant',
                        'variation',
                        'variant_code',
                    ]) ?? ''));

                    if ($variantCode !== '') {
                        $variation = $product->variations->first(function ($row) use ($variantCode) {
                            return trim((string) ($row->variant ?? '')) === $variantCode
                                || trim((string) ($row->sku ?? '')) === $variantCode;
                        });
                    }
                }
            }

            if ($sku === '' && $variation) {
                $sku = trim((string) ($variation->sku ?? ''));
            }

            if ($sku === '' && $product && isset($product->single_price)) {
                $sku = trim((string) ($product->single_price->sku ?? ''));
            }

            if ($sku === '' && $product && isset($product->variations) && $product->variations->count() === 1) {
                $sku = trim((string) ($product->variations->first()->sku ?? ''));
            }

            if ($sku === '') {
                throw new \RuntimeException(
                    'Smooth requires a SKU for every order item. Missing SKU on local order item #' . ($item->id ?? $position) . '.'
                );
            }

            $uom = trim((string) ($this->modelValue($item, [
                'unit_of_measure',
                'uom',
                'unit',
            ]) ?? ''));

            if ($uom === '') {
                $uom = trim((string) config('smooth.default_uom', 'EA'));
            }

            $lineId = (int) ($item->id ?? $position);
            if ($lineId < 1) {
                $lineId = $position;
            }

            $line = [
                'line_id' => $lineId,
                'quantity' => $quantity,
                'sku' => $sku,
                'unit_of_measure' => $uom,
                'reference_id' => mb_substr((string) $order->order_code . '-' . $lineId, 0, 32),
            ];

            $unitPrice = $this->modelValue($item, [
                'unit_price_net',
                'unit_price',
                'price',
                'sale_price',
            ]);

            if (($unitPrice === null || $unitPrice === '') && $variation) {
                $unitPrice = $variation->unit_price ?? null;
            }

            if (($unitPrice === null || $unitPrice === '') && $product && isset($product->single_price)) {
                $unitPrice = $product->single_price->unit_price ?? null;
            }

            if ($unitPrice !== null && $unitPrice !== '' && is_numeric($unitPrice)) {
                $line['unit_price_net'] = $this->decimal($unitPrice);
            }

            $lines[] = $line;
        }

        return $lines;
    }

    protected function buildShippingAddress($order, $address): array
    {
        $name = trim((string) ($address->name ?? 'Customer'));
        $parts = preg_split('/\s+/u', $name, 2) ?: [];
        $firstName = trim((string) ($parts[0] ?? 'Customer'));
        $lastName = trim((string) ($parts[1] ?? '-'));
        $streetAddress = trim((string) ($address->address ?? ''));
        $phone = trim((string) ($address->phone ?? ''));

        if ($streetAddress === '') {
            throw new \RuntimeException('Smooth requires a customer street address.');
        }

        if ($phone === '') {
            throw new \RuntimeException('Smooth requires a customer phone number.');
        }

        $coordinates = $this->geocoder->getCoordinates($address);
        $lat = $coordinates['lat'] ?? null;
        $lng = $coordinates['lng'] ?? null;

        // Smooth's schema documents these Kuwait defaults when coordinates are missing.
        if ($lat === null || $lng === null) {
            $lat = (float) config('smooth.default_latitude', 29.378586);
            $lng = (float) config('smooth.default_longitude', 47.990341);
            Log::warning('Smooth: address geocoding failed; using Smooth documented default coordinates.', [
                'order' => $order->order_code ?? null,
                'lat' => $lat,
                'long' => $lng,
            ]);
        }

        $addressLine2 = implode(', ', array_values(array_filter([
            $this->addressValue($address, ['building', 'building_no', 'building_number']),
            $this->prefixedAddressValue($address, ['floor', 'floor_no'], 'Floor'),
            $this->prefixedAddressValue($address, ['flat', 'flat_no', 'apartment', 'apartment_no'], 'Flat'),
        ])));

        $country = strtoupper(trim((string) ($address->country->code ?? config('smooth.country', 'KW'))));
        if (strlen($country) !== 2) {
            $country = (string) config('smooth.country', 'KW');
        }

        // Smooth validates phone numbers more strictly than the OpenAPI schema documents.
        // Normalize Kuwait numbers to E.164 so values such as 5XXXXXXX, 9655XXXXXXX,
        // +965 5XXX XXXX and 00965 5XXX XXXX are sent consistently as +965XXXXXXXX.
        $phone = $this->normalizePhoneForSmooth($phone, $country);

        $result = [
            'first_name' => mb_substr($firstName !== '' ? $firstName : 'Customer', 0, 256),
            'last_name' => mb_substr($lastName !== '' ? $lastName : '-', 0, 256),
            'street_address_1' => mb_substr($streetAddress, 0, 256),
            'lat' => (float) $lat,
            'long' => (float) $lng,
            'city' => $this->nullableString($address->city->name ?? null, 256),
            'state' => $this->nullableString($address->state->name ?? null, 256),
            'country' => $country,
            'phone' => $phone,
        ];

        if ($addressLine2 !== '') {
            $result['street_address_2'] = mb_substr($addressLine2, 0, 256);
        }

        $postalCode = trim((string) ($address->postal_code ?? $address->zip ?? ''));
        if ($postalCode !== '') {
            $result['postal_code'] = mb_substr($postalCode, 0, 16);
        }

        $email = trim((string) ($address->email ?? $order->email ?? ''));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['email'] = mb_substr($email, 0, 254);
        }

        return array_filter($result, static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    protected function normalizePhoneForSmooth(string $phone, string $country): string
    {
        $raw = trim($phone);
        $digits = preg_replace('/[^0-9]+/', '', $raw) ?? '';

        if ($digits === '') {
            throw new \RuntimeException('Smooth requires a valid customer phone number.');
        }

        // Convert international 00 prefix to E.164 + prefix.
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (strtoupper($country) === 'KW') {
            // Local Kuwait number: 8 digits -> prepend country code 965.
            if (strlen($digits) === 8) {
                $digits = '965' . $digits;
            }

            if (strlen($digits) !== 11 || !str_starts_with($digits, '965')) {
                throw new \RuntimeException(
                    'Smooth requires a valid Kuwait phone number. Use 8 local digits or +965 followed by 8 digits.'
                );
            }

            return '+' . $digits;
        }

        // For non-Kuwait addresses, preserve/normalize an already international number.
        if (strlen($digits) < 8 || strlen($digits) > 15) {
            throw new \RuntimeException('Smooth requires a valid international customer phone number.');
        }

        return '+' . $digits;
    }

    protected function client($courier)
    {
        return Http::acceptJson()
            ->asJson()
            ->withHeaders([
                // Exact authentication header defined by SmoothAPI.yaml.
                (string) config('smooth.api_key_header', 'api-key') => $this->apiKey($courier->properties),
            ]);
    }

    protected function apiKey($properties): string
    {
        $value = trim((string) ($properties->api_key ?? ''));
        if ($value === '') {
            return '';
        }

        try {
            return trim(Crypt::decryptString($value));
        } catch (\Throwable $e) {
            // Backward-compatible if a key was saved as plain text before Smooth encryption was added.
            return $value;
        }
    }

    protected function orderCollectionUrl(string $storeSlug): string
    {
        return $this->baseUrl()
            . '/api/v1/orders/' . rawurlencode($storeSlug) . '/';
    }

    protected function orderItemUrl(string $storeSlug, string $referenceId): string
    {
        return $this->baseUrl()
            . '/api/v1/orders/' . rawurlencode($storeSlug)
            . '/' . rawurlencode($referenceId) . '/';
    }

    protected function baseUrl(): string
    {
        return rtrim(trim((string) config('smooth.base_url')), '/');
    }

    protected function orderType(): string
    {
        $value = strtoupper(trim((string) config('smooth.order_type', 'NEXT_DAY')));
        $allowed = ['STANDARD', 'EXPRESS', 'SUPER_EXPRESS', 'SAME_DAY', 'NEXT_DAY'];
        return in_array($value, $allowed, true) ? $value : 'NEXT_DAY';
    }

    protected function deliveryMode(): string
    {
        // OrderModel's write schema currently documents STANDARD and WAREHOUSE_PICKUP.
        $value = strtoupper(trim((string) config('smooth.delivery_mode', 'STANDARD')));
        return in_array($value, ['STANDARD', 'WAREHOUSE_PICKUP'], true) ? $value : 'STANDARD';
    }

    protected function responseErrorMessage($response): string
    {
        $json = $response->json();
        $errorCode = data_get($json, 'error.errorCode');
        $errorText = data_get($json, 'error.errorText');

        if ($errorText !== null && $errorText !== '') {
            return trim((string) $errorText) . ($errorCode !== null ? ' (' . $errorCode . ')' : '');
        }

        foreach (['detail', 'message', 'error', 'non_field_errors.0'] as $path) {
            $value = data_get($json, $path);
            if (is_scalar($value) && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        if (is_array($json) && !empty($json)) {
            $encoded = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if (is_string($encoded) && $encoded !== '') {
                return mb_substr($encoded, 0, 1000);
            }
        }

        return translate('Smooth Logistics returned an error while creating the order.');
    }

    protected function modelValue($model, array $keys)
    {
        foreach ($keys as $key) {
            if (is_object($model)) {
                $value = $model->{$key} ?? null;
            } else {
                $value = is_array($model) ? ($model[$key] ?? null) : null;
            }

            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    protected function addressValue($address, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($address->{$key}) && trim((string) $address->{$key}) !== '') {
                return trim((string) $address->{$key});
            }
        }

        return null;
    }

    protected function prefixedAddressValue($address, array $keys, string $prefix): ?string
    {
        $value = $this->addressValue($address, $keys);
        return $value === null ? null : $prefix . ' ' . $value;
    }

    protected function nullableString($value, int $maxLength): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : mb_substr($value, 0, $maxLength);
    }

    protected function decimal($value): string
    {
        return number_format((float) $value, 3, '.', '');
    }
}
