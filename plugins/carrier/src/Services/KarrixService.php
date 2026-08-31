<?php

namespace Plugin\Carrier\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KarrixService
{
    protected $baseUrl = 'https://karrix.sh';

    protected $geocoder;

    public function __construct(ArmadaService $geocoder)
    {
        // Reuse the already-tested Kuwait geocoder without changing Armada's flow.
        $this->geocoder = $geocoder;
    }

    /**
     * Create and broadcast an order through the Karrix Client API.
     */
    public function createDelivery($courier, $order, $address): array
    {
        $token = $this->decryptToken((string) ($courier->properties->api_key ?? ''));
        $pickupLocationId = trim((string) ($courier->properties->branch_id ?? ''));

        if ($token === '' || $pickupLocationId === '') {
            return [
                'success' => false,
                'message' => translate('Karrix configuration is incomplete. Add the API token and pickup location ID.'),
            ];
        }

        Log::info('Karrix: Starting delivery process', ['order' => $order->order_code]);

        try {
            $pickupLocation = $this->getPickupLocation($token, $pickupLocationId);
            if ($pickupLocation === null) {
                return [
                    'success' => false,
                    'message' => translate('The Karrix pickup location ID is invalid or unavailable.'),
                ];
            }

            $deliveryArea = $this->resolveDeliveryArea($address);
            if ($deliveryArea === null) {
                return [
                    'success' => false,
                    'message' => translate('Could not match the customer address to a Karrix delivery area.'),
                ];
            }

            $coordinates = $this->geocoder->getCoordinates($address);
            $isPaid = $order->payment_status == config('tlecommercecore.order_payment_status.paid');

            $payload = [
                'client_reference' => (string) $order->order_code,
                'pickup_location_id' => (int) $pickupLocationId,
                'pickup_area_id' => (int) $this->value($pickupLocation, ['area_id', 'area.id']),
                'pickup_building' => $this->stringValue($pickupLocation, ['building', 'building_no', 'building_number'], 'N/A'),
                'pickup_floor' => $this->stringValue($pickupLocation, ['floor'], '0'),
                'pickup_flat' => $this->stringValue($pickupLocation, ['flat', 'apartment', 'apartment_no'], '0'),
                'pickup_contact_name' => $this->stringValue($pickupLocation, ['contact_name', 'name'], 'Store'),
                'pickup_contact_phone' => $this->stringValue($pickupLocation, ['contact_phone', 'phone'], ''),
                'pickup_address' => $this->stringValue($pickupLocation, ['address', 'full_address'], ''),
                'delivery_area_id' => (int) $deliveryArea['id'],
                'delivery_contact_name' => trim((string) ($address->name ?? 'Customer')),
                'delivery_contact_phone' => trim((string) ($address->phone ?? '')),
                'delivery_building' => $this->addressValue($address, ['building', 'building_no', 'building_number'], 'N/A'),
                'delivery_floor' => $this->addressValue($address, ['floor', 'floor_no'], '0'),
                'delivery_flat' => $this->addressValue($address, ['flat', 'flat_no', 'apartment', 'apartment_no'], '0'),
                'delivery_address' => trim((string) ($address->address ?? '')),
                'vehicle_type' => 'any',
                'priority' => 'normal',
                'package_description' => 'Order ' . (string) $order->order_code,
                'is_cod' => !$isPaid,
                'cod_amount' => $isPaid ? 0.0 : (float) $order->total_payable_amount,
                'order_total' => (float) $order->total_payable_amount,
            ];

            if (!empty($coordinates['lat']) && !empty($coordinates['lng'])) {
                $payload['delivery_lat'] = (float) $coordinates['lat'];
                $payload['delivery_lng'] = (float) $coordinates['lng'];
            }

            $pickupLat = $this->value($pickupLocation, ['lat', 'latitude']);
            $pickupLng = $this->value($pickupLocation, ['lng', 'longitude']);
            $pickupLat = (float) $pickupLat;
            $pickupLng = (float) $pickupLng;

            if (
                $pickupLat >= 28.0 && $pickupLat <= 30.5 &&
                $pickupLng >= 46.0 && $pickupLng <= 49.0
            ) {
                $payload['pickup_lat'] = $pickupLat;
                $payload['pickup_lng'] = $pickupLng;
            }

            $response = $this->client($token)
                ->timeout(20)
                ->post($this->baseUrl . '/api/v1/orders', $payload);

            if ($response->failed()) {
                $errorCode = (string) $response->json('error.code', '');

                // A previous request may have reached Karrix while the local save failed.
                if ($errorCode === 'DUPLICATE_REFERENCE') {
                    $existing = $this->findExistingOrder($token, (string) $order->order_code);
                    if ($existing !== null) {
                        return $this->success($existing, true);
                    }
                }

                Log::error('Karrix API refused create order', [
                    'order' => $order->order_code,
                    'status' => $response->status(),
                    'error' => $response->json('error'),
                ]);

                return [
                    'success' => false,
                    'message' => $response->json('error.message')
                        ?? $response->json('message')
                        ?? translate('Karrix returned an error while creating the delivery.'),
                ];
            }

            $data = $response->json('data');
            if (!is_array($data)) {
                $data = $response->json();
            }

            return $this->success(is_array($data) ? $data : []);
        } catch (ConnectionException $e) {
            Log::error('Karrix connection failure', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => translate('Connection to Karrix timed out. Please try again.'),
            ];
        } catch (\Throwable $e) {
            Log::error('Karrix create delivery failure', [
                'order' => $order->order_code ?? null,
                'message' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => translate('An unexpected Karrix integration error occurred.'),
            ];
        }
    }

    protected function success(array $data, bool $recovered = false): array
    {
        $tracking = $data['tracking_number'] ?? $data['tracking'] ?? null;
        if ($tracking) {
            $data['tracking_url'] = $data['tracking_url'] ?? $this->baseUrl . '/track/' . rawurlencode($tracking);
        }

        return [
            'success' => true,
            'data' => $data,
            'message' => $recovered
                ? translate('Existing Karrix delivery recovered successfully.')
                : translate('Delivery request submitted to Karrix successfully.'),
        ];
    }

    protected function getPickupLocation(string $token, string $id): ?array
    {
        $response = $this->client($token)->timeout(15)->get($this->baseUrl . '/api/v1/locations');
        if ($response->failed()) {
            Log::warning('Karrix pickup locations request failed', ['status' => $response->status()]);
            return null;
        }

        $locations = $response->json('data', []);
        foreach (is_array($locations) ? $locations : [] as $location) {
            if ((string) ($location['id'] ?? '') === (string) $id) {
                return $location;
            }
        }

        return null;
    }

    protected function resolveDeliveryArea($address): ?array
    {
        $areas = Cache::remember('carrier.karrix.areas.v1', now()->addHours(12), function () {
            $response = Http::acceptJson()->timeout(15)->get($this->baseUrl . '/api/v1/areas');
            return $response->successful() && is_array($response->json('data'))
                ? $response->json('data')
                : [];
        });

        $hints = array_values(array_filter([
            $address->city->name ?? null,
            $address->state->name ?? null,
            ...array_map('trim', explode(',', (string) ($address->address ?? ''))),
        ]));

        $best = null;
        $bestScore = 0.0;
        foreach ($hints as $hint) {
            $hint = $this->normalizeName((string) $hint);
            foreach ($areas as $area) {
                foreach (['name', 'name_en', 'name_ar', 'display_name'] as $key) {
                    $name = $this->normalizeName((string) ($area[$key] ?? ''));
                    if ($hint === '' || $name === '') {
                        continue;
                    }
                    similar_text($hint, $name, $score);
                    if ($hint === $name) {
                        $score = 100;
                    }
                    if ($score > $bestScore && !empty($area['id'])) {
                        $bestScore = $score;
                        $best = $area;
                    }
                }
            }
        }

        return $bestScore >= 72 ? $best : null;
    }

    protected function findExistingOrder(string $token, string $reference): ?array
    {
        $response = $this->client($token)->timeout(15)->get($this->baseUrl . '/api/v1/orders');
        if ($response->failed()) {
            return null;
        }

        foreach ($response->json('data', []) as $order) {
            if ((string) ($order['client_reference'] ?? '') === $reference) {
                return $order;
            }
        }

        return null;
    }

    protected function client(string $token)
    {
        return Http::acceptJson()->asJson()->withToken($token);
    }

    protected function decryptToken(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        try {
            return trim(Crypt::decryptString($value));
        } catch (\Throwable $e) {
            // Backward-compatible with a token saved before encryption was enabled.
            return $value;
        }
    }

    protected function normalizeName(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value) ?? $value;
        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    protected function value(array $data, array $keys)
    {
        foreach ($keys as $key) {
            $value = data_get($data, $key);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }
        return null;
    }

    protected function stringValue(array $data, array $keys, string $default): string
    {
        $value = $this->value($data, $keys);
        return $value === null ? $default : trim((string) $value);
    }

    protected function addressValue($address, array $keys, string $default): string
    {
        foreach ($keys as $key) {
            if (isset($address->{$key}) && trim((string) $address->{$key}) !== '') {
                return trim((string) $address->{$key});
            }
        }
        return $default;
    }
}
