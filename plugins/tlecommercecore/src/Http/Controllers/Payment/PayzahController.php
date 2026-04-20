<?php

namespace Plugin\TlcommerceCore\Http\Controllers\Payment;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Plugin\TlcommerceCore\Http\Controllers\Payment\PaymentController;
use Illuminate\Support\Facades\DB;
use Plugin\TlcommerceCore\Models\PaymentTransaction;
use Illuminate\Support\Facades\Http;
use Plugin\TlcommerceCore\Models\Orders;
use Plugin\TlcommerceCore\Models\OrderHasProducts;

class PayzahController extends Controller
{

    protected $total_payable_amount;
    protected $payzah_secret_key;
    protected $payment_method_id;
    protected $payment_method;
    protected $payment_transaction_id;
    protected $currency = 'KWD';

    /**
     * Set credentials for the specific tenant
    */
    public function setcredentials()
    {
        // $tenantId = function_exists('tenant') && tenant() ? tenant('id') : 'No Tenant Found';
        // \Log::info('Current Tenant ID: ' . $tenantId);

        $method = DB::table('tl_com_payment_methods')
            ->where('name', 'payzah') 
            ->first();

        if ($method) {
            $this->payment_method_id = $method->id;

            $settings = DB::table('tl_com_payment_method_has_settings')
                        ->where('payment_method_id', $this->payment_method_id)
                        ->pluck('key_value', 'key_name');

            
            $this->payzah_secret_key = $settings['payzah_secret_key'] ?? null;
            $this->currency = $settings['payzah_currency'] ?? 'KWD';

            Log::info('payment settings', [
                'settings' => json_encode($settings),
                'payzah_secret_key' => $this->payzah_secret_key
            ]);


            if (!$this->payzah_secret_key) {
                Log::error('Payzah Settings Missing', [
                    'method_id' => $this->payment_method_id,
                    'available_keys' => $settings->keys()
                ]);
                throw new \Exception('Payzah Private Key is missing from settings. Please check table tl_com_payment_method_has_settings.');
            }
     
        } else {
            \Log::error('Payzah method not found in tl_com_payment_methods table');
        }
    }

    /**
     * This method handles the /payment/payzah/pay route
     */
    // public function pay(Request $request)
    // {
    //     \Log::info('Payzah pay method called !!!!');

    //     $this->setcredentials();

    //     Log::info('credentials data', [
    //             'request' => $request->all(),
    //             'payment_method_id' => $this->payment_method_id,
    //             'payzah_secret_key' => $this->payzah_secret_key,
    //         ]);

    // }


    public function pay(Request $request)
{
    \Log::info('Payzah pay method called');

    // Set credentials (you already have this)
    $this->setcredentials();

    // Get payment data from session (stored during checkout)
    $orderId = session('order_id');
    $payableAmount = session('payable_amount');
    $paymentType = session('payment_type');
    // $customerId = session('customer') ?? session('guest_customer');
    $customerId = session('customer');           // real customer → tl_com_customers
    $guestCustomerId = session('guest_customer'); // guest → tl_com_guest_customers
    
    Log::info('Payzah payment initialization', [
        'order_id' => $orderId,
        'amount' => $payableAmount,
        'payment_method_id' => $this->payment_method_id,
        'customer_id' => $customerId,
        'guest_customer_id' => $guestCustomerId,
    ]);

    // Validate we have required data
    if (!$orderId || !$payableAmount) {
        Log::error('Payzah payment missing required session data');
        return redirect()->route('checkout')->with('error', 'Payment session expired');
    }

    try {
        // Prepare Payzah payment request
        $trackId = $this->generateTrackId($orderId); // We need to create this method
        $successUrl = route('payzah.success'); // Full URL
        $errorUrl = route('payzah.cancel', [
            'trackid' => $trackId,
        ]);
        // $errorUrl = route('payment.payzah.error'); // Full URL

        Log::info('succes & error urls', [
            'successUrl' => $successUrl,
            'errorUrl' => $errorUrl,
        ]);

        // Call Payzah API
        $payzahResponse = $this->initiatePayzahPayment([
            'trackid' => $trackId,
            'amount' => $payableAmount,
            'success_url' => $successUrl,
            'error_url' => $errorUrl,
            'language' => "ENG",
            'currency' => "KD",
            'payment_type' => 1
        ]);

        Log::info('Payzah API response', [
            'response' => $payzahResponse
        ]);

        // Store Payzah payment details in database
        $this->storePaymentTransaction([
            'order_id'    => $orderId,
            'track_id'    => $trackId,
            'payment_id'  => $payzahResponse['data']['PaymentID'],
            'amount'      => $payableAmount,
            'payment_url' => $payzahResponse['data']['direct_url'],
            'customer_id'    => $customerId ?? null,
            'guest_customer' => $guestCustomerId ?? null,
        ]);

        // $this->storePaymentTransaction([
        //     'order_id' => $orderId,
        //     'track_id' => $trackId,
        //     'payment_id' => $payzahResponse['data']['PaymentID'],
        //     'amount' => $payableAmount,
        //     'payment_url' => $payzahResponse['data']['direct_url'],
        //     'customer_id' => auth()->check() ? auth()->id() : null, // Add this
        //     'guest_customer' => null, // Or pass guest ID if you have it
        // ]);

        // $this->storePaymentTransaction([
        //     'order_id' => $orderId,
        //     'track_id' => $trackId,
        //     'payment_id' => $payzahResponse['data']['PaymentID'] ?? null,
        //     'amount' => $payableAmount,
        //     'payment_url' => $payzahResponse['data']['PaymentUrl'] ?? null,
        //     'status' => 'pending',
        // ]);

        // Redirect to Payzah payment page
        return redirect($payzahResponse['data']['direct_url']);

    } catch (\Exception $e) {
        Log::error('Payzah payment failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // return redirect()->route('checkout')->with('error', 'Payment initialization failed');
        return back()->with('error', 'Payment initialization failed');
    }
}

/**
 * Generate unique track ID for the payment
 */
private function generateTrackId($orderId)
{
    // ORDERID + TIMESTAMP + RANDOM (alphanumeric only)
    return $orderId . time();
}

    /**
 * Call Payzah API to initiate payment
 */
private function initiatePayzahPayment($paymentData)
{
    // $apiUrl = 'https://development.payzah.net/ws/paymentgateway/index';
    $apiUrl = 'https://payzah.net/production770/ws/paymentgateway/index';

    try {

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $this->payzah_secret_key,
        ])->timeout(30)->post($apiUrl, $paymentData);

        // Check for HTTP errors (4xx, 5xx)
        if ($response->failed()) {
            Log::error('Payzah HTTP Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Payzah API connection error: ' . $response->status());
        }

        $responseData = $response->json();

        // Check for Payzah-specific logical failure
        if (!isset($responseData['status']) || $responseData['status'] !== true) {
            Log::warning('Payzah Logical Failure', ['response' => $responseData]);
            throw new \Exception($responseData['msg'] ?? 'Payzah payment initialization failed');
        }

        return $responseData;

    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        // Specifically catch timeout or DNS issues
        Log::error('Payzah Connection Timeout', ['error' => $e->getMessage()]);
        throw new \Exception('Could not connect to the payment gateway. Please try again.');

    } catch (\Exception $e) {
        // Catch any other errors (coding errors, etc.)
        Log::error('General Payzah Initiation Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e; 
    }
}

/**
 * Store payment transaction in database
 * * @param array $data
 * @return void
 */
private function storePaymentTransaction($data): void
{
    PaymentTransaction::create([
        'payment_method' => 'payzah',
        'paid_amount'    => $data['amount'],
        'payment_for'    => 'Order ID: ' . $data['order_id'],
        'status'         => 1, // 1 = pending
        'payment_info'   => json_encode([
            'track_id'   => $data['track_id'],
            'payment_id' => $data['payment_id'],
            'payment_url' => $data['payment_url'],
        ]),
        'customer_id'    => $data['customer_id'] ?? null,
        'guest_customer' => $data['guest_customer'] ?? null,
        'user_id'        => null,
    ]);
}
// private function storePaymentTransaction($data): void
// {
//     // 1. Log for debugging
//     Log::info('Storing Payzah Transaction', $data);

//     // 2. Map the data to your existing table structure
//     $transaction = PaymentTransaction::create([
//         'payment_method' => 'payzah',
//         'paid_amount'    => $data['amount'],
//         'payment_for'    => 'Order ID: ' . ($data['order_id'] ?? 'N/A'),
//         'status'         => $this->mapStatusToInt($data['status']), // Status is an INT in your DB
        
//         // Storing technical IDs in payment_info as JSON for future reference
//         'payment_info'   => json_encode([
//             'track_id'   => $data['track_id'] ?? null,
//             'payment_id' => $data['payment_id'] ?? null,
//             'url'        => $data['payment_url'] ?? null,
//         ]),

//         // Logic for Customer vs Guest
//         'customer_id'    => auth('customer')->check() ? auth('customer')->id() : null,
//         'guest_customer' => !auth('customer')->check() ? ($data['guest_id'] ?? null) : null,
//     ]);

//     session(['payzah_last_transaction_id' => $transaction->id]);
// }

/**
 * Convert gateway string status to your DB integer status
 */
private function mapStatusToInt($status)
{
    // Example: If gateway returns 'SUCCESS', map to 1 (based on your DB default)
    return ($status === 'SUCCESS' || $status === 'CAPTURED') ? 1 : 2;
}

public function cancel(Request $request)
{
    $trackId = $request->query('trackid');

    \Log::info('Payzah cancel redirect', [
        'trackid' => $trackId,
        'payload' => $request->all(),
    ]);

    if ($trackId) {
        PaymentTransaction::whereJsonContains('payment_info->track_id', $trackId)
            ->where('status', 1) // only pending
            ->update([
                'status' => 3, // failed
            ]);
    }

    // Inform the customer (browser only)
    session()->flash(
        'payment_error',
        'Your payment failed. Please try again.'
    );

    // return response()->json([
    //     'status' => 'ok'
    // ], 200);

    $redirect_url = '/products';
    return redirect($redirect_url);
    // return redirect()->route('products');
}

//  public function cancel(Request $request)
// {
//     \Log::info('Payzah error callback received', [
//         'request_data' => $request->all(),
//     ]);
//     return (new PaymentController)->payment_cancel();
// }


public function success(Request $request)
{
    \Log::info('Payzah success callback received', [
        'request_data' => $request->all(),
    ]);

    $this->setcredentials();

    try {

        $paymentId = 0;

        $paymentTransactionId = 0;

        $orderId = 0;
        
        $trackId = $request->input('trackId');

        if ($trackId) {
            $paymentTransaction = PaymentTransaction::whereJsonContains('payment_info->track_id', $trackId)
                ->where('status', 1)
                ->first();

            if ($paymentTransaction) {
                // Decode payment_info JSON
                $paymentInfo = json_decode($paymentTransaction->payment_info, true);

                // Get the payment_id from JSON
                $paymentId = $paymentInfo['payment_id'] ?? 0;

                $paymentTransactionId = $paymentTransaction->id ?? 0;

                 // Extract numeric order ID from 'payment_for' string
                if (preg_match('/Order ID:\s*(\d+)/', $paymentTransaction->payment_for, $matches)) {
                    $orderId = (int) $matches[1];
                }
            }

        }

        // Get payment details from callback URL
        // $paymentId = $request->input('PaymentID') ?? $request->input('payment_id') ?? $request->input('payzahRefrenceCode');
        $trackId = $request->input('trackid') ?? $request->input('track_id') ?? $request->input('trackId');
        
        if (empty($paymentId) || empty($trackId)) {
            \Log::error('Payzah success: Missing required parameters', [
                'payment_id' => $paymentId,
                'track_id' => $trackId,
            ]);
            return (new PaymentController)->payment_failed();
        }

        // CRITICAL: Verify payment status with Payzah server
        $paymentDetails = $this->verifyPayzahPayment($paymentId, $trackId);
        
        if (!$paymentDetails) {
            \Log::error('Payzah payment verification failed', [
                'payment_id' => $paymentId,
                'track_id' => $trackId,
            ]);
            return (new PaymentController)->payment_failed();
        }

        // Check payment status
        $paymentStatus = $paymentDetails['paymentStatus'] ?? '';
        if ($paymentStatus !== 'CAPTURED' && $paymentStatus !== 'NOT CAPTURED') {
            \Log::error('Payzah payment not successful', [
                'payment_id' => $paymentId,
                'track_id' => $trackId,
                'status' => $paymentStatus,
            ]);
            return (new PaymentController)->payment_failed();
        }

        // Extract order ID from track_id (format: ORDER_ID-TIMESTAMP-RANDOM)
        // $orderId = $this->extractOrderIdFromTrackId($trackId);

        \Log::info('compairing orderIds', [
            'session_order_id' =>  session('order_id'),
            'order_id' => $orderId,
        ]);
        
        // Verify this matches our session data for additional security
        // $sessionOrderId = session('order_id');
        // if ($orderId != $sessionOrderId) {
        //     \Log::error('Payzah order ID mismatch', [
        //         'track_id_order' => $orderId,
        //         'session_order' => $sessionOrderId,
        //     ]);
        //     return (new PaymentController)->payment_failed();
        // }

        // Verify UDF fields match (tenant_id, customer_id for extra security)
        // $sessionCustomerId = session('customer') ?? session('guest_customer');
        // $udf2 = $paymentDetails['UDF2'] ?? '';
        
        // if (!empty($udf2) && $udf2 != $sessionCustomerId) {
        //     \Log::warning('Payzah customer ID mismatch', [
        //         'udf2' => $udf2,
        //         'session_customer' => $sessionCustomerId,
        //     ]);
        // }

        // Update payment transaction status in database
        $this->updatePaymentTransaction($trackId, $paymentId, $paymentTransactionId, 'success', $paymentDetails);

        // Format payment ID similar to PayPal
        $formattedPaymentId = 'payzah-' . $paymentId;
        
        \Log::info('Payzah payment verified and successful', [
            'payment_id' => $paymentId,
            'track_id' => $trackId,
            'order_id' => $orderId,
            'knet_payment_id' => $paymentDetails['knetPaymentId'] ?? null,
            'transaction_number' => $paymentDetails['transactionNumber'] ?? null,
        ]);

        //updating order payment status
        $this->updateOrderTransaction($orderId);

        //Redirect order success page
        $redirect_url = '/order-success' . '/' . $orderId;

        return redirect($redirect_url);

        // Call the common payment success handler
        // return (new PaymentController)->payment_success(json_encode($formattedPaymentId));

    } catch (\Exception $ex) {
        \Log::error('Payzah success callback exception', [
            'error' => $ex->getMessage(),
            'trace' => $ex->getTraceAsString(),
        ]);
        return (new PaymentController)->payment_failed();
    }
}

/**
 * Verify payment with Payzah API
 */
private function verifyPayzahPayment($paymentId, $trackId)
{
    try {
        // $verifyUrl =  'https://development.payzah.net/ws/paymentgateway/get-payment-details'; // Update with production URL
        $verifyUrl =  'https://payzah.net/production770/ws/paymentgateway/get-payment-details'; // Update with production URL
        
        \Log::info('Verifying Payzah payment', [
            'url' => $verifyUrl,
            'payment_id' => $paymentId,
            'track_id' => $trackId,
        ]);

        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $this->payzah_secret_key,
        ])->post($verifyUrl, [
            'trackid' => $trackId,
            'payment_id' => $paymentId,
        ]);

        if ($response->status() !== 200) {
            \Log::error('Payzah verification API returned non-200', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        }

        $data = $response->json();
        
        \Log::info('Payzah verification response', [
            'response' => $data,
        ]);

        // Check if verification was successful
        if (!isset($data['status']) || $data['status'] !== true) {
            \Log::error('Payzah verification status false', [
                'response' => $data,
            ]);
            return false;
        }

        // Return the payment details
        return $data['data'] ?? false;
        
    } catch (\Exception $e) {
        \Log::error('Payzah verification API exception', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return false;
    }
}

/**
 * Extract order ID from track ID
 */
private function extractOrderIdFromTrackId($trackId)
{
    // Track ID format: ORDER_ID-TIMESTAMP-RANDOM
    // Example: 1234-1738429860-abc123
    $parts = explode('-', $trackId);
    return $parts[0] ?? null;
}

/**
 * Update Order in database
 */
private function updateOrderTransaction($orderId) 
{
    \Log::info('updateOrderTransaction method called!!!', [
        'order_id' => $orderId,
    ]);

    // Find the order by ID and update payment_status
    Orders::where('id', $orderId)
        ->update([
            'payment_status' => 1, // mark as failed or whatever your logic requires
        ]);

    OrderHasProducts::where('order_id', $orderId)
        ->update([
            'payment_status' => 1, // mark as failed or whatever your logic requires
        ]);

    \Log::info('Order Payment Status updated successfully', [
        'order_id' => $orderId,
    ]);
}

/**
 * Update payment transaction in database
 */
private function updatePaymentTransaction($trackId, $paymentId, $paymentTransactionId, $status, $paymentDetails = [])
{
    \Log::info('updatePaymentTransaction method called!!!');
    // Try to get the ID from the property, fallback to Session
    // $id = session('payzah_last_transaction_id');

    // Find by ID (much faster and more accurate)
    $transaction = PaymentTransaction::find($paymentTransactionId);

    if ($transaction) {
        $info = json_decode($transaction->payment_info, true) ?? [];
        $info['payment_id'] = $paymentId;
        $info['callback_details'] = $paymentDetails;

        $transaction->update([
            'status'       => 2, // 2 = Success
            'payment_info' => json_encode($info),
        ]);

        // Clean up the session after a successful update
        session()->forget('payzah_last_transaction_id');
        
        Log::info("Transaction #{$paymentTransactionId} updated successfully.");
    } else {
        Log::error("Update failed: Transaction ID not found in session or property.");
    }
}
    
}