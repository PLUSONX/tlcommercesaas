<?php

namespace Plugin\Carrier\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Plugin\Carrier\Models\ShippingCarrier;
use Plugin\TlcommerceCore\Models\ShippingCourier;
use Plugin\TlcommerceCore\Models\ShippingCourierProperties;
use Plugin\TlcommerceCore\Models\ShippingCourierOrders;
use Plugin\TlcommerceCore\Models\Orders;
use Plugin\Carrier\Services\ArmadaService;
use Illuminate\Http\Request;
use Core\Models\User;
use Illuminate\Support\Facades\Notification;
use Plugin\TlcommerceCore\Notifications\CourierOrderUpdateNotification;


class CarrierRepository
{
    protected $armadaService;

    public function __construct(ArmadaService $armadaService)
    {
        $this->armadaService = $armadaService;
    }
    /**
     * Will store new courier service
     *
     * @param Object $request
     * @return bool
     */
    public function storeNewCourier($request)
    {
        try {
            $courier = new ShippingCarrier;
            $courier->name = $request['name'];
            $courier->tracking_url = $request['tracking_url'];
            $courier->logo = $request['logo'];
            $courier->save();
            return true;
        } catch (\Exception $e) {
            return false;
        } catch (\Error $e) {
            return false;
        }
    }
    /**
     * Will return all courier list
     *
     * @return collections
     */
    public function couriers($status = null)
    {
        if ($status != null) {
            return ShippingCarrier::where('status', $status)->get()->map(function ($courier) {
                return [
                    'id' => $courier->id,
                    'name' => $courier->name,
                    'tracking_url' => $courier->tracking_url,
                    'logo' => $courier->logo,
                    'status' => $courier->status
                ];
            });
        } else {
            return ShippingCarrier::all()->map(function ($courier) {
                return [
                    'id' => $courier->id,
                    'name' => $courier->name,
                    'logo' => $courier->logo,
                    'tracking_url' => $courier->tracking_url,
                    'status' => $courier->status
                ];
            });
        }
    }
    /**
     * Will update courier status
     *
     * @param Int $id
     * @return bool
     */
    public function updateCourierStatus($id)
    {
        try {
            DB::beginTransaction();
            $courier = ShippingCarrier::findOrFail($id);
            $status = $courier->status == config('settings.general_status.in_active') ? config('settings.general_status.active') : config('settings.general_status.in_active');
            $courier->status = $status;
            $courier->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }
    /**
     * Will delete a courier
     *
     * @param Int $id
     * @return bool
     */
    public function deleteCourier($id)
    {
        try {
            DB::beginTransaction();
            $courier = ShippingCarrier::findOrFail($id);
            $courier->delete();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Will return courier info
     *
     * @param Int $id
     * @return collection
     */
    public function courierDetails($id)
    {
        return ShippingCarrier::findOrFail($id);
    }
    /**
     * Will update courier details
     *
     * @param Object $request
     * @return bool
     */
    public function updateCourier($request)
    {
        try {
            DB::beginTransaction();
            $courier = ShippingCarrier::findOrFail($request['id']);
            $courier->name = $request['name'];
            $courier->tracking_url = $request['tracking_url'];
            $courier->logo = $request['edit_logo'];
            $courier->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


    /**
     * Will return courier properties
     *
     * @param Int $id
     * @return collection
     */
    public function courierProperties($id)
    {
        Log::info("courierProperties method called:", ['id' => $id]);

        $courier = ShippingCourier::where('id', $id)->with('properties')->first();

        if (!$courier || !$courier->properties) {
            Log::warning("Courier or properties not found for ID: " . $id);
            return [
                'shipping_courier_id' => $id
            ];
            // return null; 
        }

        $properties = $courier->properties;

        return [
            'shipping_courier_id' => $id,
            'api_key' => $properties->api_key,
            'api_secret' => $properties->api_secret,
            'branch_id' => $properties->branch_id,
        ];
    }

     /**
     * Will save courier properties
     *
     * @param Object $request
     * @return bool
     */
    public function submitCourierProperties(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info("submitCourierProperties method called!");
        
            Log::info("Courier Properties data:", ['request' => $request->all()]);

            ShippingCourierProperties::updateOrCreate(
                ['shipping_courier_id' => $request->shipping_courier_id], // The search criteria
                [
                    'api_key'    => trim((string) $request->api_key),
                    'api_secret' => trim((string) $request->api_secret), // This will be auto-encrypted by your Model mutator
                    'branch_id'  => trim((string) $request->branch_id),
                ]
            );
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("properties submission failure: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Will save courier properties
     *
     * @param Object $request
     * @return bool
     */
    public function submitCourierRequest($request)
    {
        try {
            // DB::beginTransaction();

            Log::info("submitCourierRequest 2 method called!");
        
            Log::info("Courier Properties data:", ['request' => $request->all()]);

            $order_id = $request->order_id;
            $shipping_courier_id = $request->available_couriers;

            $courier = ShippingCourier::where('id', $shipping_courier_id)->with('properties')->first();

            Log::info("Courier data:", ['courier' => json_encode($courier)]);

            if (!$courier || !$courier->properties) {
                Log::warning("Courier or properties not found for ID: " . $shipping_courier_id);
                return [
                    'shipping_courier_id' => $shipping_courier_id
                ];
            }

            $order = Orders::where('id', $order_id)->with('shipping_details.country', 'shipping_details.state', 'shipping_details.city')->first();

            if (!$order) {
                Log::warning("Order not found for ID: " . $order_id);
                return [
                    'order_id' => $order_id
                ];
            }

            Log::info("order data:", ['order' => json_encode($order)]);

            $address = $order->shipping_details;

            if (!$address) {
                Log::warning("Shipping address not found for order", [
                    'order_id' => $order_id,
                    'shipping_address_id' => $order->shipping_address,
                ]);
                return false;
            }

            Log::info("address data with relations:", ['address' => $address->toArray()]);

            $armadaResponse = $this->armadaService->createDelivery($courier, $order, $address);
           
            if ($armadaResponse['success']) {
                $data = $armadaResponse['data'];

                try {

                     ShippingCourierOrders::create([
                        'order_id'             => $order->id,
                        'shipping_courier_id'  => $courier->id,
                        'code'                 => $data['code'],
                        'status'               => $data['status'],
                        'amount'               => $data['amount'],
                        'delivery_fee'         => $data['delivery_fee'],
                        'currency'             => $data['currency'],
                        'driver_name'          => $data['driver']['name'] ?? null,
                        'driver_phone'         => $data['driver']['phone'] ?? null,
                        'driver_latitude'      => $data['driver']['latitude'] ?? null,
                        'driver_longitude'     => $data['driver']['longitude'] ?? null,
                        'estimated_distance'   => $data['logistics']['estimated_distance'] ?? null,
                        'estimated_duration'   => $data['logistics']['estimated_duration'] ?? null,
                        'tracking_url'         => $data['logistics']['tracking_url'] ?? null,
                        'pickup_qr_url'        => $data['logistics']['pickup_qr_url'] ?? null,
                    ]);

                    return true;

                }
                catch (\Exception $e) {
                    Log::error("ShippingCourierOrder Submit failure: " . $e->getMessage());
                    return false;
                }

            }
            else {
                Log::warning("Courier Error", ['error' => $armadaResponse['message']]);
                return false;
            }
            // DB::commit();
            return true;
        } catch (\Exception $e) {
            // DB::rollBack();
            Log::error("Courier Requst failure: " . $e->getMessage());
            return false;
        }
    }

     /**
     * Will save courier properties
     *
     * @param Object $request
     * @return bool
     */
    public function updateShippingCourierOrders($request) {
        try {
            $payload = $request->all();

            Log::info("updateShippingCourierOrders method called:", [
                    'payload' => $payload
                ]);

            // Handle Armada's test payload specifically
        // if (isset($payload['code']) && $payload['code'] === 'TEST123') {
        //     Log::info("Armada Test Webhook (TEST123) acknowledged successfully.");
        //     return true; 
        // }

            ShippingCourierOrders::where('code', $payload['code'])->update([
                'status'             => $payload['status'],
                'amount'             => $payload['amount'],
                'delivery_fee'       => $payload['delivery_fee'],
                'estimated_distance' => $payload['logistics']['estimated_distance'] ?? null,
                'estimated_duration' => $payload['logistics']['estimated_duration'] ?? null,
                'tracking_url'       => $payload['logistics']['tracking_url'] ?? null,
                'pickup_qr_url'      => $payload['logistics']['pickup_qr_url'] ?? null,
            ]);

            $shippingCourierOrder =  ShippingCourierOrders::where('code', $payload['code'])->first();

            if (!$shippingCourierOrder) {
                Log::error("Courier Order Update: No record found for code " . $payload['code']);
                return false;
            }

            $order = Orders::where('id', $shippingCourierOrder->order_id)->first();

            if (!$order) {
                Log::error("Courier Order Update: No order found for id " . $shippingCourierOrder->order_id);
                return false;
            }

            //Send notification to admin
            $link = '/orders/order-details/' . $shippingCourierOrder->order_id;
            $message =  "Update received from courier, Order code " . $order->order_code;
            $data = [
                'message' => $message,
                'link' => $link
            ];

            $admins = User::where('user_type', config('tlecommercecore.user_type.admin'))->where('status', config('settings.general_status.active'))->get();

            if ($admins != null) {
                
                $notification = new CourierOrderUpdateNotification($data);

                Notification::send($admins, $notification);

            }

            return true;
        }
        catch (\Exception $e) {
            Log::error("Courier Order Update failure: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Will return courier properties
     *
     * @param Int $id
     * @return collection
     */
    public function getCarriersOrderUpdates($id)
    {
        Log::info("getCarriersOrderUpdates method called:", ['Order_id' => $id]);

        $shippingCourierOrder =  ShippingCourierOrders::where('order_id', $id)->first();

        if (!$shippingCourierOrder) {
            Log::warning("Courier order updates not found for Order ID: " . $id);
            // return [
            //     'shipping_courier_id' => $id
            // ];
            return null; 
        }

        return [
            'shipping_courier_id' => $shippingCourierOrder->id,
            'status' => $shippingCourierOrder->status,
            'driver_name' => $shippingCourierOrder->driver_name,
            'driver_phone' => $shippingCourierOrder->driver_phone,
        ];
    }
}

