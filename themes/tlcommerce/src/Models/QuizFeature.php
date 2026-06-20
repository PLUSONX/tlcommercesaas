<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizFeature extends Model
{
    protected $table = 'quiz_features';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'layout_config',
        'is_active',
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(QuizSubmission::class, 'quiz_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')->orderBy('sort_order');
    }
}
