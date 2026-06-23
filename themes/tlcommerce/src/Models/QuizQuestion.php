<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Support\Facades\App;
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

    public function translation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $row = $this->quiz_question_translations->where('lang', $lang)->first();

        return $row != null ? $row->$field : $this->$field;
    }

    public function quiz_question_translations()
    {
        return $this->hasMany(QuizQuestionTranslation::class, 'question_id');
    }

    public function quiz()
    {
        return $this->belongsTo(QuizFeature::class, 'quiz_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id')->orderBy('sort_order');
    }
}
