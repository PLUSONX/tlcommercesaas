<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;
use Plugin\TlcommerceCore\Models\Customers;
use Plugin\TlcommerceCore\Models\GuestCustomers;

class PaymentTransaction extends Model
{
    protected $table = "tl_com_payment_transactions";

    protected $fillable = [
        'payment_method',
        'paid_amount',
        'payment_for',
        'payment_info',
        'guest_customer',
        'customer_id',
        'user_id',
        'status'
    ];

    public function customer_info()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }
    public function guest_customer_info()
    {
        return $this->belongsTo(GuestCustomers::class, 'guest_customer', 'id');
    }
}
