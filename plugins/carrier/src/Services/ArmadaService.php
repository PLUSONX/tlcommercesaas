<?php

namespace Plugin\Carrier\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Geocoder\Query\GeocodeQuery;

class ArmadaService {

    protected $baseUrl = 'https://api.armadadelivery.com';

    /**
     * Create the delivery order in Armada
     */
    public function createDelivery($courier, $order, $address)
    {
        Log::info("Armada: Starting delivery process for Order #{$order->order_code}");

        try {
            // FAIL-SAFE 1: Validate Required Courier Properties
            if (!isset($courier->properties->api_key) || !isset($courier->properties->api_secret)) {
                Log::error("Armada: Missing API credentials for courier ID: {$courier->id}");
                return ['success' => false, 'message' => translate('Courier configuration is incomplete.')];
            }

            // FAIL-SAFE 2: Coordinate Check
            $coordinates = $this->getCoordinates($address);
            if (is_null($coordinates['lat']) || is_null($coordinates['lng'])) {
                Log::warning("Armada: Could not geocode address for Order #{$order->order_code}");
                return [
                    'success' => false, 
                    'message' => translate('Could not determine delivery location. Please verify the address.')
                ];
            }

            $path = '/v2/deliveries';
            $method = 'POST';
            $timestamp = (string) round(microtime(true) * 1000);

            // FAIL-SAFE 3: Data Integrity & Type Casting
            // Ensure numbers are floats and strings are strings to match Armada's JSON schema
            $orderData = [
                'reference' => (string) $order->order_code,
                'origin_format' => 'branch_format',
                'origin' => ['branch_id' => $courier->properties->branch_id],
                'destination_format' => 'location_format',
                'destination' => [
                    'contact_name'  => (string) $address->name,
                    'contact_phone' => (string) $address->phone,
                    'latitude'      => (float) $coordinates["lat"],
                    'longitude'     => (float) $coordinates["lng"],
                    'address'       => (string) $address->address,
                ],
                'payment' => [
                    'amount' => (float) $order->total_payable_amount, 
                    'type'   => $order->payment_status == 1 ? 'paid' : 'unpaid'
                ],
            ];

            $body = json_encode($orderData, JSON_UNESCAPED_SLASHES);

            // Generate Signature
            $signature = $this->generateSignature(
                $courier->properties->api_secret, 
                $timestamp, 
                $method, 
                $path, 
                $body
            );

            // FAIL-SAFE 4: Network Timeout & Exception Handling
            // We use a timeout so the request doesn't hang the server if Armada is down
            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization'      => 'Key ' . $courier->properties->api_key,
                    'X-Armada-Timestamp' => $timestamp,
                    'X-Armada-Signature' => $signature,
                    'Content-Type'       => 'application/json',
                    'Accept'             => 'application/json',
                ])->post($this->baseUrl . $path, $orderData);

            // FAIL-SAFE 5: Handle Non-200 Responses
            if ($response->failed()) {
                $errorData = $response->json();
                Log::error("Armada API Refused Request:", [
                    'order' => $order->order_code,
                    'status' => $response->status(),
                    'response' => $errorData
                ]);

                return [
                    'success' => false,
                    'message' => $errorData['message'] ?? translate('Delivery service provider returned an error.')
                ];
            }

            return [
                'success' => true,
                'data' => $response->json(),
                'message' => translate('Delivery request submitted successfully.')
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // FAIL-SAFE 6: Connection/Timeout Error
            Log::emergency("Armada API Timeout: " . $e->getMessage());
            return ['success' => false, 'message' => translate('Connection to delivery service timed out. Please try again.')];
            
        } catch (\Exception $e) {
            // FAIL-SAFE 7: General Catch-all
            Log::critical("General Error in createDelivery: " . $e->getMessage());
            return ['success' => false, 'message' => translate('An unexpected error occurred.')];
        }
    }
    // public function createDelivery($courier, $order, $address)
    // {
    //     Log::info("createDelivery method called!");

    //     $coordinates = $this->getCoordinates($address);

    //     Log::info("Coordinates data:", ['coordinates' => json_encode($coordinates)]);
        
    //     $path = '/v2/deliveries';
    //     $method = 'POST';
    //     $timestamp = (string) round(microtime(true) * 1000);
        
    //     // Ensure JSON is encoded exactly as it will be sent
    //     // $body = json_encode($orderData, JSON_UNESCAPED_SLASHES);

    //     $body = json_encode([
    //         'reference' => $order->order_code,
    //         'origin_format' => 'branch_format',
    //         'origin' => ['branch_id' => $courier->properties->branch_id],
    //         'destination_format' => 'location_format',
    //         'destination' => [
    //             'contact_name' => $address->name,
    //             'contact_phone' => $address->phone,
    //             'latitude' => $coordinates["lat"],
    //             'longitude' => $coordinates["lng"],
    //             'address' => $address->address,
    //         ],
    //         'payment' => ['amount' => $order->total_payable_amount, 'type' => 'paid'],
    //     ], JSON_UNESCAPED_SLASHES);

    //     Log::info("body data:", ['body' => $body]);

    //     // // Generate Signature
    //     $signature = $this->generateSignature(
    //         $courier->properties->api_secret, 
    //         $timestamp, 
    //         $method, 
    //         $path, 
    //         $body
    //     );

    //     // Execute Request
    //     $response = Http::withHeaders([
    //         'Authorization'      => 'Key ' . $courier->properties->api_key,
    //         'X-Armada-Timestamp' => $timestamp,
    //         'X-Armada-Signature' => $signature,
    //         'Content-Type'       => 'application/json',
    //     ])->post($this->baseUrl . $path, $body);

    //     if ($response->failed()) {
    //         Log::error("Armada API Error:", [
    //             'status' => $response->status(),
    //             'body'   => $response->json()
    //         ]);
    //     }

    //     return $response->json();
    // }

    /**
     * The core signature logic based on Armada's documentation
     */
    private function generateSignature($secret, $timestamp, $method, $path, $body = '')
    {
        $payload = sprintf('%s.%s.%s.%s', $timestamp, strtoupper($method), $path, $body);
        return hash_hmac('sha256', $payload, $secret);
    }


    public function getCoordinates($address)
    {
        try {
            $httpClient = new \GuzzleHttp\Client();
            $provider = \Geocoder\Provider\Nominatim\Nominatim::withOpenStreetMapServer($httpClient, 'Platepilots');
            $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

            // Split the address field itself into parts too
            $addressParts = array_filter(array_map('trim', explode(',', $address->address)));

            $tail = [
                $address->city ? $address->city->name : null,
                $address->state ? $address->state->name : null,
                $address->country ? $address->country->name : 'Kuwait'
            ];
            $tail = array_values(array_filter($tail));

            // Build candidates: try dropping leading address parts one by one
            $candidates = [];
            while (!empty($addressParts)) {
                $candidates[] = array_merge($addressParts, $tail);
                array_shift($addressParts); // drop most-specific part (e.g. "Kipco Tower")
            }
            // Final fallback: just city/state/country
            $candidates[] = $tail;

            foreach ($candidates as $components) {
                $queryString = implode(', ', $components);
                Log::info("Trying geocode: " . $queryString);

                $result = $geocoder->geocodeQuery(
                    \Geocoder\Query\GeocodeQuery::create($queryString)
                );

                if (!$result->isEmpty()) {
                    $coordinates = $result->first()->getCoordinates();
                    Log::info("Resolved via: " . $queryString);
                    return [
                        'lat' => $coordinates->getLatitude(),
                        'lng' => $coordinates->getLongitude(),
                    ];
                }
            }

        } catch (\Exception $e) {
            \Log::error("Nominatim Geocoding Error: " . $e->getMessage());
        }

        return ['lat' => null, 'lng' => null];
    }

}