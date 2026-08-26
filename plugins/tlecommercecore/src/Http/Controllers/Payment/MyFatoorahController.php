<?php

namespace Plugin\TlcommerceCore\Http\Controllers\Payment;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Plugin\TlcommerceCore\Models\PaymentTransaction;
use Plugin\TlcommerceCore\Models\PaymentMethods;
use Illuminate\Support\Facades\Http;
use Plugin\TlcommerceCore\Models\Orders;
use Plugin\TlcommerceCore\Models\OrderHasProducts;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use MyFatoorah\Library\API\MyFatoorahSupplier;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use MyFatoorah\Library\MyFatoorah;
use Plugin\TlcommerceCore\Http\Controllers\Payment\PaymentController;


class MyFatoorahController extends Controller {

    /**
     * MyFatoorah Config Array
     * 
     * @var array
     */
    public $mfConfig = [];

    /**
     * Store Config Array
     * 
     * @var array
     */
    private $params = [];

    /**
     * Payment method ID from DB
     * 
     * @var int
     */
    private $payment_method_id;

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Initiate MyFatoorah Configuration
     */
    public function __construct() {
        // $this->params = config('myfatoorah');

        // $this->mfConfig = [
        //     'apiKey'    => $this->params['api_key'],
        //     'isTest'    => $this->params['is_test'],
        //     'vcCode'    => $this->params['vc_code'],
        //     'loggerObj' => storage_path('logs/myfatoorah.log')
        // ];

        // $this->setCredentials();
    }

    private function setCredentials(): void
    {
        Log::info('setCredentials method called');

        // $method = DB::table('tl_com_payment_methods')
        //     ->where('name', 'myfatoorah')
        //     ->first();

        // $methods = PaymentMethods::get();

        // Log::info('All Methods data', [
        //         'methods' => json_encode($methods),
        // ]);

        // Log::info('Connection Details:', [
        //     'database' => DB::connection()->getDatabaseName(),
        //     'host'     => config('database.connections.' . DB::getDefaultConnection() . '.host'),
        //     'driver'   => DB::getDefaultConnection()
        // ]);

        $method = PaymentMethods::where('name', 'LIKE', 'myfatoorah')->first();

        if (!$method) {
            Log::error('MyFatoorah method not found in tl_com_payment_methods table');
            return;
        }

        $this->payment_method_id = $method->id;

        $settings = DB::table('tl_com_payment_method_has_settings')
            ->where('payment_method_id', $this->payment_method_id)
            ->pluck('key_value', 'key_name');

        Log::info('MyFatoorah payment settings', [
            'settings' => json_encode($settings),
        ]);

        // $apiKey = $settings['myfatoorah_secret_key'] ?? null;
        $apiKey = trim($settings['myfatoorah_secret_key'] ?? null);
        $isTest = $settings['myfatoorah_is_test'] ?? true;
        $vcCode = $settings['myfatoorah_vc_code'] ?? 'KWT';

        if (!$apiKey) {
            Log::error('MyFatoorah API key missing', [
                'method_id'     => $this->payment_method_id,
                'available_keys' => $settings->keys(),
            ]);
            throw new \Exception('MyFatoorah API key is missing from settings. Please check tl_com_payment_method_has_settings.');
        }

        $tenantLogPath = storage_path('logs'); // This points to the tenant's specific storage log folder

        // Check if the tenant's log directory exists, if not, create it
        if (!file_exists($tenantLogPath)) {
            mkdir($tenantLogPath, 0777, true);
        }

        $logFile = $tenantLogPath . '/myfatoorah.log';

        $isTest = filter_var($settings['myfatoorah_is_test'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $this->mfConfig = [
            'apiKey'    => $apiKey,
            'isTest'    => false,
            'vcCode'    => 'KWT',
            'loggerObj' => $logFile,
        ];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to redirect the system to MyFatoorah invoice URL
     * Provide the index method with the order id and (payment method id or session id)
     *
     * @return RedirectResponse|JsonResponse
     */
    public function index() {
        try {
            //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
            $paymentId = request('pmid') ?: 0;
            $sessionId = request('sid') ?: null;

            $orderId  = request('oid') ?: 147;
            $curlData = $this->getPayLoadData($orderId);

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

            return redirect($payment['invoiceURL']);
        } catch (Exception $ex) {
            $exMessage = $this->mfTransMsg($ex->getMessage());
            return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to map order data to MyFatoorah
     * You can get the data using the order object in your system
     * 
     * @param string $orderId
     * 
     * @return array
     */
    private function getPayLoadData($orderId) {
        $callbackURL = route('myfatoorah.process');

        //You can get the data using the order object in your system
        $order  = $this->mfGetTestOrderData($orderId);
        $amount = $order['total'];
        return [
            'CustomerName'       => 'FName LName',
            'InvoiceValue'       => $amount,
            'DisplayCurrencyIso' => $order['currency'],
            'CustomerEmail'      => 'test@test.com',
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => '+965',
            'CustomerMobile'     => '12345678',
            'Language'           => app()->getLocale(),
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION,
            'Suppliers'          => $this->getSupplierInfo($amount),
        ];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on a loading page that used to wait MyFatoorah response to be received.
     * 
     * @return View
     */
    public function process() {
        $paymentId = request('paymentId');
        if (!$paymentId) {
            return abort(404);
        }

        $callbackURL = route('myfatoorah.callback');
        return view('myfatoorah.process', compact('paymentId', 'callbackURL'));
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to get MyFatoorah Payment Information
     * Provide the callback method with the paymentId
     * 
     * @return JsonResponse
     */
    public function callback(Request $request)
    {
        Log::info('CALLBACK HIT - VERY FIRST LINE');

        Log::info('MyFatoorah callback received', [
            'request_data' => request()->all(),
        ]);

        try {

            $this->setCredentials();
            
            $paymentId = request('paymentId');

            if (!$paymentId) {
                Log::error('MyFatoorah callback: Missing paymentId');
                return (new PaymentController)->payment_failed();
            }

            // Verify payment status with MyFatoorah
            $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
            $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

            Log::info('MyFatoorah payment status response', [
                'invoice_status' => $data->InvoiceStatus,
                'invoice_id'     => $data->InvoiceId,
                'invoice_error'  => $data->InvoiceError ?? null,
            ]);

            // Check payment status
            if ($data->InvoiceStatus !== 'Paid') {
                Log::error('MyFatoorah payment not successful', [
                    'payment_id'     => $paymentId,
                    'invoice_status' => $data->InvoiceStatus,
                    'invoice_error'  => $data->InvoiceError ?? null,
                ]);
                return (new PaymentController)->payment_failed();
            }

            // Look up our transaction record using invoice_id stored in payment_info
            $paymentTransaction = PaymentTransaction::whereJsonContains('payment_info->invoice_id', $data->InvoiceId)
                ->where('status', 1)
                ->first();

            if (!$paymentTransaction) {
                Log::error('MyFatoorah callback: Transaction not found', [
                    'invoice_id' => $data->InvoiceId,
                ]);
                return (new PaymentController)->payment_failed();
            }

            // Extract order ID from payment_for string
            $orderId = null;
            if (preg_match('/Order ID:\s*(\d+)/', $paymentTransaction->payment_for, $matches)) {
                $orderId = (int) $matches[1];
            }

            if (!$orderId) {
                Log::error('MyFatoorah callback: Could not extract order ID', [
                    'payment_for' => $paymentTransaction->payment_for,
                ]);
                return (new PaymentController)->payment_failed();
            }

            // Update payment transaction status
            $paymentTransaction->update([
                'status'       => 2, // 2 = success, confirm this matches your convention
                'payment_info' => json_encode(array_merge(
                    json_decode($paymentTransaction->payment_info, true),
                    [
                        'invoice_status'     => $data->InvoiceStatus,
                        'payment_id'         => $paymentId,
                        'transaction_status' => 'SUCCESS',
                    ]
                )),
            ]);

            // Update order payment status
            $this->updateOrderTransaction($orderId);

            Log::info('MyFatoorah payment successful', [
                'payment_id'  => $paymentId,
                'invoice_id'  => $data->InvoiceId,
                'order_id'    => $orderId,
            ]);

            return redirect('/order-success/' . $orderId);

        } catch (Exception $ex) {
            Log::error('MyFatoorah callback exception', [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);
            return (new PaymentController)->payment_failed();
        }
    }
    // public function callback() {
    //     $paymentId = request('paymentId');
    //     if (!$paymentId) {
    //         return abort(404);
    //     }

    //     try {
    //         $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
    //         $data  = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

    //         $message = $this->mfGetTestMessage($data->InvoiceStatus, $data->InvoiceError);

    //         return response()->json(['IsSuccess' => true, 'Message' => $message, 'Data' => $data]);
    //     } catch (Exception $ex) {
    //         $exMessage = $this->mfTransMsg($ex->getMessage());
    //         return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
    //     }
    // }

//-----------------------------------------------------------------------------------------------------------------------------------------

    public function pay(Request $request)
    {
        $this->setCredentials();

        Log::info('MyFatoorah pay method called');

        $orderId         = session('order_id');
        $payableAmount   = session('payable_amount');
        $customerId      = session('customer');
        $guestCustomerId = session('guest_customer');

        Log::info('MyFatoorah payment initialization', [
            'order_id'          => $orderId,
            'amount'            => $payableAmount,
            'customer_id'       => $customerId,
            'guest_customer_id' => $guestCustomerId,
            'callback' => url('/') . '/' . 'payment/myfatoorah/callback'
        ]);

        if (!$orderId || !$payableAmount) {
            Log::error('MyFatoorah payment missing required session data');
            return redirect('/checkout')->with('error', 'Payment session expired');
        }

        try {
            // Load order with whichever relationship is populated
            $order = Orders::with(['customer_info', 'guest_customer'])->findOrFail($orderId);

            if ($customerId && $order->customer_info) {
                $customer = $order->customer_info;
                $customerName   = $customer->name;
                $customerEmail  = $customer->email;
                $customerMobile = $customer->phone ?? '00000000';
            } elseif ($guestCustomerId && $order->guest_customer) {
                $guest = $order->guest_customer;
                $customerName   = $guest->name;
                $customerEmail  = $guest->email;
                $customerMobile = $guest->phone ?? '00000000';
            } else {
                // Fallback — should never hit this in practice
                $customerName   = 'Customer';
                $customerEmail  = 'noreply@example.com';
                $customerMobile = '00000000';
            }

            // $callbackURL = route('myfatoorah.callback');
            $callbackURL = url('/') . '/' . 'payment/myfatoorah/callback';

            $curlData = [
                'CustomerName'       => $customerName,
                'InvoiceValue'       => $payableAmount,
                'DisplayCurrencyIso' => 'KWD',
                'CustomerEmail'      => $customerEmail,
                'CallBackUrl'        => $callbackURL,
                'ErrorUrl'           => $callbackURL,
                'MobileCountryCode'  => '+965',
                'CustomerMobile'     => $customerMobile,
                'Language'           => app()->getLocale(),
                'CustomerReference'  => $orderId,
                'SourceInfo'         => 'Laravel ' . app()::VERSION . ' - MyFatoorah Package ' . MYFATOORAH_LARAVEL_PACKAGE_VERSION,
            ];

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, 0);

            PaymentTransaction::create([
                'payment_method' => 'myfatoorah',
                'paid_amount'    => $payableAmount,
                'payment_for'    => 'Order ID: ' . $orderId,
                'status'         => 1,
                'payment_info'   => json_encode([
                    'invoice_id'  => $payment['invoiceId'],
                    'payment_url' => $payment['invoiceURL'],
                ]),
                'customer_id'    => $customerId      ?? null,
                'guest_customer' => $guestCustomerId ?? null,
                'user_id'        => null,
            ]);

            return redirect($payment['invoiceURL']);

        } catch (Exception $e) {
            Log::error('MyFatoorah payment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Payment initialization failed');
        }
    }
    /**
     * Example on how to display the enabled gateways at your MyFatoorah account to be displayed on the checkout page
     * Provide the checkout method with the order id to display its total amount and currency
     * 
     * @return View
     * 
     * @throws Exception
     */
    // public function checkout() {
    //     try {
    //         //You can get the data using the order object in your system
    //         $orderId = request('oid') ?: 147;
    //         $order   = $this->mfGetTestOrderData($orderId);

    //         //You can replace this variable with customer Id in your system
    //         $customerId = request('customerId');

    //         //You can use the user defined field if you want to save card
    //         $userDefinedField = $this->params['save_card'] && $customerId ? "CK-$customerId" : '';

    //         //Get the enabled gateways at your MyFatoorah account to be displayed on the checkout page
    //         $mfObj          = new MyFatoorahPaymentEmbedded($this->mfConfig);
    //         $paymentMethods = $mfObj->getCheckoutGateways($order['total'], $order['currency'], $this->params['register_apple_pay']);

    //         if (empty($paymentMethods['all'])) {
    //             throw new Exception('noPaymentGateways');
    //         }

    //         //Generate MyFatoorah session for embedded payment
    //         $mfSession = $mfObj->getEmbeddedSession($userDefinedField);

    //         //Get Environment url
    //         $isTest = $this->mfConfig['isTest'];
    //         $vcCode = $this->mfConfig['vcCode'];

    //         $countries = MyFatoorah::getMFCountries();
    //         $jsDomain  = ($isTest) ? $countries[$vcCode]['testPortal'] : $countries[$vcCode]['portal'];

    //         return view('myfatoorah.checkout', compact('mfSession', 'paymentMethods', 'jsDomain', 'userDefinedField'));
    //     } catch (Exception $ex) {
    //         $exMessage = $this->mfTransMsg($ex->getMessage());
    //         return view('myfatoorah.error', ['message' => $exMessage]);
    //     }
    // }


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
            'payment_status' => 1, 
        ]);

    OrderHasProducts::where('order_id', $orderId)
        ->update([
            'payment_status' => 1, 
        ]);

    \Log::info('Order payment status updated successfully', [
        'order_id' => $orderId,
    ]);
}

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how the webhook is working when MyFatoorah try to notify your system about any transaction status update
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function webhook(Request $request) {
        //Validate webhook_secret_key
        $secretKey = $this->params['webhook_secret_key'];
        if (empty($secretKey)) {
            return response()->json(null, 404);
        }

        //Validate MyFatoorah-Signature
        $mfSignature = $request->header('MyFatoorah-Signature');
        if (empty($mfSignature)) {
            return response()->json(null, 404);
        }

        //Validate input
        $body  = $request->getContent();
        $input = json_decode($body, true);
        if (empty($input['Data']) || empty($input['EventType']) || $input['EventType'] != 1) {
            return response()->json(null, 404);
        }

        //Validate Signature
        if (!MyFatoorah::isSignatureValid($input['Data'], $secretKey, $mfSignature, $input['EventType'])) {
            return response()->json(null, 404);
        }

        //Update Transaction status on your system
        return $this->changeTransactionStatus($input['Data']);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to update your system with the order status that comes from a webhook
     * 
     * @param array $inputData
     * 
     * @return JsonResponse
     */
    private function changeTransactionStatus($inputData) {
        try {
            //1. Check if orderId is valid on your system.
            $orderId = $inputData['CustomerReference'];

            //2. Get MyFatoorah invoice id
            $invoiceId = $inputData['InvoiceId'];

            //3. Check order status at MyFatoorah side
            $message = 'Invoice is paid.';
            if ($inputData['TransactionStatus'] != 'SUCCESS') {
                //get the error if you want using the getPaymentStatus API endpoint
                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
                $data  = $mfObj->getPaymentStatus($invoiceId, 'InvoiceId');

                $message = $this->mfGetTestMessage($data->InvoiceStatus, $data->InvoiceError);
            }

            //4. Update order transaction status on your system
            return response()->json(['IsSuccess' => true, 'Message' => $message, 'Data' => $inputData]);
        } catch (Exception $ex) {
            $exMessage = $this->mfTransMsg($ex->getMessage());
            return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to get the supplier array to pass it to the payload of the invoice creation
     * 
     * @param number $amount
     * 
     * @return array|null
     * 
     * @throws Exception
     */
    private function getSupplierInfo($amount) {
        $supplierCode = $this->params['supplier_code'];
        if ($supplierCode == null) {
            return null;
        }

        if (!is_integer($supplierCode) || $supplierCode <= 0) {
            throw new Exception("Invalid Supplier code $supplierCode.");
        }

        $myfatoorahSupplier = new MyFatoorahSupplier($this->mfConfig);
        if (!$myfatoorahSupplier->isSupplierApproved($supplierCode)) {
            throw new Exception("Supplier code $supplierCode is not active in vendor account, please contact MyFatoorah team to activate it.");
        }

        return [[
        'SupplierCode'  => $supplierCode,
        'ProposedShare' => null,
        'InvoiceShare'  => $amount
        ]];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
    private function mfTransMsg($msg) {
        return __('myfatoorah.' . $msg);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
    private function mfGetTestOrderData($orderId) {
        return [
            'orderId'  => $orderId,
            'total'    => 1234.56,
            'currency' => 'KWD'
        ];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
    private function mfGetTestMessage($status, $error) {
        if ($status == 'Paid' || $status == 'SUCCESS') {
            return 'Invoice is paid.';
        } else if ($status == 'Failed') {
            return 'Invoice is not paid due to ' . $error;
        } else if ($status == 'Expired') {
            return $error;
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
