<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class StudioBookingSlot extends Model
{
    protected $table = 'tl_com_studio_booking_slots';

    protected $fillable = [
        'schedule_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'schedule_date' => 'date:Y-m-d',
        'status' => 'integer',
    ];
}
