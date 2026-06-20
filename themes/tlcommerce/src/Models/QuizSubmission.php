<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    protected $table = 'quiz_submissions';

    public $timestamps = false;

    protected $fillable = [
        'quiz_id',
        'customer_id',
        'guest_customer_id',
        'session_token',
        'submitted_at',
    ];

    public function quiz()
    {
        return $this->belongsTo(QuizFeature::class, 'quiz_id');
    }

    public function submissionAnswers()
    {
        return $this->hasMany(QuizSubmissionAnswer::class, 'submission_id');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class, 'submission_id')->orderBy('rank');
    }
}
