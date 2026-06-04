<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    protected $table = "tl_com_feedback";

    public $timestamps = false;

    protected $fillable = [
        'name',
        'satisfaction_rating',
        'phone',
        'comment'
    ];

    protected $casts = [
        'satisfaction_rating' => 'integer',
        'created_at' => 'datetime',
    ];
}