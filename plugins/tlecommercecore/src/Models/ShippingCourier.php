<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCourier extends Model
{
    protected $table = "tl_com_shipping_courier";

    /**
     * Get the API properties associated with the courier.
     */
    public function properties()
    {
        return $this->hasOne(ShippingCourierProperties::class, 'shipping_courier_id');
    }
}
