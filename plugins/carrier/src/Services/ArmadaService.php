<?php

namespace Plugin\Carrier\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArmadaService {

    protected $baseUrl = 'https://api.armadadelivery.com';

    /** @var string Free Komoot Photon geocoder (no API key) */
    protected $photonUrl = 'https://photon.komoot.io/api/';

    /** @var string Open Admin Data Kuwait areas endpoint */
    protected $kuwaitAreasApiUrl = 'https://openadmindata.org/api/v1/kw/area.json';

    /** @var float Minimum similar_text percent for Kuwait area fuzzy match */
    protected $kuwaitFuzzyThreshold = 78.0;

    /**
     * Create the delivery order in Armada
     */
    public function createDelivery($courier, $order, $address)
    {
        Log::info("Armada: Starting delivery process for Order #{$order->order_code}");

        try {
            // FAIL-SAFE 1: Validate Required Courier Properties
            $apiKey = trim((string) ($courier->properties->api_key ?? ''));
            $apiSecret = trim((string) ($courier->properties->api_secret ?? ''));
            $branchId = trim((string) ($courier->properties->branch_id ?? ''));

            if ($apiKey === '' || $apiSecret === '') {
                Log::error("Armada: Missing API credentials for courier ID: {$courier->id}");
                return ['success' => false, 'message' => translate('Courier configuration is incomplete.')];
            }

            // FAIL-SAFE 2: Coordinate Check
            $coordinates = $this->getCoordinates($address);
            if (is_null($coordinates['lat']) || is_null($coordinates['lng'])) {
                $geocodeError = $coordinates['error'] ?? translate('Could not determine delivery location. Please verify the address.');
                Log::warning("Armada: Could not geocode address for Order #{$order->order_code}", [
                    'error' => $geocodeError,
                ]);
                return [
                    'success' => false,
                    'message' => translate('Could not determine delivery location.') . ' ' . $geocodeError,
                ];
            }

            if (!empty($coordinates['source'])) {
                Log::info("Armada: Geocoded Order #{$order->order_code}", [
                    'source' => $coordinates['source'],
                    'lat' => $coordinates['lat'],
                    'lng' => $coordinates['lng'],
                    'matched' => $coordinates['matched'] ?? null,
                ]);
            }

            $path = '/v2/deliveries';
            $method = 'POST';
            $timestamp = (string) round(microtime(true) * 1000);

            // FAIL-SAFE 3: Data Integrity & Type Casting
            // Ensure numbers are floats and strings are strings to match Armada's JSON schema
            $orderData = [
                'reference' => (string) $order->order_code,
                'origin_format' => 'branch_format',
                'origin' => ['branch_id' => $branchId],
                'destination_format' => 'location_format',
                'destination' => [
                    'contact_name'  => (string) $address->name,
                    'contact_phone' => (string) $address->phone,
                    'latitude'      => (float) $coordinates["lat"],
                    'longitude'     => (float) $coordinates["lng"],
                    'first_line'    => (string) $address->address,
                ],
                'payment' => [
                    'amount' => (float) $order->total_payable_amount,
                    'type'   => $order->payment_status == config('tlecommercecore.order_payment_status.paid') ? 'paid' : 'cash',
                ],
            ];

            $body = json_encode($orderData, JSON_UNESCAPED_SLASHES);

            // Generate Signature
            $signature = $this->generateSignature(
                $apiSecret,
                $timestamp,
                $method,
                $path,
                $body
            );

            // FAIL-SAFE 4: Network Timeout & Exception Handling
            // We use a timeout so the request doesn't hang the server if Armada is down
            // Send the exact $body bytes used for signing — Armada rejects mismatched HMAC payloads with 401
            // Use send('POST') not post() — Laravel post() injects default [] and overwrites withBody()
            $response = Http::timeout(20)
                ->withHeaders([
                    'Authorization'      => 'Key ' . $apiKey,
                    'X-Armada-Timestamp' => $timestamp,
                    'X-Armada-Signature' => $signature,
                    'Content-Type'       => 'application/json',
                    'Accept'             => 'application/json',
                ])
                ->withBody($body, 'application/json')
                ->send('POST', $this->baseUrl . $path);

            // FAIL-SAFE 5: Handle Non-200 Responses
            if ($response->failed()) {
                $errorData = $response->json();
                $logContext = [
                    'order' => $order->order_code,
                    'status' => $response->status(),
                    'response' => $errorData,
                    'raw_body' => $response->body(),
                ];

                if ($response->status() === 401) {
                    $logContext['api_key_prefix'] = substr($apiKey, 0, 8);
                    $logContext['api_secret_length'] = strlen($apiSecret);
                    $logContext['body_length'] = strlen($body);
                    $logContext['timestamp'] = $timestamp;
                }

                Log::error("Armada API Refused Request:", $logContext);

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


    /**
     * Resolve lat/lng via Nominatim → Photon → Kuwait areas fuzzy match.
     *
     * @param object $address CustomerAddress (with city/state/country relations)
     * @return array{lat: float|null, lng: float|null, source?: string, error?: string, matched?: string}
     */
    public function getCoordinates($address)
    {
        $candidates = $this->buildGeocodeCandidates($address);
        $errors = [];

        if (empty($candidates)) {
            return [
                'lat' => null,
                'lng' => null,
                'error' => 'No address components available to geocode.',
            ];
        }

        // Provider 1: Nominatim (OpenStreetMap)
        $nominatim = $this->geocodeNominatim($candidates);
        if ($nominatim !== null) {
            return $nominatim;
        }
        $errors[] = 'Nominatim: no results for "' . $candidates[0] . '"';

        // Provider 2: Photon (Komoot) — free, no API key
        $photon = $this->geocodePhoton($candidates);
        if ($photon !== null) {
            return $photon;
        }
        $errors[] = 'Photon: no results for "' . $candidates[0] . '"';

        // Provider 3: Kuwait Open Admin Data areas (fuzzy name → area center)
        $kuwait = $this->geocodeKuwaitAreas($address);
        if ($kuwait !== null) {
            return $kuwait;
        }
        $placeHints = $this->collectPlaceHints($address);
        $errors[] = 'Kuwait areas: no close match for "' . implode(', ', $placeHints) . '"';

        return [
            'lat' => null,
            'lng' => null,
            'error' => implode(' | ', $errors),
        ];
    }

    /**
     * Build geocode query candidates: full address → drop leading parts → city/state/country.
     *
     * @param object $address
     * @return string[]
     */
    private function buildGeocodeCandidates($address): array
    {
        $addressParts = array_values(array_filter(array_map('trim', explode(',', (string) ($address->address ?? '')))));

        $tail = [
            $address->city->name ?? null,
            $address->state->name ?? null,
            $address->country->name ?? 'Kuwait',
        ];
        $tail = array_values(array_filter($tail));

        $candidates = [];
        $parts = $addressParts;
        while (!empty($parts)) {
            $candidates[] = implode(', ', array_merge($parts, $tail));
            array_shift($parts);
        }
        if (!empty($tail)) {
            $candidates[] = implode(', ', $tail);
        }

        return array_values(array_unique(array_filter($candidates)));
    }

    /**
     * @param string[] $candidates
     * @return array{lat: float, lng: float, source: string}|null
     */
    private function geocodeNominatim(array $candidates): ?array
    {
        try {
            $httpClient = new \GuzzleHttp\Client(['timeout' => 10]);
            $provider = \Geocoder\Provider\Nominatim\Nominatim::withOpenStreetMapServer($httpClient, 'Platepilots');
            $geocoder = new \Geocoder\StatefulGeocoder($provider, 'en');

            foreach ($candidates as $queryString) {
                Log::info('Trying Nominatim geocode: ' . $queryString);

                $result = $geocoder->geocodeQuery(
                    \Geocoder\Query\GeocodeQuery::create($queryString)
                );

                if (!$result->isEmpty()) {
                    $coordinates = $result->first()->getCoordinates();
                    Log::info('Resolved via Nominatim: ' . $queryString);
                    return [
                        'lat' => $coordinates->getLatitude(),
                        'lng' => $coordinates->getLongitude(),
                        'source' => 'nominatim',
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Nominatim Geocoding Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Free Photon geocoder fallback (same OSM data, different service).
     *
     * @param string[] $candidates
     * @return array{lat: float, lng: float, source: string}|null
     */
    private function geocodePhoton(array $candidates): ?array
    {
        try {
            foreach ($candidates as $queryString) {
                Log::info('Trying Photon geocode: ' . $queryString);

                $response = Http::timeout(8)
                    ->acceptJson()
                    ->get($this->photonUrl, [
                        'q' => $queryString,
                        'limit' => 5,
                        'lang' => 'en',
                    ]);

                if ($response->failed()) {
                    Log::warning('Photon geocode HTTP failure', [
                        'status' => $response->status(),
                        'query' => $queryString,
                    ]);
                    continue;
                }

                $features = $response->json('features') ?? [];
                foreach ($features as $feature) {
                    $coords = $feature['geometry']['coordinates'] ?? null;
                    if (!is_array($coords) || count($coords) < 2) {
                        continue;
                    }

                    $lng = (float) $coords[0];
                    $lat = (float) $coords[1];
                    $country = strtolower((string) ($feature['properties']['country'] ?? ''));
                    $countrycode = strtolower((string) ($feature['properties']['countrycode'] ?? ''));

                    // Prefer Kuwait results when present; still accept if country unknown
                    $isKuwait = $countrycode === 'kw'
                        || str_contains($country, 'kuwait')
                        || str_contains(strtolower($queryString), 'kuwait');

                    if (!$isKuwait && ($country !== '' || $countrycode !== '')) {
                        continue;
                    }

                    Log::info('Resolved via Photon: ' . $queryString, ['lat' => $lat, 'lng' => $lng]);
                    return [
                        'lat' => $lat,
                        'lng' => $lng,
                        'source' => 'photon',
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Photon Geocoding Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Fuzzy-match Kuwait area names (EN/AR) and return area-center coordinates.
     * Prefer city + address tokens over state so "ishbiliya" is not overridden by "Farwaniyah".
     *
     * @param object $address
     * @return array{lat: float, lng: float, source: string, matched: string}|null
     */
    private function geocodeKuwaitAreas($address): ?array
    {
        try {
            $areas = $this->loadKuwaitAreas();
            if (empty($areas)) {
                Log::warning('Kuwait areas dataset empty or unreadable');
                return null;
            }

            $primaryHints = $this->collectPrimaryPlaceHints($address);
            $stateHints = $this->collectStatePlaceHints($address);

            $match = $this->bestKuwaitAreaMatch($areas, $primaryHints);
            if ($match === null) {
                $match = $this->bestKuwaitAreaMatch($areas, $stateHints);
            }

            if ($match !== null) {
                Log::info('Resolved via Kuwait areas fuzzy match', [
                    'hint' => $match['hint'],
                    'matched' => $match['matched'],
                    'score' => $match['score'],
                    'lat' => $match['lat'],
                    'lng' => $match['lng'],
                ]);

                return [
                    'lat' => $match['lat'],
                    'lng' => $match['lng'],
                    'source' => 'kuwait_areas_fuzzy',
                    'matched' => $match['matched'],
                ];
            }
        } catch (\Exception $e) {
            Log::error('Kuwait areas geocode error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * @param array<int, array<string, mixed>> $areas
     * @param string[] $hints
     * @return array{lat: float, lng: float, matched: string, score: float, hint: string}|null
     */
    private function bestKuwaitAreaMatch(array $areas, array $hints): ?array
    {
        if (empty($hints)) {
            return null;
        }

        $best = null;
        $bestScore = 0.0;

        foreach ($hints as $hint) {
            $normalizedHint = $this->normalizePlaceName($hint);
            if ($normalizedHint === '') {
                continue;
            }

            foreach ($areas as $area) {
                $names = array_filter([
                    $area['name_en'] ?? null,
                    $area['name_local'] ?? null,
                    $area['slug'] ?? null,
                ]);

                foreach ($names as $name) {
                    $score = $this->placeNameSimilarity($normalizedHint, (string) $name);
                    if ($score > $bestScore && isset($area['lat'], $area['lon'])) {
                        $bestScore = $score;
                        $best = [
                            'lat' => (float) $area['lat'],
                            'lng' => (float) $area['lon'],
                            'matched' => trim(($area['name_en'] ?? '') . ' / ' . ($area['name_local'] ?? ''), ' /'),
                            'score' => $score,
                            'hint' => $hint,
                        ];
                    }
                }
            }
        }

        if ($best === null || $bestScore < $this->kuwaitFuzzyThreshold) {
            if ($best !== null) {
                Log::info('Kuwait areas: best match below threshold', [
                    'hint' => $best['hint'],
                    'matched' => $best['matched'],
                    'score' => $best['score'],
                    'threshold' => $this->kuwaitFuzzyThreshold,
                ]);
            }
            return null;
        }

        return $best;
    }

    private function placeNameSimilarity(string $normalizedHint, string $name): float
    {
        $normalizedName = $this->normalizePlaceName($name);
        if ($normalizedName === '') {
            return 0.0;
        }

        if ($normalizedHint === $normalizedName) {
            return 100.0;
        }

        similar_text($normalizedHint, $normalizedName, $percent);
        $score = (float) $percent;

        $hintCore = $this->stripPlacePrefixes($normalizedHint);
        $nameCore = $this->stripPlacePrefixes($normalizedName);
        if ($hintCore !== '' && $nameCore !== '') {
            if ($hintCore === $nameCore) {
                return 100.0;
            }
            similar_text($hintCore, $nameCore, $corePercent);
            $score = max($score, (float) $corePercent);

            // Levenshtein boost for short misspellings (ishbiliya ↔ eshbilya)
            if (strlen($hintCore) <= 20 && strlen($nameCore) <= 20) {
                $distance = levenshtein($hintCore, $nameCore);
                $maxLen = max(strlen($hintCore), strlen($nameCore));
                if ($maxLen > 0) {
                    $score = max($score, (1 - ($distance / $maxLen)) * 100);
                }
            }
        }

        return $score;
    }

    /**
     * City + free-text address parts (highest priority for area matching).
     *
     * @param object $address
     * @return string[]
     */
    private function collectPrimaryPlaceHints($address): array
    {
        $hints = [];

        if (!empty($address->city->name)) {
            $hints[] = $address->city->name;
        }

        $addressParts = array_filter(array_map('trim', explode(',', (string) ($address->address ?? ''))));
        foreach ($addressParts as $part) {
            if (preg_match('/^\d+$/', $part) || mb_strlen($part) < 3) {
                continue;
            }
            $hints[] = $part;
        }

        return array_values(array_unique($hints));
    }

    /**
     * State/governorate hints used only if city/address matching fails.
     *
     * @param object $address
     * @return string[]
     */
    private function collectStatePlaceHints($address): array
    {
        if (!empty($address->state->name)) {
            return [$address->state->name];
        }
        return [];
    }

    /**
     * All place hints for error reporting.
     *
     * @param object $address
     * @return string[]
     */
    private function collectPlaceHints($address): array
    {
        return array_values(array_unique(array_merge(
            $this->collectPrimaryPlaceHints($address),
            $this->collectStatePlaceHints($address)
        )));
    }

    private function normalizePlaceName(string $name): string
    {
        $name = mb_strtolower(trim($name));
        // Unify common separators / punctuation
        $name = str_replace(["\n", "\r", "\t"], ' ', $name);
        $name = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $name) ?? $name;
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;
        return trim($name);
    }

    private function stripPlacePrefixes(string $normalized): string
    {
        $normalized = preg_replace('/^(al|el|اال|ال)\s+/u', '', $normalized) ?? $normalized;
        return trim($normalized);
    }

    /**
     * Load Kuwait areas: cache (refreshed from API) → bundled JSON fallback.
     * Empty results are never cached so a transient failure cannot stick for 7 days.
     *
     * @return array<int, array<string, mixed>>
     */
    private function loadKuwaitAreas(): array
    {
        $cached = Cache::get('carrier.kuwait_areas.v1');
        if (is_array($cached) && !empty($cached)) {
            return $cached;
        }

        $remote = $this->fetchKuwaitAreasFromApi();
        if (!empty($remote)) {
            Cache::put('carrier.kuwait_areas.v1', $remote, now()->addDays(7));
            return $remote;
        }

        $bundled = $this->loadBundledKuwaitAreas();
        if (!empty($bundled)) {
            Cache::put('carrier.kuwait_areas.v1', $bundled, now()->addDays(7));
        }

        return $bundled;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchKuwaitAreasFromApi(): array
    {
        try {
            $response = Http::timeout(10)->acceptJson()->get($this->kuwaitAreasApiUrl);
            if ($response->successful()) {
                $entities = $response->json('entities') ?? [];
                if (is_array($entities) && !empty($entities)) {
                    Log::info('Kuwait areas loaded from Open Admin Data API', ['count' => count($entities)]);
                    return $entities;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Kuwait areas API refresh failed: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadBundledKuwaitAreas(): array
    {
        $path = base_path('plugins/carrier/data/kuwait-areas.json');
        if (!is_file($path)) {
            Log::error('Bundled Kuwait areas file missing: ' . $path);
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        $entities = $decoded['entities'] ?? [];

        if (!is_array($entities)) {
            return [];
        }

        Log::info('Kuwait areas loaded from bundled JSON', ['count' => count($entities)]);
        return $entities;
    }

}