<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestionTranslation extends Model
{
    protected $table = 'quiz_question_translations';

    protected $fillable = [
        'question_id',
        'lang',
        'question_text',
    ];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
