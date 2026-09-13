<?php

/*
|--------------------------------------------------------------------------
| Smooth Logistics
|--------------------------------------------------------------------------
|
| Credentials are stored in the existing courier properties table:
|   api_key   = Smooth "api-key" credential
|   branch_id = Smooth pre-shared Store Slug
|
| No database migration is required.
|
| The QA base URL comes from the documentation supplied by Smooth. When
| Smooth gives the production API URL, change SMOOTH_BASE_URL only.
|
*/

return [
    'base_url' => env('SMOOTH_BASE_URL', 'https://link.smoothlogistics.co'),
    'api_key_header' => 'api-key',
    'timeout' => (int) env('SMOOTH_TIMEOUT', 20),

    // Smooth OrderModel defaults/documented values.
    'order_type' => env('SMOOTH_ORDER_TYPE', 'NEXT_DAY'),
    'delivery_mode' => env('SMOOTH_DELIVERY_MODE', 'STANDARD'),
    'default_uom' => env('SMOOTH_DEFAULT_UOM', 'EA'),
    'country' => env('SMOOTH_COUNTRY', 'KW'),
    'currency' => env('SMOOTH_CURRENCY', 'KWD'),
    'requires_proof_of_delivery' => filter_var(
        env('SMOOTH_REQUIRES_PROOF_OF_DELIVERY', false),
        FILTER_VALIDATE_BOOLEAN
    ),

    // Smooth's ApiAddressModel documents these defaults for missing coordinates.
    'default_latitude' => (float) env('SMOOTH_DEFAULT_LATITUDE', 29.378586),
    'default_longitude' => (float) env('SMOOTH_DEFAULT_LONGITUDE', 47.990341),
];
