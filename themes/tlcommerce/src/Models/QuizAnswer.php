<?php

namespace Theme\TLCommerce\Models;

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

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function productScores()
    {
        return $this->hasMany(QuizAnswerProductScore::class, 'answer_id');
    }
}
