<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $table = 'quiz_answers';

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'answer_text',
        'answer_image',
        'answer_description',
        'sort_order',
    ];

    public function translation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $row = $this->quiz_answer_translations->where('lang', $lang)->first();

        return $row != null ? $row->$field : $this->$field;
    }

    public function quiz_answer_translations()
    {
        return $this->hasMany(QuizAnswerTranslation::class, 'answer_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function productScores()
    {
        return $this->hasMany(QuizAnswerProductScore::class, 'answer_id');
    }
}
