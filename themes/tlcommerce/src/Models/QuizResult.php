<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Plugin\TlcommerceCore\Models\Product;

class QuizResult extends Model
{
    protected $table = 'quiz_results';

    const UPDATED_AT = null;

    protected $fillable = [
        'submission_id',
        'product_id',
        'total_score',
        'rank',
    ];

    protected $casts = [
        'total_score' => 'float',
        'rank' => 'integer',
    ];

    public function submission()
    {
        return $this->belongsTo(QuizSubmission::class, 'submission_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
