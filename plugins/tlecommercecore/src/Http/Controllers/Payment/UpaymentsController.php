<?php

namespace Plugin\TlcommerceCore\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Plugin\TlcommerceCore\Http\Controllers\Payment\PaymentController;
use Plugin\TlcommerceCore\Models\OrderHasProducts;
use Plugin\TlcommerceCore\Models\Orders;
use Plugin\TlcommerceCore\Models\PaymentTransaction;
use Plugin\TlcommerceCore\Repositories\EcommerceNotification;

class UpaymentsController extends Controller
{
    protected $apiToken;
    protected $currency = 'KWD';
    protected $gatewaySrc = 'knet';
    protected $paymentMethodId;
    protected $sandbox = true;
    protected $integrationMode = 'non_whitelabel';
    protected $callbackBaseUrl = '';

    protected const ALLOWED_RESULTS = ['CAPTURED', 'FAILED', 'CANCELLED', 'PENDING', 'NOT CAPTURED'];

    /**
     * Load tenant credentials from payment method settings.
     */
    public function setcredentials(?int $methodId = null): void
    {
        $methodId = $methodId ?? session('payment_method_id') ?? config('tlecommercecore.payment_methods.upayments');

        $method = DB::table('tl_com_payment_methods')
            ->where('id', $methodId)
            ->first();

        if (!$method) {
            Log::error('Upayments method not found in tl_com_payment_methods table', [
                'method_id' => $methodId,
            ]);
            throw new \Exception('Upayments payment method not found.');
        }

        $this->paymentMethodId = (int) $method->id;

        $settings = DB::table('tl_com_payment_method_has_settings')
            ->where('payment_method_id', $this->paymentMethodId)
            ->pluck('key_value', 'key_name');

        $this->apiToken = $this->normalizeApiToken($settings['upayments_api_token'] ?? null);
        $this->currency = $settings['upayments_currency'] ?? 'KWD';
        $this->sandbox = (int) ($settings['sandbox'] ?? config('settings.general_status.active')) === (int) config('settings.general_status.active');
        $this->integrationMode = $settings['upayments_integration_mode'] ?? 'non_whitelabel';
        $this->callbackBaseUrl = trim((string) ($settings['upayments_callback_base_url'] ?? ''));

        $applePayId = (int) config('tlecommercecore.payment_methods.upayments_apple_pay');
        if ($this->paymentMethodId === $applePayId) {
            $this->gatewaySrc = $settings['upayments_gateway_src'] ?? 'apple-pay';
            if ($this->integrationMode !== 'whitelabel') {
                $this->integrationMode = 'whitelabel';
            }
        } else {
            $this->gatewaySrc = $settings['upayments_gateway_src'] ?? 'knet';
        }

        if (!$this->apiToken) {
            Log::error('Upayments Settings Missing', [
                'method_id' => $this->paymentMethodId,
                'available_keys' => $settings->keys(),
            ]);
            throw new \Exception('Upayments API token is missing from settings. Please check tl_com_payment_method_has_settings.');
        }
    }

    /**
     * Initiate uPayments charge and redirect customer to hosted payment page.
     */
    public function pay(Request $request)
    {
        if ($request->query('success') === 'failed') {
            return view('plugin/tlecommercecore::payments.errors.payment_failed')
                ->with(['gateway' => 'upayments']);
        }

        Log::info('Upayments pay method called');

        $this->setcredentials();

        $orderId = session('order_id');
        $payableAmount = session('payable_amount');
        $customerId = session('customer');
        $guestCustomerId = session('guest_customer');

        Log::info('Upayments payment initialization', [
            'order_id' => $orderId,
            'amount' => $payableAmount,
            'payment_method_id' => $this->paymentMethodId,
            'gateway_src' => $this->gatewaySrc,
            'integration_mode' => $this->integrationMode,
            'sandbox' => $this->sandbox,
            'customer_id' => $customerId,
            'guest_customer_id' => $guestCustomerId,
        ]);

        if (!$orderId || !$payableAmount) {
            Log::error('Upayments payment missing required session data');
            return redirect('/checkout')->with('error', 'Payment session expired');
        }

        try {
            $order = Orders::with(['products.product_details', 'customer_info', 'guest_customer', 'billing_details', 'shipping_details'])
                ->find((int) $orderId);

            if (!$order) {
                throw new \Exception('Order not found for Upayments payment.');
            }

            $requestedOrderId = (string) $orderId . time() . rand(100, 999);
            $reference = 'REF' . $requestedOrderId;
            $referenceId = $order->order_code ? (string) $order->order_code : ('ORD' . $orderId);

            $chargePayload = $this->buildChargePayload(
                $order,
                $requestedOrderId,
                $reference,
                $referenceId,
                (float) $payableAmount,
                $customerId,
                $guestCustomerId
            );

            Log::info('Upayments charge request', [
                'requested_order_id' => $requestedOrderId,
                'integration_mode' => $this->integrationMode,
                'includes_payment_gateway' => isset($chargePayload['paymentGateway']),
                'includes_tokens' => isset($chargePayload['tokens']),
                'customer_keys' => array_keys($chargePayload['customer'] ?? []),
                'order_amount' => $chargePayload['order']['amount'] ?? null,
                'return_url' => $chargePayload['returnUrl'] ?? null,
                'cancel_url' => $chargePayload['cancelUrl'] ?? null,
                'notification_url' => $chargePayload['notificationUrl'] ?? null,
            ]);

            $chargeResponse = $this->initiateCharge($chargePayload);

            $paymentLink = $chargeResponse['data']['link'] ?? null;
            $trackId = $chargeResponse['data']['trackId'] ?? null;

            if (!$paymentLink) {
                Log::error('Upayments payment link missing', ['response' => $chargeResponse]);
                throw new \Exception('Upayments payment initialization failed: payment link missing');
            }

            $this->storePaymentTransaction([
                'order_id' => $orderId,
                'amount' => $payableAmount,
                'requested_order_id' => $requestedOrderId,
                'reference' => $reference,
                'track_id' => $trackId,
                'payment_link' => $paymentLink,
                'customer_id' => $customerId ?? null,
                'guest_customer' => $guestCustomerId ?? null,
            ]);

            return redirect($paymentLink);
        } catch (\Exception $e) {
            Log::error('Upayments payment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Payment initialization failed');
        }
    }

    /**
     * Browser redirect after payment (returnUrl / cancelUrl).
     */
    public function callback(Request $request)
    {
        Log::info('Upayments callback received', [
            'payload' => $request->all(),
        ]);

        $requestedOrderId = trim((string) $request->query('requested_order_id', ''));

        if ($requestedOrderId === '') {
            Log::error('Upayments callback: missing requested_order_id');
            return (new PaymentController)->payment_failed();
        }

        try {
            $transaction = $this->findTransactionByRequestedOrderId($requestedOrderId);

            if (!$transaction) {
                Log::error('Upayments callback: transaction not found', [
                    'requested_order_id' => $requestedOrderId,
                ]);
                return (new PaymentController)->payment_failed();
            }

            $callbackPaymentInfo = json_decode($transaction->payment_info, true) ?? [];
            $this->setcredentials($callbackPaymentInfo['payment_method_id'] ?? null);

            $orderId = $this->extractOrderIdFromTransaction($transaction);

            if (!$orderId) {
                Log::error('Upayments callback: could not resolve internal order id', [
                    'requested_order_id' => $requestedOrderId,
                ]);
                return (new PaymentController)->payment_failed();
            }

            $paymentInfo = json_decode($transaction->payment_info, true) ?? [];
            $this->mergeCallbackParamsIntoPaymentInfo($paymentInfo, $request->query());

            $verified = $this->resolveVerifiedPayment($paymentInfo, $transaction);

            if (!$verified || strtoupper(trim($verified['result'] ?? '')) !== 'CAPTURED') {
                $this->updatePaymentTransactionRecord($transaction, $paymentInfo, $verified['result'] ?? ($paymentInfo['result'] ?? 'FAILED'));

                Log::error('Upayments callback: payment not captured', [
                    'requested_order_id' => $requestedOrderId,
                    'result' => $verified['result'] ?? ($paymentInfo['result'] ?? null),
                ]);

                return (new PaymentController)->payment_failed();
            }

            $paymentInfo['callback_details'] = $verified;
            $this->updatePaymentTransactionRecord($transaction, $paymentInfo, 'CAPTURED', 2);
            $this->fulfillOrderIfCaptured($orderId);

            return redirect('/order-success/' . $orderId);
        } catch (\Exception $ex) {
            Log::error('Upayments callback exception', [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);

            return (new PaymentController)->payment_failed();
        }
    }

    /**
     * Webhook notification from uPayments (source of truth).
     */
    public function webhook(Request $request)
    {
        Log::info('Upayments webhook received', [
            'payload' => $request->all(),
        ]);

        if (!$request->isMethod('post')) {
            return response('Method Not Allowed', 405);
        }

        $requestedOrderId = trim((string) $request->input('requested_order_id', ''));
        $result = strtoupper(trim((string) $request->input('result', '')));

        if ($requestedOrderId === '' || !in_array($result, self::ALLOWED_RESULTS, true)) {
            Log::warning('Upayments webhook: invalid payload', [
                'requested_order_id' => $requestedOrderId,
                'result' => $result,
            ]);
            return response('Bad Request', 400);
        }

        try {
            $transaction = $this->findTransactionByRequestedOrderId($requestedOrderId);

            if (!$transaction) {
                Log::warning('Upayments webhook: order not found', [
                    'requested_order_id' => $requestedOrderId,
                ]);
                return response('OK', 200);
            }

            $webhookPaymentInfo = json_decode($transaction->payment_info, true) ?? [];
            $this->setcredentials($webhookPaymentInfo['payment_method_id'] ?? null);

            $paymentInfo = $webhookPaymentInfo;
            $this->mergeWebhookParamsIntoPaymentInfo($paymentInfo, $request->all());

            $status = $this->mapResultToStatusInt($result);
            $transaction->update([
                'status' => $status,
                'payment_info' => json_encode($paymentInfo),
            ]);

            if ($result === 'CAPTURED') {
                $orderId = $this->extractOrderIdFromTransaction($transaction);
                if ($orderId) {
                    $this->fulfillOrderIfCaptured($orderId);
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Upayments webhook exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('OK', 200);
        }
    }

    /**
     * POST /api/v1/charge
     */
    private function initiateCharge(array $payload): array
    {
        $endpoint = $this->apiBaseUrl() . '/charge';

        Log::info('Upayments charge API call', [
            'endpoint' => $endpoint,
            'sandbox' => $this->sandbox,
            'integration_mode' => $this->integrationMode,
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout(30)->post($endpoint, $payload);

        if ($response->failed()) {
            Log::error('Upayments HTTP Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'endpoint' => $endpoint,
                'integration_mode' => $this->integrationMode,
            ]);

            $message = $response->json('message') ?? ('HTTP ' . $response->status());
            throw new \Exception('Upayments rejected the payment request: ' . $message);
        }

        $responseData = $response->json();

        if (!isset($responseData['status']) || $responseData['status'] !== true) {
            Log::warning('Upayments Logical Failure', ['response' => $responseData]);
            throw new \Exception($responseData['message'] ?? 'Upayments payment initialization failed');
        }

        return $responseData;
    }

    /**
     * GET /api/v1/get-payment-status/{track_id}
     */
    private function verifyUpaymentsPayment(string $trackId): ?array
    {
        try {
            $verifyUrl = $this->apiBaseUrl() . '/get-payment-status/' . urlencode($trackId);

            Log::info('Verifying Upayments payment', [
                'url' => $verifyUrl,
                'track_id' => $trackId,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($verifyUrl);

            if ($response->failed()) {
                Log::error('Upayments verification API returned error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            Log::info('Upayments verification response', [
                'response' => $data,
            ]);

            if (!isset($data['status']) || $data['status'] !== true) {
                return null;
            }

            $details = $data['data'] ?? [];

            if (isset($details['result'])) {
                return $details;
            }

            if (isset($details['payment']['result'])) {
                return $details['payment'];
            }

            if (isset($details['transaction']) && is_array($details['transaction'])) {
                return $details['transaction'];
            }

            return is_array($details) ? $details : null;
        } catch (\Exception $e) {
            Log::error('Upayments verification API exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function resolveVerifiedPayment(array $paymentInfo, PaymentTransaction $transaction): ?array
    {
        $existingResult = strtoupper(trim((string) ($paymentInfo['result'] ?? '')));

        if ($existingResult === 'CAPTURED' && (int) $transaction->status === 2) {
            return ['result' => 'CAPTURED'] + $paymentInfo;
        }

        $trackId = $paymentInfo['track_id'] ?? null;

        if (!$trackId) {
            return null;
        }

        $verified = $this->verifyUpaymentsPayment($trackId);

        if (!$verified) {
            return null;
        }

        return $verified;
    }

    private function fulfillOrderIfCaptured(int $orderId): void
    {
        $order = Orders::find($orderId);

        if (!$order) {
            Log::warning('Upayments fulfill: order not found', ['order_id' => $orderId]);
            return;
        }

        if ((int) $order->payment_status === (int) config('tlecommercecore.order_payment_status.paid')) {
            Log::info('Upayments fulfill: order already paid', ['order_id' => $orderId]);
            return;
        }

        Orders::where('id', $orderId)->update([
            'payment_status' => config('tlecommercecore.order_payment_status.paid'),
        ]);

        OrderHasProducts::where('order_id', $orderId)->update([
            'payment_status' => config('tlecommercecore.order_payment_status.paid'),
        ]);

        $message = 'Order payment completed by Upayments';
        EcommerceNotification::sendCustomerOrderPaymentCompletedNotification($orderId, $message);
        EcommerceNotification::sendNewOrderNotification($order);

        Log::info('Upayments order fulfilled', ['order_id' => $orderId]);
    }

    private function storePaymentTransaction(array $data): void
    {
        $paymentMethodSlug = Str::slug(session('payment_method', 'upayments'));

        PaymentTransaction::create([
            'payment_method' => $paymentMethodSlug,
            'paid_amount' => $data['amount'],
            'payment_for' => 'Order ID: ' . $data['order_id'],
            'status' => 1,
            'payment_info' => json_encode([
                'requested_order_id' => $data['requested_order_id'],
                'reference' => $data['reference'],
                'track_id' => $data['track_id'],
                'payment_link' => $data['payment_link'],
                'gateway_src' => $this->gatewaySrc,
                'order_id' => $data['order_id'],
                'payment_method_id' => $this->paymentMethodId,
            ]),
            'customer_id' => $data['customer_id'] ?? null,
            'guest_customer' => $data['guest_customer'] ?? null,
            'user_id' => null,
        ]);
    }

    private function findTransactionByRequestedOrderId(string $requestedOrderId): ?PaymentTransaction
    {
        return PaymentTransaction::whereJsonContains('payment_info->requested_order_id', $requestedOrderId)
            ->orderByDesc('id')
            ->first();
    }

    private function extractOrderIdFromTransaction(PaymentTransaction $transaction): ?int
    {
        $paymentInfo = json_decode($transaction->payment_info, true) ?? [];

        if (!empty($paymentInfo['order_id'])) {
            return (int) $paymentInfo['order_id'];
        }

        if (preg_match('/Order ID:\s*(\d+)/', $transaction->payment_for, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function updatePaymentTransactionRecord(PaymentTransaction $transaction, array $paymentInfo, string $result, ?int $status = null): void
    {
        $paymentInfo['result'] = strtoupper(trim($result));

        $transaction->update([
            'status' => $status ?? $this->mapResultToStatusInt($paymentInfo['result']),
            'payment_info' => json_encode($paymentInfo),
        ]);
    }

    private function mapResultToStatusInt(string $result): int
    {
        $result = strtoupper(trim($result));

        if ($result === 'CAPTURED') {
            return 2;
        }

        if (in_array($result, ['FAILED', 'CANCELLED', 'NOT CAPTURED'], true)) {
            return 3;
        }

        return 1;
    }

    private function mergeCallbackParamsIntoPaymentInfo(array &$paymentInfo, array $query): void
    {
        $fields = [
            'payment_id', 'result', 'post_date', 'tran_id', 'ref', 'track_id', 'auth',
            'order_id', 'refund_order_id', 'payment_type', 'invoice_id', 'transaction_date', 'receipt_id',
        ];

        foreach ($fields as $field) {
            if (isset($query[$field]) && $query[$field] !== '') {
                $paymentInfo[$field === 'order_id' ? 'response_order_id' : $field] = $query[$field];
            }
        }
    }

    private function mergeWebhookParamsIntoPaymentInfo(array &$paymentInfo, array $payload): void
    {
        $map = [
            'payment_id' => 'payment_id',
            'result' => 'result',
            'post_date' => 'post_date',
            'tran_id' => 'tran_id',
            'ref' => 'ref',
            'track_id' => 'track_id',
            'auth' => 'auth',
            'order_id' => 'response_order_id',
            'refund_order_id' => 'refund_order_id',
            'payment_type' => 'payment_type',
            'invoice_id' => 'invoice_id',
            'transaction_date' => 'transaction_date',
            'receipt_id' => 'receipt_id',
        ];

        foreach ($map as $source => $target) {
            if (isset($payload[$source]) && $payload[$source] !== '') {
                $paymentInfo[$target] = $payload[$source];
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildChargePayload(
        Orders $order,
        string $requestedOrderId,
        string $reference,
        string $referenceId,
        float $payableAmount,
        $customerId,
        $guestCustomerId
    ): array {
        $payload = [
            'products' => $this->buildProductsPayload($order, $payableAmount),
            'order' => [
                'id' => $requestedOrderId,
                'reference' => $reference,
                'description' => 'Order payment' . ($order->order_code ? ' #' . $order->order_code : ''),
                'currency' => $this->currency,
                'amount' => $payableAmount,
            ],
            'language' => 'en',
            'reference' => [
                'id' => $referenceId,
            ],
            'customer' => $this->buildCustomerPayload($order, $customerId, $guestCustomerId),
            'returnUrl' => $this->buildCallbackUrl('upayments.callback'),
            'cancelUrl' => $this->buildCallbackUrl('upayments.callback'),
            'notificationUrl' => $this->buildCallbackUrl('upayments.webhook'),
        ];

        if ($this->integrationMode === 'non_whitelabel') {
            $payload['tokens'] = (object) [];
        }

        if ($this->integrationMode === 'whitelabel') {
            $payload['paymentGateway'] = [
                'src' => $this->gatewaySrc,
            ];
        }

        return $payload;
    }

    private function buildCallbackUrl(string $routeName): string
    {
        $url = route($routeName);

        if (!isCentralDomain()) {
            $path = parse_url($url, PHP_URL_PATH) ?: '/';
            if (!str_starts_with($path, '/payment/')) {
                $root = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
                $url = rtrim($root, '/') . '/payment' . $path;
            }
        }

        if ($this->callbackBaseUrl !== '') {
            $path = parse_url($url, PHP_URL_PATH) ?: '/';
            $url = rtrim($this->callbackBaseUrl, '/') . $path;
        }

        if (str_starts_with($url, 'http://')) {
            $url = 'https://' . substr($url, 7);
        }

        return $url;
    }

    private function isValidUpaymentsMobile(string $mobile): bool
    {
        return (bool) preg_match('/^\+\d{8,15}$/', $mobile);
    }

    private function normalizeApiToken(?string $token): ?string
    {
        if ($token === null) {
            return null;
        }

        $token = trim($token);

        if (stripos($token, 'Bearer ') === 0) {
            $token = trim(substr($token, 7));
        }

        return $token !== '' ? $token : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildProductsPayload(Orders $order, float $payableAmount): array
    {
        return [
            [
                'name' => 'Order Payment',
                'description' => $order->order_code ? 'Order #' . $order->order_code : 'Order #' . $order->id,
                'price' => $payableAmount,
                'quantity' => 1,
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private function buildCustomerPayload(Orders $order, $customerId = null, $guestCustomerId = null): array
    {
        $name = null;
        $email = null;
        $phone = null;

        if ($order->billing_details != null) {
            $name = $order->billing_details->name ?? null;
            $phone = $order->billing_details->phone ?? null;
        }

        if (!$name && $order->shipping_details != null) {
            $name = $order->shipping_details->name ?? null;
            if (!$phone) {
                $phone = $order->shipping_details->phone ?? null;
            }
        }

        if ($order->customer_info != null) {
            $email = $order->customer_info->email ?? null;
            if (!$name) {
                $name = $order->customer_info->name ?? null;
            }
            if (!$phone) {
                $phone = $order->customer_info->phone ?? null;
            }
        } elseif ($order->guest_customer != null) {
            $email = $order->guest_customer->email ?? null;
            if (!$name) {
                $name = $order->guest_customer->name ?? null;
            }
        }

        $uniqueId = (string) ($customerId ?? $guestCustomerId ?? $order->id);

        $payload = [
            'uniqueId' => $uniqueId,
            'name' => !empty($name) ? $name : 'Guest Customer',
            'email' => !empty($email) ? $email : ('guest-' . $order->id . '@checkout.local'),
        ];

        if (!empty($phone)) {
            $mobile = $this->normalizeMobileNumber($phone);
            if ($this->isValidUpaymentsMobile($mobile)) {
                $payload['mobile'] = $mobile;
            }
        }

        return $payload;
    }

    private function normalizeMobileNumber(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]+/', '', $phone) ?? $phone;

        if ($phone !== '' && $phone[0] !== '+') {
            $phone = '+' . ltrim($phone, '0');
        }

        return $phone;
    }

    private function apiBaseUrl(): string
    {
        if ($this->sandbox) {
            return 'https://sandboxapi.upayments.com/api/v1';
        }

        return 'https://uapi.upayments.com/api/v1';
    }
}
