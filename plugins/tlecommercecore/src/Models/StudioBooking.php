<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class StudioBooking extends Model
{
    protected $table = 'tl_com_studio_bookings';

    protected $fillable = [
        'booking_token',
        'customer_id',
        'order_id',
        'schedule_date',
        'start_time',
        'end_time',
        'duration_minutes',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'schedule_date' => 'date:Y-m-d',
        'customer_id' => 'integer',
        'order_id' => 'integer',
        'duration_minutes' => 'integer',
        'expires_at' => 'datetime',
    ];
}
