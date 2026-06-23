<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswerTranslation extends Model
{
    protected $table = 'quiz_answer_translations';

    protected $fillable = [
        'answer_id',
        'lang',
        'answer_text',
        'answer_description',
    ];

    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class, 'answer_id');
    }
}
