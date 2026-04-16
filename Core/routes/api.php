<?php

use Illuminate\Support\Facades\Route;
use Core\Http\Controllers\SystemController;
use Core\Http\Controllers\Api\TranslationController;
use Core\Http\Controllers\Api\DeviceTokenController;
use Plugin\Carrier\Http\Controllers\CarrierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::get('/locale/{lang}', [TranslationController::class, 'themeTranslations']);
    Route::get('/cache-reset', [SystemController::class, 'clearSystemCacheFromApi']);
});

Route::group(['prefix' => 'webhooks'], function () {
    Route::post('/order-update', [CarrierController::class, 'updateShippingCourierOrders']);
});

Route::post('/device-tokens', [DeviceTokenController::class, 'store']);

Route::post('/test-api', function () {
    return response()->json([
        'status' => 'success2',
        'message' => 'API route reached!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Route::group(['middleware' => 'auth'], function () {

//     Route::post('/test-api', [DeviceTokenController::class, 'store']);

// });

// For admin/user routes
// Route::middleware(['auth:jwt-auth'])->prefix('admin')->group(function () {

//     Route::post('/test-api', [DeviceTokenController::class, 'store']);
//     // Route::post('/device-tokens', [DeviceTokenController::class, 'store']);

//     Route::get('/device-tokens', [DeviceTokenController::class, 'index']);
//     Route::delete('/device-tokens/{token}', [DeviceTokenController::class, 'destroy']);
//     Route::delete('/device-tokens', [DeviceTokenController::class, 'destroyAll']);
// });

// // For customer routes
// Route::middleware(['auth:jwt-customer'])->prefix('customer')->group(function () {
//     Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
//     Route::get('/device-tokens', [DeviceTokenController::class, 'index']);
//     Route::delete('/device-tokens/{token}', [DeviceTokenController::class, 'destroy']);
//     Route::delete('/device-tokens', [DeviceTokenController::class, 'destroyAll']);
// });

// Route::middleware(['auth:api'])->group(function () {
//     // Register or update device token

//     Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
    
//     // Get all device tokens for authenticated user
//     Route::get('/device-tokens', [DeviceTokenController::class, 'index']);
    
//     // Delete specific device token
//     Route::delete('/device-tokens/{token}', [DeviceTokenController::class, 'destroy']);
    
//     // Delete all device tokens for authenticated user
//     Route::delete('/device-tokens', [DeviceTokenController::class, 'destroyAll']);
// });

// Route::middleware('auth:sanctum')->post('/test-auth', function () {
//     return response()->json([
//         'status' => 'success',
//         'user_id' => auth()->id(),
//         'message' => 'Authenticated!'
//     ]);
// });

// Route::post('/test-api', [DeviceTokenController::class, 'store']);

// Route::post('/test-api', function () {
//     return response()->json([
//         'status' => 'success2',
//         'message' => 'API route reached!',
//         'timestamp' => now()->toDateTimeString()
//     ]);
// });

// Route::post('/device-2', function () {
//     return response()->json(['route' => 'hit']);
// });