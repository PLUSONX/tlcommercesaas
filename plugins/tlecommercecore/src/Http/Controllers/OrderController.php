<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use Illuminate\Support\Facades\Log;
use BPDF;
use NPDF;
use Milon\Barcode\DNS1D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Plugin\TlcommerceCore\Repositories\OrderRepository;
use Plugin\TlcommerceCore\Http\Requests\DeliveryStatusUpdateRequest;
use Plugin\TlcommerceCore\Models\Currency;
use Plugin\TlcommerceCore\Models\PaymentTransaction;
use Plugin\TlcommerceCore\Repositories\SettingsRepository;

class OrderController extends Controller
{

    protected $order_repository;

    public function __construct(OrderRepository $order_repository)
    {
        $this->order_repository = $order_repository;
    }

    /**
     * Will return inhouse orders
     *
     * @return mixed
     */
    public function inhouseOrders(Request $request)
    {
        $shipping_type = config('tlecommercecore.order_type.home_delivery');
        $orders = $this->order_repository->orderList($request, $shipping_type, 'inhouse', null);
        $order_counter = $this->order_repository->orderCounter($shipping_type);
        $meta = $this->order_repository->inhouseOrdersMeta($shipping_type);

        // Log::info('inhouse method called', [
        //             'orders' => json_encode($orders),
        // ]);

        if ($request->boolean('partial')) {
            return response()->json([
                'success' => true,
                'latest_id' => $meta['latest_id'],
                'status_version' => $meta['status_version'],
                'total' => $meta['total'],
                'order_counter' => $order_counter,
                'tbody' => view('plugin/tlecommercecore::orders.inhouse_orders._table_rows', [
                    'orders' => $orders,
                ])->render(),
                'pagination' => view('plugin/tlecommercecore::orders.inhouse_orders._pagination', [
                    'orders' => $orders,
                ])->render(),
            ]);
        }

        return view('plugin/tlecommercecore::orders.inhouse_orders.index')->with(
            [
                'orders' => $orders,
                'order_counter' => $order_counter,
                'orders_meta' => $meta,
            ]
        );
    }

    /**
     * Lightweight meta for inhouse orders auto-refresh polling.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function inhouseOrdersLatestMeta()
    {
        $meta = $this->order_repository->inhouseOrdersMeta(config('tlecommercecore.order_type.home_delivery'));

        return response()->json([
            'success' => true,
            'latest_id' => $meta['latest_id'],
            'status_version' => $meta['status_version'],
            'total' => $meta['total'],
        ]);
    }

    /**
     * Will redirect order details page
     *
     * @param Int $id
     * @return mixed
     */
    public function orderDetails($id)
    {
        $order_details = $this->order_repository->orderDetails($id);
        return view('plugin/tlecommercecore::orders.details')->with(
            [
                'order_details' => $order_details
            ]
        );
    }

    /**
     * Will return Order status details
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function orderStatusDetails(Request $request)
    {
        $order_details = $this->order_repository->orderDetails($request['id']);
        return view('plugin/tlecommercecore::orders.status_details')->with(
            [
                'order_details' => $order_details
            ]
        );
    }
    /**
     * Will update delivery status
     *
     * @param DeliveryStatusUpdateRequest $request
     * @return mixed
     */
    public function updateOrderStatus(DeliveryStatusUpdateRequest $request)
    {
        $res = $this->order_repository->updateOrderStatus($request);
        if ($res) {
            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }

    /**
     * Will update payment status of an order (all line items)
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function updateOrderPaymentStatus(Request $request)
    {
        $validStatuses = array_values(config('tlecommercecore.order_payment_status'));

        $request->validate([
            'order_id' => 'required|integer',
            'payment_status' => 'required|in:' . implode(',', $validStatuses),
        ]);

        $res = $this->order_repository->updateOrderPaymentStatus($request['order_id'], $request['payment_status']);

        if ($res) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    /**
     * Will update delivery status of an order (all line items)
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function updateOrderDeliveryStatus(Request $request)
    {
        $validStatuses = array_values(config('tlecommercecore.order_delivery_status'));

        $request->validate([
            'order_id' => 'required|integer',
            'delivery_status' => 'required|in:' . implode(',', $validStatuses),
        ]);

        $res = $this->order_repository->updateOrderDeliveryStatus($request['order_id'], $request['delivery_status']);

        if ($res) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    /**
     * Will cancel an order
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function cancelOrder(Request $request)
    {
        $res = $this->order_repository->cancelOrder($request['order_id']);

        if ($res) {
            toastNotification('success', translate('Order cancelled successfully'));
        } else {
            toastNotification('error', translate('Order cancel failed'));
        }
        return redirect()->back();
    }

    /**
     * Will cancel an item
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function cancelOrderItem(Request $request)
    {
        $res = $this->order_repository->changeOrderItemStatus($request['item_id'], $request['order_id'], config('tlecommercecore.order_delivery_status.cancelled'));
        if ($res) {
            toastNotification('success', translate('Item has been cancelled'));
        } else {
            toastNotification('error', translate('Action failed'));
        }

        return redirect()->back();
    }

    /**
     * Will accept order
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function acceptOrder(Request $request)
    {
        $res = $this->order_repository->acceptOrder($request['order_id']);
        if ($res) {
            toastNotification('success', translate('Order accept successfully'));
        } else {
            toastNotification('error', translate('Order accept failed'));
        }
        return redirect()->back();
    }
    /**
     * Will bulk action of orders
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function orderBulkAction(Request $request)
    {
        $res = $this->order_repository->orderBulkAction($request);

        if ($res) {
            toastNotification('success', translate('Bulk action completed successfully'));
        } else {
            toastNotification('error', translate('Bulk action failed'));
        }
    }
    /**
     * Will print shipping label
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function printShippingLabel(Request $request)
    {
        try {
            if (!$request->has('shipping_label_products')) {
                toastNotification('error', 'No product selected');
                return redirect()->back();
            }

            // \Log::info('=== printShippingLabel START ===', [
            //     'order_id' => $request['order_id'],
            //     'shipping_label_products' => $request['shipping_label_products'],
            //     'action' => $request['action'],
            // ]);

            $shipping_label_content = $this->order_repository->getShippingLabelContent($request['order_id'], $request['shipping_label_products']);
            // \Log::info('Shipping label content retrieved', ['content' => $shipping_label_content]);

            // $qr_data = collect($shipping_label_content)->toJson();

            // Generate QR as SVG - save raw content directly (no base64)
            // $qr_svg_content = QrCode::format('svg')->size(200)->errorCorrection('H')->generate($qr_data);
            // $qr_temp = public_path('temp_qr_' . $shipping_label_content['order_code'] . '.svg');
            // file_put_contents($qr_temp, $qr_svg_content);
            // \Log::info('QR code generated successfully');

            // Log::info('qr data', [
            //     'qr_temp' => $qr_temp,
            // ]);

            // $order_code_bar_code = DNS1D::getBarcodePNG($shipping_label_content['order_code'], 'C39+', 1, 85);
            // $tracking_id_bar_code = DNS1D::getBarcodePNG($shipping_label_content['tracking_id'], 'C39+', 1, 50);
            // \Log::info('Barcodes generated successfully');

            // $order_bar_temp = public_path('temp_bar_order_' . $shipping_label_content['order_code'] . '.png');
            // $tracking_bar_temp = public_path('temp_bar_tracking_' . $shipping_label_content['order_code'] . '.png');

            // Register cleanup
            // register_shutdown_function(function() use ($qr_temp) {
            //     @unlink($qr_temp);
            // });

            // Now generate and save files
            // file_put_contents($order_bar_temp, base64_decode($order_code_bar_code));
            // file_put_contents($tracking_bar_temp, base64_decode($tracking_id_bar_code));

            // file_put_contents($qr_temp, base64_decode($qr_code));

            $font_family = "Roboto";
            $local = getLocale();

            if ($local == 'bd') $font_family = 'Bangla';
            if ($local == 'sa') $font_family = 'Arabic';
            if ($local == 'il') $font_family = 'Hebrew';

            $default_currency_id = SettingsRepository::getEcommerceSetting('default_currency');
            $default_currency = Currency::find($default_currency_id);

            if (!$default_currency) {
                \Log::error('printShippingLabel: Default currency not found', ['currency_id' => $default_currency_id]);
                toastNotification('error', translate('Something went wrong. Please try again'));
                return redirect()->back();
            }

            $currency_font = 'Arial Unicode MS';
            if ($default_currency->symbol == '₹') {
                $currency_font = 'Roboto';
            }

            // Convert logo URL to local path for PDF rendering
            $order_info = $shipping_label_content;

            if (!empty($order_info['system_properties']['logo'])) {
                $logo_url = $order_info['system_properties']['logo'];

                $clean_path = str_replace([url('/'), '/public', 'public/'], '', $logo_url);
                $clean_path = ltrim($clean_path, '/');
                $logo_local_path = public_path($clean_path);

                if (file_exists($logo_local_path)) {
                    // Use the local file path directly instead of base64
                    $order_info['system_properties']['logo'] = $logo_local_path;
                }
            }

            if (!empty($order_info['system_properties']['paid_image'])) {
                $clean_path = str_replace([url('/'), '/public', 'public/'], '', $order_info['system_properties']['paid_image']);
                $clean_path = ltrim($clean_path, '/');
                $local_path = public_path($clean_path);
                if (file_exists($local_path)) {
                    $order_info['system_properties']['paid_image'] = $local_path;
                }
            }

            if (!empty($order_info['system_properties']['unpaid_image'])) {
                $clean_path = str_replace([url('/'), '/public', 'public/'], '', $order_info['system_properties']['unpaid_image']);
                $clean_path = ltrim($clean_path, '/');
                $local_path = public_path($clean_path);
                if (file_exists($local_path)) {
                    $order_info['system_properties']['unpaid_image'] = $local_path;
                }
            }

                    $data = [
                        'title'               => $shipping_label_content['order_code'],
                        'date'                => date('m/d/Y'),
                        'order_info'          => $order_info,
                        // 'qr_code'             => $qr_temp,
                        // 'order_code_bar_code' => $order_bar_temp,
                        // 'tracking_id_bar_code'=> $tracking_bar_temp,
                        'font_family'         => $font_family,
                        'currency_font'       => $currency_font,
                    ];

                    // Log::info('data check', [
                    //     'data' => json_encode($data)
                    // ]);

                    $default_language = getLocale();
                    $is_rtl = DB::table('tl_languages')
                        ->where('code', '=', $default_language)
                        ->where('is_rtl', '=', 1)
                        ->exists();

                    // \Log::info('RTL check', ['locale' => $default_language, 'is_rtl' => $is_rtl]);

                    if ($is_rtl) {
                        // $tenant_id = isTenant();
                        // $qrCodePath = public_path('tenant/tenant' . $tenant_id . '/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');

                        // \Log::info('Writing QR code file', ['path' => $qrCodePath]);
                        // file_put_contents($qrCodePath, base64_decode($qr_code));

                        // $data['qr_code'] = url('tenant/tenant' . $tenant_id . '/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');

                        // \Log::info('Loading RTL PDF view');
                        $pdf = NPDF::loadView('plugin/tlecommercecore::orders.invoice.shipping_label_rtl', $data, [], [
                            'default_font'  => 'dejavusans',
                            'mode'          => 'utf-8',
                            'margin_top'    => 0,
                            'margin_right'  => 0,
                            'margin_bottom' => 0,
                            'margin_left'   => 0,
                            'padding_left'  => 5,
                            'padding_right' => 5,
                        ]);

                        // \Log::info('RTL PDF generated, action: ' . $request['action']);
                        return $request['action'] == 'preview'
                            ? $pdf->stream($shipping_label_content['order_code'] . '.pdf')
                            : $pdf->download($shipping_label_content['order_code'] . '.pdf');
                    }

                    // \Log::info('Loading LTR PDF view');

                    $view = view('plugin/tlecommercecore::orders.invoice.shipping_label', $data);
                    // \Log::info('View file path: ' . $view->getEngine()->getCompiler()->getCompiledPath(
                    //     $view->getPath()
                    // ));
                    // \Log::info('View source path: ' . $view->getPath());

                    // Render the blade view to raw HTML first so we can inspect it
                    $html = view('plugin/tlecommercecore::orders.invoice.shipping_label', $data)->render();

                    $pdf = BPDF::loadHTML($html)
                     ->set_option('isRemoteEnabled', true)
                    ->set_option('isHtml5ParserEnabled', true)
                    ->set_option('isFontSubsettingEnabled', true);

                    // $pdf = BPDF::loadView('plugin/tlecommercecore::orders.invoice.shipping_label', $data)
                    //     ->set_option('isFontSubsettingEnabled', true);

                    // \Log::info('LTR PDF generated, action: ' . $request['action']);

                    // \Log::info('Attempting PDF stream, checking output buffer', [
                    //     'ob_level' => ob_get_level(),
                    //     'headers_sent' => headers_sent(),
                    // ]);

                    // Clean any buffered output before streaming
                    while (ob_get_level() > 0) {
                        ob_end_clean();
                    }
                    
                    return $request['action'] == 'preview'
                        ? $pdf->stream()
                        : $pdf->download($shipping_label_content['order_code'] . '.pdf');

                } catch (\Throwable $e) {
                    \Log::error('=== printShippingLabel FAILED ===', [
                        'message' => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'trace'   => $e->getTraceAsString(),
                    ]);
                    toastNotification('error', translate('Something went wrong. Please try again'));
                    return redirect()->back();
                }
    }
    // public function printShippingLabel(Request $request)
    // {
    //     try {
    //         if (!$request->has('shipping_label_products')) {
    //             toastNotification('error', 'No product selected');
    //             return redirect()->back();
    //         }

    //         $shipping_label_content = $this->order_repository->getShippingLabelContent($request['order_id'], $request['shipping_label_products']);
    //         $qr_data = collect($shipping_label_content)->toJson();
    //         $qr_code = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($qr_data));
    //         $order_code_bar_code = DNS1D::getBarcodePNG($shipping_label_content['order_code'], 'C39+', 1, 85);
    //         $tracking_id_bar_code = DNS1D::getBarcodePNG($shipping_label_content['tracking_id'], 'C39+', 1, 50);

    //         $font_family = "Roboto";
    //         $local = getLocale();

    //         if ($local  == 'bd') {
    //             $font_family = 'Bangla';
    //         }

    //         if ($local  == 'sa') {
    //             $font_family = 'Arabic';
    //         }

    //         if ($local  == 'il') {
    //             $font_family = 'Hebrew';
    //         }

    //         $default_currency_id = SettingsRepository::getEcommerceSetting('default_currency');
    //         $default_currency = Currency::find($default_currency_id);

    //         $currency_font = 'Arial Unicode MS';
    //         if ($default_currency->symbol == '₹') {
    //             $currency_font = 'Roboto';
    //         }

    //         $data = [
    //             'title' => $shipping_label_content['order_code'],
    //             'date' => date('m/d/Y'),
    //             'order_info' => $shipping_label_content,
    //             'qr_code' => $qr_code,
    //             'order_code_bar_code' => $order_code_bar_code,
    //             'tracking_id_bar_code' => $tracking_id_bar_code,
    //             'font_family' => $font_family,
    //             'currency_font' => $currency_font
    //         ];

    //         $default_language = getLocale();
    //         $is_rtl = DB::table('tl_languages')
    //             ->where('code', '=', $default_language)
    //             ->where('is_rtl', '=', 1)
    //             ->exists();

    //         if ($is_rtl) {
    //             $tenant_id = isTenant();
    //             $qrCodePath = public_path('tenant/tenant' . $tenant_id . '/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');
    //             file_put_contents($qrCodePath, base64_decode($qr_code));

    //             $data['qr_code'] = url('tenant/tenant' . $tenant_id . '/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');
    //             // $data['qr_code'] = url('public/tenant/tenant' . $tenant_id . '/shipping_' . $shipping_label_content['order_code'] . 'qr_code.png');

    //             $pdf = NPDF::loadView('plugin/tlecommercecore::orders.invoice.shipping_label_rtl', $data, [], [
    //                 'default_font' => 'dejavusans',
    //                 'mode' => 'utf-8',
    //                 'margin_top' => 10,
    //                 'margin_right' => 10,
    //                 'margin_bottom' => 10,
    //                 'margin_left' => 10,
    //                 'padding_left' => 5,
    //                 'padding_right' => 5,
    //             ]);

    //             if ($request['action'] == 'preview') {
    //                 return $pdf->stream($shipping_label_content['order_code'] . '.pdf');
    //             } else {
    //                 return $pdf->download($shipping_label_content['order_code'] . '.pdf');
    //             }
    //         }


    //         $shipping_view = 'plugin/tlecommercecore::orders.invoice.shipping_label';
    //         $pdf = BPDF::loadView($shipping_view, $data)->set_option('isFontSubsettingEnabled', true);


    //         if ($request['action'] == 'preview') {
    //             return $pdf->stream();
    //         } else {
    //             return $pdf->download($shipping_label_content['order_code'] . '.pdf');
    //         }
    //     } catch (\Exception $e) {
    //         toastNotification('error', translate('Something went wrong. Please try again'));
    //         return redirect()->back();
    //     }
    // }

    /**
     * Will print order invoice
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     **/
    public function printInvoice(Request $request)
    {
        try {
            if (!$request->has('invoice_products')) {
                toastNotification('error', 'No product selected');
                return redirect()->back();
            }

            $invoice_data = $this->order_repository->getInvoiceData($request['order_id'], $request['invoice_products']);

            Log::info('before order_url!');

            $order_id = $invoice_data['products']->first()->order_id;

            try {
                $order_url = route('plugin.tlcommercecore.orders.details', ['id' => $order_id]);
                Log::info('URL Generated: ' . $order_url);
            } catch (\Exception $e) {
                Log::error('Route Generation Failed: ' . $e->getMessage());
            }

            // $order_url = route('plugin.tlcommercecore.orders.details', ['id' => $invoice_data['order_id']]);

            Log::info('after order_url!');

            // Generate QR as SVG and save to temp file
            // $qr_svg_content = QrCode::format('svg')->size(200)->errorCorrection('H')->generate($invoice_data['order_code']);
            $qr_svg_content = QrCode::format('svg')
                                ->size(200)
                                ->errorCorrection('H')
                                ->generate($order_url);

            $qr_temp = public_path('temp_qr_invoice_' . $invoice_data['order_code'] . '.svg');
            file_put_contents($qr_temp, $qr_svg_content);

            Log::info('qr data', [
                'url_encoded' => $order_url,
                'file_path' => $qr_temp,
            ]);

            // Register cleanup
            register_shutdown_function(function() use ($qr_temp) {
                @unlink($qr_temp);
            });

            $font_family = "Roboto";
            $local = getLocale();

            if ($local == 'bd') $font_family = 'Bangla';
            if ($local == 'sa') $font_family = 'Arabic';
            if ($local == 'il') $font_family = 'Hebrew';

            $default_currency_id = SettingsRepository::getEcommerceSetting('default_currency');
            $default_currency = Currency::find($default_currency_id);

            if (!$default_currency) {
                toastNotification('error', translate('Something went wrong. Please try again'));
                return redirect()->back();
            }

            $currency_font = 'Arial Unicode MS';
            if ($default_currency->symbol == '₹') {
                $currency_font = 'Roboto';
            }

            // Fix logo - convert URL to local file path
            $order_info = $invoice_data;
            if (!empty($order_info['system_properties']['logo'])) {
                $logo_url = $order_info['system_properties']['logo'];
                $clean_path = str_replace([url('/'), '/public', 'public/'], '', $logo_url);
                $clean_path = ltrim($clean_path, '/');
                $logo_local_path = public_path($clean_path);

                $order_info['system_properties']['logo'] = $logo_local_path;
                
            }

            if (!empty($order_info['system_properties']['paid_image'])) {
                $clean_path = str_replace([url('/'), '/public', 'public/'], '', $order_info['system_properties']['paid_image']);
                $clean_path = ltrim($clean_path, '/');
                $local_path = public_path($clean_path);
                if (file_exists($local_path)) {
                    $order_info['system_properties']['paid_image'] = $local_path;
                }
            }

            if (!empty($order_info['system_properties']['unpaid_image'])) {
                $clean_path = str_replace([url('/'), '/public', 'public/'], '', $order_info['system_properties']['unpaid_image']);
                $clean_path = ltrim($clean_path, '/');
                $local_path = public_path($clean_path);
                if (file_exists($local_path)) {
                    $order_info['system_properties']['unpaid_image'] = $local_path;
                }
            }

            Log::info('before transaction!');

            $transaction = PaymentTransaction::where('payment_for', 'LIKE', '%Order ID: ' . $order_id . '%')->first();

            Log::info('after transaction!');

            Log::error('Transaction data', [
                    'transaction' => json_encode($transaction),
            ]);

            $data = [
                'title'        => $invoice_data['order_code'],
                'date'         => date('m/d/Y'),
                'payment_date' => $transaction ? ($transaction->updated_at ?? $transaction->created_at) : null,
                'order_info'   => $order_info,
                'qr_code'      => $qr_temp,
                'font_family'  => $font_family,
                'currency_font'=> $currency_font,
            ];

            $default_language = getLocale();
            $is_rtl = DB::table('tl_languages')
                ->where('code', '=', $default_language)
                ->where('is_rtl', '=', 1)
                ->exists();

            if ($is_rtl) {
                $tenant_id = isTenant();
                $qrCodePath = public_path('tenant/tenant' . $tenant_id . '/invoice_' . $invoice_data['order_code'] . 'qr_code.svg');
                file_put_contents($qrCodePath, $qr_svg_content);
                $data['qr_code'] = $qrCodePath;

                $pdf = NPDF::loadView('plugin/tlecommercecore::orders.invoice.invoice_rtl', $data, [], [
                    'default_font'  => 'dejavusans',
                    'mode'          => 'utf-8',
                    'margin_top'    => 0,
                    'margin_right'  => 0,
                    'margin_bottom' => 0,
                    'margin_left'   => 0,
                    'padding_left'  => 5,
                    'padding_right' => 5,
                ]);

                return $request['action'] == 'preview'
                    ? $pdf->stream($invoice_data['order_code'] . '.pdf')
                    : $pdf->download($invoice_data['order_code'] . '.pdf');
            }

            Log::info('before html!');

            $html = view('plugin/tlecommercecore::orders.invoice.invoice', $data)->render();

            Log::info('after html!');

            $pdf = BPDF::loadHTML($html)
                ->set_option('isRemoteEnabled', true)
                ->set_option('isHtml5ParserEnabled', true)
                ->set_option('isFontSubsettingEnabled', true);

            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            return $request['action'] == 'preview'
                ? $pdf->stream()
                : $pdf->download($invoice_data['order_code'] . '.pdf');

        } catch (\Exception $e) {
            toastNotification('error', translate('Something went wrong. Please try again'));
            return redirect()->back();
        }
    }
    // public function printInvoice(Request $request)
    // {
    //     try {
    //         if (!$request->has('invoice_products')) {
    //             toastNotification('error', 'No product selected');
    //             return redirect()->back();
    //         }
    //         $invoice_data = $this->order_repository->getInvoiceData($request['order_id'], $request['invoice_products']);
    //         $qr_code = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($invoice_data['order_code']));
    //         $font_family = "Roboto";
    //         $local = getLocale();

    //         if ($local  == 'bd') {
    //             $font_family = 'Bangla';
    //         }

    //         if ($local  == 'sa') {
    //             $font_family = 'Arabic';
    //         }

    //         if ($local  == 'il') {
    //             $font_family = 'Hebrew';
    //         }

    //         $default_currency_id = SettingsRepository::getEcommerceSetting('default_currency');
    //         $default_currency = Currency::find($default_currency_id);
    //         $currency_font = 'Arial Unicode MS';
    //         if ($default_currency->symbol == '₹') {
    //             $currency_font = 'Roboto';
    //         }

    //         $data = [
    //             'title' => $invoice_data['order_code'],
    //             'date' => date('m/d/Y'),
    //             'order_info' => $invoice_data,
    //             'qr_code' => $qr_code,
    //             'font_family' => $font_family,
    //             'currency_font' => $currency_font,
    //         ];


    //         $default_language = getLocale();
    //         $is_rtl = DB::table('tl_languages')
    //             ->where('code', '=', $default_language)
    //             ->where('is_rtl', '=', 1)
    //             ->exists();

    //         if ($is_rtl) {
    //             $tenant_id = isTenant();
    //             $qrCodePath = public_path('tenant/tenant' . $tenant_id . '/invoice_' . $invoice_data['order_code'] . 'qr_code.png');
    //             file_put_contents($qrCodePath, base64_decode($qr_code));

    //             $data['qr_code'] = url('tenant/tenant' . $tenant_id . '/invoice_' . $invoice_data['order_code'] . 'qr_code.png');
    //             // $data['qr_code'] = url('public/tenant/tenant' . $tenant_id . '/invoice_' . $invoice_data['order_code'] . 'qr_code.png');


    //             $pdf = NPDF::loadView('plugin/tlecommercecore::orders.invoice.invoice_rtl', $data, [], [
    //                 'default_font' => 'dejavusans',
    //                 'mode' => 'utf-8',
    //                 'margin_top' => 0,
    //                 'margin_right' => 0,
    //                 'margin_bottom' => 0,
    //                 'margin_left' => 0,
    //                 'padding_left' => 5,
    //                 'padding_right' => 5,
    //             ]);

    //             if ($request['action'] == 'preview') {
    //                 return $pdf->stream($invoice_data['order_code'] . '.pdf');
    //             } else {
    //                 return $pdf->download($invoice_data['order_code'] . '.pdf');
    //             }
    //         }

    //         $invoice_view = $is_rtl ?  'plugin/tlecommercecore::orders.invoice.invoice_rtl' : 'plugin/tlecommercecore::orders.invoice.invoice';
    //         $pdf = BPDF::loadView($invoice_view, $data)->set_option('isFontSubsettingEnabled', true);


    //         if ($request['action'] == 'preview') {
    //             return $pdf->stream();
    //         } else {
    //             return $pdf->download($invoice_data['order_code'] . '.pdf');
    //         }
    //     } catch (\Exception $e) {
    //         toastNotification('error', translate('Something went wrong. Please try again'));
    //         return redirect()->back();
    //     }
    // }
}
