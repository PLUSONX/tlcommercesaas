<?php

namespace Plugin\TlcommerceCore\Repositories;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;
use Core\Models\User;
use Core\Http\Mail\NewOrderMail;
use Core\Jobs\SendTenantMailJob;
use Core\Http\Mail\OrderRefundMail;
use Core\Http\Mail\OrderConfirmMail;
use Illuminate\Support\Facades\Mail;
use Core\Http\Mail\ProductReviewEmail;
use Plugin\TlcommerceCore\Models\Orders;
use Core\Http\Mail\OrderStatusUpdateMail;
use Plugin\TlcommerceCore\Models\Customers;
use Plugin\TlcommerceCore\Models\GuestCustomers;
use Illuminate\Support\Facades\Notification;
use Plugin\TlcommerceCore\Models\OrderHasProducts;
use Plugin\TlcommerceCore\Repositories\SettingsRepository;
use Plugin\TlcommerceCore\Notifications\ProductApprovalNotification;
use Plugin\TlcommerceCore\Notifications\OrderStatusUpdateNotification;
use Plugin\TlcommerceCore\Notifications\CustomerOrderCancelNotification;
use Plugin\TlcommerceCore\Notifications\CustomerOrderCreateNotification;
use Plugin\TlcommerceCore\Notifications\CustomerOrderReturnNotification;
use Plugin\TlcommerceCore\Notifications\CustomerProductReviewNotification;
use Plugin\TlcommerceCore\Notifications\CustomerOrderPaymentCompletedNotification;

class EcommerceNotification
{
    /**
     * Will send order invoice notification to customer
     *
     * @param Int $order_id
     * @param Int $customer_id
     */
   public static function sendOrderInvoiceNotification($order_id, $customer_id, $guest_customer_id)
{
    try {
        Log::info("Notification Invoice Process Started", ['order_id' => $order_id, 'customer_id' => $customer_id, 'guest_customer_id' => $guest_customer_id]);

        $order = Orders::with('products.product_details')->find($order_id);
        $notifiable_customer = null;

        if ($order->customer_id != null) {
            $notifiable_customer = Customers::find($order->customer_id);

        } elseif ($order->guest_customer_id != null) {
            $notifiable_customer = GuestCustomers::find($order->guest_customer_id);

        } else {
            Log::warning("Order has neither customer_id nor guest_customer_id", ['order_id' => $order_id]);
            return false;
        }
        

        if (!$order || !$notifiable_customer) return;

        $link = '';
        if ($customer_id > 0) {
            $link = '/dashboard/order-details/' . $order_id;
        } elseif ($guest_customer_id > 0) {
            $link = '/guest/order-details/' . $order_id;
        }

        // Build the HTML Table (The "_order_details_" content)
        $product_rows = '';
        foreach ($order->products as $item) {
            $price = number_format($item->unit_price, 2) . " KD";

            $product_name = $item->product_details->name ?? 'Unknown Product';

            Log::info("Price Data", ['price' => $price]);

            $product_rows .= "
                <tr>
                    <td style='padding: 10px; border-bottom: 1px solid #ededed; text-align: left;'>{$product_name}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #ededed; text-align: center;'>{$item->quantity}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #ededed; text-align: right;'>{$price}</td>
                </tr>";
        }

        $total_formatted = number_format($order->total_payable_amount, 2) . " KD";

        Log::info("Total Price Data", ['total_formatted' => $total_formatted]);

        $invoice_table_html = "
            <table width='100%' border='0' cellpadding='0' cellspacing='0' style='border: 1px solid #ededed; margin-top: 10px;'>
                <thead>
                    <tr style='background-color: #f8f9fa;'>
                        <th style='padding: 10px; border-bottom: 1px solid #ededed; text-align: left;'>Product</th>
                        <th style='padding: 10px; border-bottom: 1px solid #ededed; text-align: center;'>Qty</th>
                        <th style='padding: 10px; border-bottom: 1px solid #ededed; text-align: right;'>Price</th>
                    </tr>
                </thead>
                <tbody>{$product_rows}</tbody>
                <tfoot>
                    <tr>
                        <td colspan='2' style='padding: 10px; text-align: right; font-weight: bold;'>Total:</td>
                        <td style='padding: 10px; text-align: right; font-weight: bold; color: #ef2543;'>{$total_formatted}</td>
                    </tr>
                </tfoot>
            </table>";

        \Log::info('EcommerceNotifcation Method: before mail_data!!!');

        $mail_title = 'Your order has been placed!';
        $message = 'Thank you for your order. Payment has been received.';
        $btn_title = 'Track Your Order';

        Log::info("Mail Data", [
            'template_id'       => 10,
            '_system_logo_url_' => self::getMailLogoUrl(),
            '_site_link_'       => url('/'),
            '_footer_text_'     => getGeneralSetting('copyright_text'),
            'keywords'          => getEmailTemplateVariables(10, true),
        ]);

        $mail_data = [
            'template_id'       => 10,
            'keywords'          => getEmailTemplateVariables(10, true),
            'subject'           => 'Invoice for Order #' . $order->order_code,
            '_system_logo_url_' => self::getMailLogoUrl(),
            '_site_link_'       => url('/'),
            '_footer_text_'     => getGeneralSetting('copyright_text'),
            '_order_code_'      => $order->order_code,
            '_order_details_'   => $invoice_table_html,
            '_tracking_url_'    => url('/') . $link,
            '_customer_name_'   => $notifiable_customer->name,
            '_message_'         => $message,
            '_btn_title_'       => $btn_title,
            '_mail_title_'      => $mail_title,
        ];
        

         \Log::info('EcommerceNotifcation Method: before SendTenantMailJob!!!');

        SendTenantMailJob::dispatch($notifiable_customer->email, $mail_data, getTenantMailConfig());

         \Log::info('EcommerceNotifcation Method: after SendTenantMailJob!!!');


    } catch (\Exception $e) {
        \Log::error("Invoice failure: " . $e->getMessage());
    }
}



    /**
     * Will send order status notification to customer
     *
     * @param Int $order_id
     * @param Int $customer_id
     * @param Int $guest_customer_id
     * @param String $message
     */
    public static function sendOrderStatusNotification($order_id, $customer_id, $guest_customer_id, $message, $btn_title, $mail_title)
    {
        \Log::info('sendOrderStatusNotification method called!!!');
        
        $link = ''; // fixed typo: was $link - ''
        $notifiable_customer = null;
        $data = []; // initialize before try block

        try {
            Log::info("sendOrderStatusNotification Process Started", [
                'order_id' => $order_id,
                'customer_id' => $customer_id,
                'guest_customer_id' => $guest_customer_id
            ]);

            if ($customer_id > 0) {
                $link = '/dashboard/order-details/' . $order_id;
                $notifiable_customer = Customers::find($customer_id);

                $data = [
                    'message' => $message,
                    'link' => $link
                ];

                $notifiable_customer->notify(new OrderStatusUpdateNotification($data));
                Log::info("In-app notification sent successfully", ['customer_id' => $customer_id]);

            } else if ($guest_customer_id > 0) {
                $link = '/guest/order-details/' . $order_id;
                $notifiable_customer = GuestCustomers::where('id', $guest_customer_id)->first();

            } else {
                Log::warning("Order has neither customer_id nor guest_customer_id", ['order_id' => $order_id]);
                return false;
            }

        } catch (\Exception $e) {
            \Log::error("orderStatus Notification failure: " . $e->getMessage());
            return false; // stop execution if first block fails
        }

        // 3. Email Dispatch
        try {
            if ($notifiable_customer == null) {
                Log::warning("No notifiable customer found, skipping email.");
                return false;
            }

            $mail_data = [
                'template_id'       => 11,
                'keywords'          => getEmailTemplateVariables(11, true),
                '_system_logo_url_' => self::getMailLogoUrl(),
                '_site_link_'       => url('/'),
                'subject'           => $mail_title,
                '_tracking_url_'    => url('/') . $link,
                '_customer_name_'   => $notifiable_customer->name,
                '_message_'         => $message,
                '_btn_title_'       => $btn_title,
                '_mail_title_'      => $mail_title,
            ];

            SendTenantMailJob::dispatch($notifiable_customer->email, $mail_data, getTenantMailConfig());
            Log::info("Email job dispatched successfully", ['email' => $notifiable_customer->email]);

        } catch (Exception $e) {
            Log::error("Failed to dispatch email job", [
                'error' => $e->getMessage(),
                'email' => $notifiable_customer->email ?? 'unknown'
            ]);
        }
    }
    // public static function sendOrderStatusNotification($order_id, $customer_id, $guest_customer_id, $message, $btn_title, $mail_title)
    // {
    //     \Log::info('sendOrderStatusNotification method called!!!');
    //     $link - '';
    //     $notifiable_customer = null;
    //     try {

    //         Log::info("sendOrderStatusNotification Process Started", ['order_id' => $order_id, 'customer_id' => $customer_id, 'guest_customer_id' => $guest_customer_id]);

    //         // $link - '';
    //         // $link = '/dashboard/order-details/' . $order_id;
    //         // $data = [
    //         //     'message' => $message,
    //         //     'link' => $link
    //         // ];

    //         // 1. Fetch Customer
    //         // $notifiable_customer = null;

    //         if($customer_id > 0) {

    //             $link = '/dashboard/order-details/' . $order_id;

    //             $notifiable_customer = Customers::find($customer_id);

    //             $notifiable_customer->notify(new OrderStatusUpdateNotification($data));
    //             Log::info("In-app notification sent successfully", ['customer_id' => $customer_id]);
    //         }
    //         else if($guest_customer_id > 0) {
    //             $link = '/guest/order-details/' . $order_id;
    //             $notifiable_customer = GuestCustomers::where('id', $guest_customer_id)->first();
    //         }
    //         else {
    //             Log::warning("Order has neither customer_id nor guest_customer_id", ['order_id' => $order_id]);
    //             return false;
    //         }

    //     }
    //     catch (\Exception $e) {
    //         \Log::error("orderStatus Notification failure: " . $e->getMessage());
    //     }

    //     $data = [
    //             'message' => $message,
    //             'link' => $link
    //     ];
        

    //     // 3. Email Dispatch
    //     try {
    //         $mail_data = [
    //             'template_id' => 11,
    //             'keywords' => getEmailTemplateVariables(11, true),
    //             '_system_logo_url_' => self::getMailLogoUrl(),
    //             '_site_link_'       => url('/'),
    //             'subject' => $mail_title,
    //             '_tracking_url_' => url('/') . $link ?? '',
    //             '_customer_name_' => $notifiable_customer->name,
    //             '_message_' => $message,
    //             '_btn_title_' => $btn_title,
    //             '_mail_title_' => $mail_title,
    //         ];

    //         SendTenantMailJob::dispatch($notifiable_customer->email, $mail_data, getTenantMailConfig());
            
    //         Log::info("Email job dispatched successfully", ['email' => $notifiable_customer->email]);

    //     } catch (Exception $e) {
    //         Log::error("Failed to dispatch email job", [
    //             'error' => $e->getMessage(),
    //             'email' => $notifiable_customer->email
    //         ]);
    //     }

        
    
    // }
    // public static function sendOrderStatusNotification($order_id, $customer_id, $message, $btn_title, $mail_title)
    // {

    //     $link = '/dashboard/order-details/' . $order_id;
    //     $data = [
    //         'message' => $message,
    //         'link' => $link
    //     ];
    //     $notifiable_customer = Customers::where('id', $customer_id)->first();
    //     if ($notifiable_customer != null) {
    //         $notifiable_customer->notify(new OrderStatusUpdateNotification($data));
    //         //Send mail to customer
    //         $mail_data = [
    //             'template_id' => 11,
    //             'keywords' => getEmailTemplateVariables(11, true),
    //             'subject' => $mail_title,
    //             '_tracking_url_' => url('/') . '/dashboard/order-details/' . $order_id,
    //             '_customer_name_' => $notifiable_customer->name,
    //             '_message_' => $message,
    //             '_btn_title_' => $btn_title,
    //             '_mail_title_' => $mail_title,
    //         ];
    //         SendTenantMailJob::dispatch($notifiable_customer->email, $mail_data, getTenantMailConfig());
    //     }
    // }
    /**
     * Will send order item status notification to seller
     *
     * @param Int $seller
     * @param String $message
     */
    public static function sendOrderItemUpdateStatusNotificationToSeller($order_id, $seller_id, $message)
    {

        $seller_link = '/seller/order-details/' . $order_id;
        $seller_data = [
            'message' => $message,
            'link' => $seller_link
        ];
        $notifiable_seller = User::where('id', $seller_id)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
        if ($notifiable_seller != null) {
            Notification::send($notifiable_seller, new OrderStatusUpdateNotification($seller_data));
        }
    }
    /**
     * Will send order status notification to admin
     *
     * @param Int $order_id
     */
    public static function sendSellerCreateProductNotificationToAdmin($product_id)
    {
        $link = '/seller-products';
        $message = translate('Seller create a new product');
        $data = [
            'message' => $message,
            'link' => $link
        ];
        //Send notification to admin
        $notifiable_admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($notifiable_admins != null) {
            Notification::send($notifiable_admins, new OrderStatusUpdateNotification($data));
        }
    }
    /**
     * Will send order status notification to admin
     *
     * @param Int $order_id
     */
    public static function sendSellerOrderStatusNotificationToAdmin($order_id, $message = null)
    {
        $link = '/orders/order-details/' . $order_id;
        $order_details = Orders::where('id', $order_id)->first();
        $message = 'Order code ' . $order_details->order_code . ' has been accepted by seller';
        $data = [
            'message' => $message,
            'link' => $link
        ];
        //Send notification to admin
        $notifiable_admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($notifiable_admins != null) {
            Notification::send($notifiable_admins, new OrderStatusUpdateNotification($data));
        }
    }
    /**
     * Will send new order notification
     */

    public static function sendNewOrderNotification($order)
    {
        \Log::info('sendNewOrderNotification method called!!!');

        try {
            //Send notification to admin
        $link = '/orders/order-details/' . $order->id;
        $message =  "New order has been placed. Order code " . $order->order_code;
        $data = [
            'message' => $message,
            'link' => $link
        ];
        $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($admins != null) {
                // \Log::info('Admin is not null!!!');

                // \Log::info('Admins Data:', [
                //     'count' => count($admins),
                //     'class' => get_class($admins),
                //     'emails' => $admins->pluck('email')->toArray()
                // ]);
                // \Log::info('Payload Data:', $data);

                $notification = new CustomerOrderCreateNotification($data);
                
                // \Log::info('Notification Data', [
                //     'notification' => $notification
                // ]);

            try {
                    // \Log::info('Testing manual insert...');

                    // $fullType = 'Plugin\TlcommerceCore\Notifications\CustomerOrderCreateNotification';

                    // \Log::info('Type length', [
                    //     'length' => strlen($fullType),
                    //     'value' => $fullType,
                    //     'escaped' => addslashes($fullType)
                    // ]);
                    // \DB::connection('tenant')->table('notifications')->insert([
                    //     'id' => \Str::uuid(),
                    //     'type' => 'Plugin\\TlcommerceCore\\Notifications\\CustomerOrderCreateNotification', // Double backslashes
                    //     'notifiable_type' => 'Core\\Models\\User',
                    //     'notifiable_id' => 1,
                    //     'data' => json_encode($data),
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ]);
                    // \DB::connection('tenant')->table('notifications')->insert([
                    //     'id' => \Str::uuid(),
                    //     'type' => 'test',
                    //     // 'type' => 'Plugin\TlcommerceCore\Notifications\CustomerOrderCreateNotification',
                    //     'notifiable_type' => 'Core\Models\User',
                    //     'notifiable_id' => 1,
                    //     'data' => json_encode($data),
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ]);
                    // \Log::info('Manual insert worked!');
                // \Log::info('Current DB connection:', [
                //     'connection' => \DB::connection()->getName(),
                //     'database' => \DB::connection()->getDatabaseName()
                // ]);

                // \Log::info('User connection:', [
                //     'connection' => $admins->first()->getConnectionName()
                // ]);
                // \Log::info('About to send notification');
                Notification::send($admins, $notification);
                // \Log::info('Notification send completed');

            } catch (\Throwable $e) {

                // dd([
                //     'Message' => $e->getMessage(),
                //     'File'    => $e->getFile(),
                //     'Line'    => $e->getLine(),
                //     'Sql'     => $e instanceof \Illuminate\Database\QueryException ? $e->getSql() : 'Not a SQL error'
                // ]);

                // \Log::info("Notification system crashed!", [
                //     'message' => $e->getMessage(),
                //     'file' => $e->getFile(),
                //     'line' => $e->getLine()
                // ]);

                \Log::error("Notification system crashed!", [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }

            // Notification::send($admins, new CustomerOrderCreateNotification($data));
        }
        // \Log::info('Outside Admins!!!');
        //Send Email to admin
        if (SettingsRepository::getEcommerceSetting('admin_new_order_email_notification') == config('settings.general_status.active')) 
        {
            $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->pluck('email');
            
            try {
                $orderDetailsHtml = view('plugin/tlecommercecore::mail.order_details_mail', ['order_id' => $order->id])->render();
            } catch (\Exception $e) {
                \Log::error("Mail rendering failed: " . $e->getMessage());
                $orderDetailsHtml = "Order details currently unavailable in email.";
            }
            
            $mail_data = [
                'template_id' => 13,
                'keywords' => getEmailTemplateVariables(13, true),
                '_system_logo_url_' => self::getMailLogoUrl(),
                '_site_link_'       => url('/'),
                'subject' => 'New Order Placed!',
                '_order_code_' =>  $order->order_code,
                '_tracking_url_' => url('/') . '/' . getAdminPrefix() . '/orders/order-details/' . $order->id,
                '_order_details_' => $orderDetailsHtml,
                // '_order_details_' => view('plugin/tlecommercecore::mail.order_details_mail', ['order_id' => $order->id])->render(),
            ];

            SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
        }

        //send notification to seller
        // if (isActivePluging('multivendor')) {
        //     $seller_link = '/seller/order-details/' . $order->id;
        //     $seller_data = [
        //         'message' => $message,
        //         'link' => $seller_link
        //     ];
        //     $seller_ids = OrderHasProducts::where('order_id', $order->id)->distinct()->pluck('seller_id');

        //     $sellers = User::whereIn('id', $seller_ids)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
        //     if ($sellers != null) {
        //         Notification::send($sellers, new CustomerOrderCreateNotification($seller_data));
        //     }
        // }

        \Log::info('Ecommernotification Method: before customer region!!!');
        //Send invoice to customer
        // if (SettingsRepository::getEcommerceSetting('send_invoice_to_customer_mail') == config('settings.general_status.active')) {
    

            // \Log::info('Ecommernotification Method: after customer region!!!');
            // Get the customer ID based on whether they are logged in or a guest
            
            if($order->customer_id != null) {
                
                self::sendOrderInvoiceNotification($order->id, $order->customer_id, 0);
            }

            if($order->guest_customer_id != null) {

                \Log::info('Ecommernotification Method: inside guest customer region');

                self::sendOrderInvoiceNotification($order->id, 0, $order->guest_customer_id);
            }
            
            // if ($customer_id) {
            //     \Log::info('Ecommernotification Method: before sending notification to customer!!!');
            //     // Delegate to our new clean method for Template ID 6
            //     self::sendOrderInvoiceNotification($order->id, $customer_id);

            //     \Log::info('Ecommernotification Method: after sending notification to customer!!!');

            // }
        // }
        // if (SettingsRepository::getEcommerceSetting('send_invoice_to_customer_mail') == config('settings.general_status.active')) {
        //     $customer_email = $order->customer_info != null ? $order->customer_info?->email : $order->guest_customer?->email;
        //     $customer_name = $order->customer_info != null ? $order->customer_info?->name : 'Guest Customer';
        //     if ($customer_email != null) {
        //         $mail_data = [
        //             'template_id' => 10,
        //             'keywords' => getEmailTemplateVariables(10, true),
        //             'subject' => 'Your order has been placed!',
        //             '_order_code_' =>  $order->order_code,
        //             '_tracking_url_' => url('/') . '/dashboard/order-details/' . $order->id,
        //             '_customer_name_' => $customer_name,
        //             '_order_details_' => view('plugin/tlecommercecore::mail.order_details_mail', ['order_id' => $order->id])->render(),
        //         ];
        //         SendTenantMailJob::dispatch($customer_email, $mail_data, getTenantMailConfig());
        //     }
        // }
        }
        catch (\Exception $e) {
        \Log::error("Global Notification Method Error: " . $e->getMessage());
    }

        
    }


    /**
     * Will send new Feedback notification
     */

    public static function sendNewFeedbackNotification($feedback)
    {
        \Log::info('sendNewFeedbackNotification method called!!!');

        try {
            //Send notification to admin
            $name =  $feedback->name;
            $phone =  $feedback->phone;
            $satisfaction_rating =  $feedback->satisfaction_rating;
            $comment =  $feedback->comment;
            $link = '#';
            $message =  "New Feedback has been placed.";
            
            $data = [
                'name' => $name,
                'phone' => $phone,
                'comment' => $comment,
                'satisfaction_rating' => $satisfaction_rating,
                'message' => $message,
                'link' => $link
            ];
            $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
            if ($admins != null) {
                    \Log::info('Admin is not null!!!');

                    // \Log::info('Admins Data:', [
                    //     'count' => count($admins),
                    //     'class' => get_class($admins),
                    //     'emails' => $admins->pluck('email')->toArray()
                    // ]);
                    // \Log::info('Payload Data:', $data);

                    $notification = new CustomerOrderCreateNotification($data);
                    
                    \Log::info('after notification variable!!!');

                try {
                        
                    \Log::info('About to send notification');

                    Notification::send($admins, $notification);

                    \Log::info('Notification send completed');

                } catch (\Throwable $e) {

                    \Log::error("Notification system crashed!", [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                }

            }

            // \Log::info('outside email section');

            // if (SettingsRepository::getEcommerceSetting('admin_new_order_email_notification') == config('settings.general_status.active')) 
            // {

                \Log::info('inside email section');

                $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->pluck('email');
                
                Log::info("admins emails", ['admins' => json_encode($admin_emails)]);

                $mail_data = [
                    'template_id' => 15,
                    'keywords' => getEmailTemplateVariables(15, true),
                    '_system_logo_url_' => self::getMailLogoUrl(),
                    '_site_link_'       => url('/'),
                    'subject' => 'New Feedback Placed!',
                    '_customer_name_' => $name,
                    '_phone_' => $phone,
                    '_comment_' => $comment,
                    '_satisfaction_rating_' => $satisfaction_rating,
                    '_mail_title_' => $message,
                ];

                SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
            // }

           
        
        }
        catch (\Exception $e) {
            \Log::error("Global Notification Method Error: " . $e->getMessage());
    }

        
    }

    /**
     * Will send new order review notification
     */
    public static function sendNewReviewNotification($review, $order)
    {
        try {
            $customer = $review->customer;
            $name = $customer != null ? $customer->name : '';
            $phone = $customer != null ? $customer->phone : '';
            $rating = $review->rating;
            $comment = $review->review;
            $order_code = $order->order_code;
            $link = '#';
            $message = "New order review has been placed for order " . $order_code;

            $data = [
                'name' => $name,
                'phone' => $phone,
                'comment' => $comment,
                'satisfaction_rating' => $rating,
                'message' => $message,
                'link' => $link,
            ];

            $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))
                ->where('status', config('settings.general_status.active'))
                ->get();

            if ($admins != null) {
                $notification = new CustomerOrderCreateNotification($data);

                try {
                    Notification::send($admins, $notification);
                } catch (\Throwable $e) {
                    \Log::error("Review notification system crashed!", [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }

            $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))
                ->where('status', config('settings.general_status.active'))
                ->pluck('email');

            $mail_data = [
                'template_id' => 16,
                'keywords' => getEmailTemplateVariables(16, true),
                '_system_logo_url_' => self::getMailLogoUrl(),
                '_site_link_'       => url('/'),
                'subject' => 'New Order Review Placed!',
                '_customer_name_' => $name,
                '_phone_' => $phone,
                '_comment_' => $comment,
                '_satisfaction_rating_' => $rating,
                '_mail_title_' => $message,
            ];

            SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
        } catch (\Exception $e) {
            \Log::error("Global Review Notification Method Error: " . $e->getMessage());
        }
    }

    // public static function sendNewOrderNotification($order)
    // {
    //     \Log::info('sendNewOrderNotification method called!!!');

    //     //Send notification to admin
    //     $link = '/orders/order-details/' . $order->id;
    //     $message =  "New order has been placed. Order code " . $order->order_code;
    //     $data = [
    //         'message' => $message,
    //         'link' => $link
    //     ];
    //     $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
    //     if ($admins != null) {
    //         Notification::send($admins, new CustomerOrderCreateNotification($data));
    //     }
    //     //Send Email to admin
    //     if (SettingsRepository::getEcommerceSetting('admin_new_order_email_notification') == config('settings.general_status.active')) {
    //         $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->pluck('email');
    //         $mail_data = [
    //             'template_id' => 13,
    //             'keywords' => getEmailTemplateVariables(13, true),
    //             'subject' => 'New Order Placed!',
    //             '_order_code_' =>  $order->order_code,
    //             '_tracking_url_' => url('/') . '/' . getAdminPrefix() . '/orders/order-details/' . $order->id,
    //             '_order_details_' => view('plugin/tlecommercecore::mail.order_details_mail', ['order_id' => $order->id])->render(),
    //         ];

    //         SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
    //     }

    //     //send notification to seller
    //     if (isActivePluging('multivendor')) {
    //         $seller_link = '/seller/order-details/' . $order->id;
    //         $seller_data = [
    //             'message' => $message,
    //             'link' => $seller_link
    //         ];
    //         $seller_ids = OrderHasProducts::where('order_id', $order->id)->distinct()->pluck('seller_id');

    //         $sellers = User::whereIn('id', $seller_ids)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
    //         if ($sellers != null) {
    //             Notification::send($sellers, new CustomerOrderCreateNotification($seller_data));
    //         }
    //     }

    //     //Send invoice to customer
    //     if (SettingsRepository::getEcommerceSetting('send_invoice_to_customer_mail') == config('settings.general_status.active')) {
    //         $customer_email = $order->customer_info != null ? $order->customer_info?->email : $order->guest_customer?->email;
    //         $customer_name = $order->customer_info != null ? $order->customer_info?->name : 'Guest Customer';
    //         if ($customer_email != null) {
    //             $mail_data = [
    //                 'template_id' => 10,
    //                 'keywords' => getEmailTemplateVariables(10, true),
    //                 'subject' => 'Your order has been placed!',
    //                 '_order_code_' =>  $order->order_code,
    //                 '_tracking_url_' => url('/') . '/dashboard/order-details/' . $order->id,
    //                 '_customer_name_' => $customer_name,
    //                 '_order_details_' => view('plugin/tlecommercecore::mail.order_details_mail', ['order_id' => $order->id])->render(),
    //             ];
    //             SendTenantMailJob::dispatch($customer_email, $mail_data, getTenantMailConfig());
    //         }
    //     }
    // }
    /**
     * Will send new order notification
     *
     * @param Int $order_id
     */
    public static function sendCustomerOrderCancelNotification($order_id, $message)
    {

        //Send notification to admin
        $link = '/orders/order-details/' . $order_id;
        $data = [
            'message' => $message,
            'link' => $link
        ];
        $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($admins != null) {
            Notification::send($admins, new CustomerOrderCancelNotification($data));
        }

        //Send customer order cancel email notification to admin
        if (SettingsRepository::getEcommerceSetting('admin_order_cancel_email_notification') == config('settings.general_status.active')) {
            $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->pluck('email');
            $mail_data = [
                'template_id' => 14,
                'keywords' => getEmailTemplateVariables(14, true),
                '_system_logo_url_' => self::getMailLogoUrl(),
                '_site_link_'       => url('/'),
                'subject' => 'Order Cancelled!',
                '_mail_title_' =>  "Order Cancelled",
                '_btn_title_' =>  "View Order Details",
                '_message_' =>  $message,
                '_action_url_' => url('/') . '/' . getAdminPrefix() . '/orders/order-details/' . $order_id,
            ];
            SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
        }

        //send notification to seller
        // if (isActivePluging('multivendor')) {
        //     $seller_link = '/seller/order-details/' . $order_id;
        //     $seller_data = [
        //         'message' => $message,
        //         'link' => $seller_link
        //     ];
        //     $seller_ids = OrderHasProducts::where('order_id', $order_id)->distinct()->pluck('seller_id');
        //     $notifiable_sellers = User::whereIn('id', $seller_ids)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
        //     if ($notifiable_sellers != null) {
        //         Notification::send($notifiable_sellers, new CustomerOrderCancelNotification($seller_data));
        //     }
        // }
    }


    /**
     * Will send order status notification to customer
     *
     * @param Int $order_id
     * @param Int $customer_id
     * @param String $message
     */
    public static function sendCustomerOrderConfirmationNotification($order_id, $customer_id, $guest_customer_id, $message, $btn_title, $mail_title)
    {
        Log::info("Order Confirmation Notifcation Process Started", ['order_id' => $order_id, 'customer_id' => $customer_id, 'guest_customer_id' => $guest_customer_id]);

        $order = Orders::with('products.product_details')->find($order_id);

        $link = '';
        // $link = '/dashboard/order-details/' . $order_id;
        $data = [
            'message' => $message,
            'link' => $link
        ];

        $notifiable_customer = null;

        if($customer_id > 0) {

            $link = '/dashboard/order-details/' . $order_id;

           $notifiable_customer = Customers::where('id', $customer_id)->first();
        }
        else if($guest_customer_id > 0) {

            $link = '/guest/order-details/' . $order_id;

           $notifiable_customer = GuestCustomers::where('id', $guest_customer_id)->first();
        }
        else {
            Log::warning("Order has neither customer_id nor guest_customer_id", ['order_id' => $order_id]);
            return false;
        }
        
        if ($notifiable_customer != null) {

            if($customer_id > 0) {

                $notifiable_customer->notify(new OrderStatusUpdateNotification($data));
            }

            // Build the HTML Table (The "_table_" content)
            $product_rows = '';
            foreach ($order->products as $item) {
                $price = number_format($item->unit_price, 2) . " KD";

                $product_name = $item->product_details->name ?? 'Unknown Product';

                Log::info("Price Data", ['price' => $price]);

                $product_rows .= "
                    <tr>
                        <td style='padding: 10px; border-bottom: 1px solid #ededed; text-align: left;'>{$product_name}</td>
                        <td style='padding: 10px; border-bottom: 1px solid #ededed; text-align: center;'>{$item->quantity}</td>
                        <td style='padding: 10px; border-bottom: 1px solid #ededed; text-align: right;'>{$price}</td>
                    </tr>";
            }


            $total_formatted = number_format($order->total_payable_amount, 2) . " KD";

            Log::info("Total Price Data", ['total_formatted' => $total_formatted]);

            $invoice_table_html = "
                <table width='100%' border='0' cellpadding='0' cellspacing='0' style='border: 1px solid #ededed; margin-top: 10px;'>
                    <thead>
                        <tr style='background-color: #f8f9fa;'>
                            <th style='padding: 10px; border-bottom: 1px solid #ededed; text-align: left;'>Product</th>
                            <th style='padding: 10px; border-bottom: 1px solid #ededed; text-align: center;'>Qty</th>
                            <th style='padding: 10px; border-bottom: 1px solid #ededed; text-align: right;'>Price</th>
                        </tr>
                    </thead>
                    <tbody>{$product_rows}</tbody>
                    <tfoot>
                        <tr>
                            <td colspan='2' style='padding: 10px; text-align: right; font-weight: bold;'>Total:</td>
                            <td style='padding: 10px; text-align: right; font-weight: bold; color: #ef2543;'>{$total_formatted}</td>
                        </tr>
                    </tfoot>
                </table>";



                //Send mail to customer
                $mail_data = [
                    'template_id' => 10,
                    'keywords' => getEmailTemplateVariables(10, true),
                    '_system_logo_url_' => self::getMailLogoUrl(),
                    '_site_link_'       => url('/'),
                    '_order_code_'      => $order->order_code,
                    'subject' => $mail_title,
                    '_order_details_' => $invoice_table_html,
                    '_tracking_url_' => url('/') . $link,
                    '_customer_name_' => $notifiable_customer->name,
                    '_message_' => $message,
                    '_btn_title_' => $btn_title,
                    '_mail_title_' => $mail_title,
                ];
                SendTenantMailJob::dispatch($notifiable_customer->email, $mail_data, getTenantMailConfig());
        }
    }

    /**
     * Will send customer product review notification to admin
     *
     * @param String $message
     */
    public static function sendCustomerProductReviewNotification($message)
    {
        $link = '/product-reviews';
        $data = [
            'message' => $message,
            'link' => $link
        ];
        $users = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($users != null) {
            Notification::send($users, new CustomerProductReviewNotification($data));
        }

        //Send customer product review email notification to admin
        if (SettingsRepository::getEcommerceSetting('admin_product_review_email_notification') == config('settings.general_status.active')) {
            $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->pluck('email');
            $mail_data = [
                'template_id' => 14,
                'keywords' => getEmailTemplateVariables(14, true),
                '_system_logo_url_' => self::getMailLogoUrl(),
                'subject' => 'Product Review',
                '_mail_title_' =>  "Product Review Received",
                '_btn_title_' =>  "View Product Reviews",
                '_message_' =>  $message,
                '_action_url_' => url('/') . '/' . getAdminPrefix() . '/product-reviews',
            ];
            SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
        }
    }

    /**
     * Will send customer order return notification to admin
     *
     * @param Int $id
     * @param String $message
     */
    public static function sendCustomerOrderReturnNotification($refund_id, $message, $seller_id = null)
    {
        //Send notification to admin
        $link = '/refunds/refund-request-details/' . $refund_id;
        $data = [
            'message' => $message,
            'link' => $link
        ];
        $notifiable_admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($notifiable_admins != null) {
            Notification::send($notifiable_admins, new CustomerOrderReturnNotification($data));
        }
        //Send email notification to admin
        if (SettingsRepository::getEcommerceSetting('admin_order_refund_email_notification') == config('settings.general_status.active')) {
            $admin_emails = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->pluck('email');
            $mail_data = [
                'template_id' => 14,
                'keywords' => getEmailTemplateVariables(14, true),
                '_system_logo_url_' => self::getMailLogoUrl(),
                'subject' => 'Refund Request Created',
                '_mail_title_' =>  "Refund Request Created",
                '_btn_title_' =>  "View Request Details",
                '_message_' =>  $message,
                '_action_url_' => url('/') . '/' . getAdminPrefix() . '/refunds/refund-request-details/' . $refund_id,
            ];
            SendTenantMailJob::dispatch($admin_emails, $mail_data, getTenantMailConfig());
        }

        //send notification to seller
        if ($seller_id != null && isActivePluging('multivendor')) {
            $seller_link = '/seller/refunds';
            $seller_data = [
                'message' => $message,
                'link' => $seller_link
            ];
            $notifiable_seller = User::where('id', $seller_id)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
            if ($notifiable_seller != null) {
                Notification::send($notifiable_seller, new CustomerOrderReturnNotification($seller_data));
            }
        }
    }

    /**
     * Will send customer order payment completed to admin
     *
     * @param Int $order_id
     * @param String $message
     */
    public static function sendCustomerOrderPaymentCompletedNotification($order_id, $message)
    {
        $link = '/orders/order-details/' . $order_id;
        $data = [
            'message' => $message,
            'link' => $link
        ];
        $notifiable_admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($notifiable_admins != null) {
            Notification::send($notifiable_admins, new CustomerOrderPaymentCompletedNotification($data));
        }
    }
    /**
     * Will send payout request status update notification to seller
     *
     * @param Int $seller
     * @param String $message
     */
    public static function sendPayoutRequestStatusUpdateNotificationToSeller($seller_id, $message)
    {
        $seller_link = '/seller/payout-requests';
        $seller_data = [
            'message' => $message,
            'link' => $seller_link
        ];
        $notifiable_seller = User::where('id', $seller_id)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
        if ($notifiable_seller != null) {
            Notification::send($notifiable_seller, new OrderStatusUpdateNotification($seller_data));
        }
    }
    /**
     * Will send payout request create notification to admin
     *
     * @param Int $seller
     * @param String $message
     */
    public static function sendPayoutRequestNotificationToAdmin()
    {

        $link = '/seller-payout-requests';
        $data = [
            'message' => 'A seller create a payout request',
            'link' => $link
        ];
        $notifiable_admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();
        if ($notifiable_admins != null) {
            Notification::send($notifiable_admins, new CustomerOrderPaymentCompletedNotification($data));
        }
    }

    /**
     * Will send earning notification to seller
     *
     * @param Int $seller
     * @param String $message
     */
    public static function sendEarningNotificationToSeller($seller_id, $message)
    {

        $seller_link = '/seller/earning';
        $seller_data = [
            'message' => $message,
            'link' => $seller_link
        ];
        $notifiable_seller = User::where('id', $seller_id)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
        if ($notifiable_seller != null) {
            Notification::send($notifiable_seller, new OrderStatusUpdateNotification($seller_data));
        }
    }

    /**
     * Will send update seller product approval status notification to seller
     *
     * @param Int $seller
     * @param String $message
     */
    public static function sendUpdateProductApprovalStatusNotificationToSeller($seller_id, $message)
    {

        $seller_link = '/seller/products';
        $seller_data = [
            'message' => $message,
            'link' => $seller_link
        ];
        $notifiable_seller = User::where('id', $seller_id)->where('user_type', config('tlecommercecore.user_type.seller'))->get();
        if ($notifiable_seller != null) {
            Notification::send($notifiable_seller, new ProductApprovalNotification($seller_data));
        }
    }

    /**
     * Absolute logo URL for tenant email templates.
     */
    private static function getMailLogoUrl(): string
    {
        $logoId = getGeneralSetting('admin_logo') ?: getGeneralSetting('white_background_logo');
        $logo = asset(getFilePath($logoId));

        // Email clients need a public absolute URL; web root is already public/.
        $logo = str_replace('/tenancy/assets', '', $logo);
        $logo = str_replace('public/', '', $logo);
        $logo = str_replace('/public', '', $logo);
        $logo = preg_replace('#(?<!:)//+#', '/', $logo);

        return $logo ?: '';
    }
}
