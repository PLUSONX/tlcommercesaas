<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public callback authenticated with the X-Karrix-Signature HMAC header.
Route::post('/carrier/karrix/webhook', [CarrierController::class, 'karrixWebhook'])
    ->name('plugin.carrier.karrix.webhook');

// Smooth Logistics order-status callback documented by SmoothAPI.
// This is isolated from the existing Armada/Karrix callback paths.
Route::put('/smooth/orders/status-update/{owner_slug}/{order_id}', [CarrierController::class, 'smoothOrderStatusUpdate'])
    ->name('plugin.carrier.smooth.orders.status.update');


// Smooth also documents these order-operational callbacks. The current courier module
// does not need their warehouse payloads, so they are safely acknowledged with HTTP 200.
Route::put('/smooth/orders/pick-confirmation/{owner_slug}/{order_id}', [CarrierController::class, 'smoothOrderPickConfirmation'])
    ->name('plugin.carrier.smooth.orders.pick.confirmation');

Route::put('/smooth/orders/order-tu-picked-confirmation/{owner_slug}/{order_id}', [CarrierController::class, 'smoothOrderTuPickedConfirmation'])
    ->name('plugin.carrier.smooth.orders.tu.picked.confirmation');
