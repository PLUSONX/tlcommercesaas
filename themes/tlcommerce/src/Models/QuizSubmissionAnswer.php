<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSubmissionAnswer extends Model
{
    protected $table = 'quiz_submission_answers';

    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'question_id',
        'answer_id',
    ];

    public function submission()
    {
        return $this->belongsTo(QuizSubmission::class, 'submission_id');
    }

    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class, 'answer_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
