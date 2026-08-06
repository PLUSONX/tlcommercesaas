<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $table = 'tl_com_analytics_events';

    protected $fillable = [
        'event_type',
        'product_id',
    ];
}
