<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCustomers extends Model
{
    protected $table = "tl_com_guest_customer";

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
