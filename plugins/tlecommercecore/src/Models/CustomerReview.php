<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    protected $table = "tl_com_product_reviews";

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'order_id',
        'review',
        'rating',
        'images',
        'status',
    ];

    protected $casts = [
        'rating' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
