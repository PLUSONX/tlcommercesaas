<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    public $timestamps = false;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'is_required',
        'sort_order',
        'layout_config',
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_required' => 'boolean',
    ];

    public function quiz()
    {
        return $this->belongsTo(QuizFeature::class, 'quiz_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id')->orderBy('sort_order');
    }
}
