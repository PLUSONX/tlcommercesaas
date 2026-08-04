<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryScheduleSlot extends Model
{
    protected $table = 'tl_com_delivery_schedule_slots';

    protected $fillable = [
        'schedule_date',
        'start_time',
        'end_time',
        'max_orders',
        'status',
        'label',
        'recurrence_group_id',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];
}
