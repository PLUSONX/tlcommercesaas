<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCourierOrders extends Model
{
    protected $table = "tl_com_shipping_courier_orders";

    protected $fillable = [
        'order_id',
        'shipping_courier_id',
        'code',
        'status',
        'amount',
        'delivery_fee',
        'currency',
        'driver_name',
        'driver_phone',
        'driver_latitude',
        'driver_longitude',
        'estimated_distance',
        'estimated_duration',
        'tracking_url',
        'pickup_qr_url',
    ];

    protected $casts = [
        'amount'             => 'float',
        'delivery_fee'       => 'float',
        'driver_latitude'    => 'float',
        'driver_longitude'   => 'float',
        'estimated_distance' => 'integer',
        'estimated_duration' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function courier()
    {
        return $this->belongsTo(ShippingCourier::class, 'shipping_courier_id');
    }
    
}