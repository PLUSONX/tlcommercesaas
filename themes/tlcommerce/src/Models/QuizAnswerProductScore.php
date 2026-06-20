<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Plugin\TlcommerceCore\Models\Product;

class QuizAnswerProductScore extends Model
{
    protected $table = 'quiz_answer_product_scores';

    public $timestamps = false;

    protected $fillable = [
        'answer_id',
        'product_id',
        'score',
    ];

    protected $casts = [
        'score' => 'float',
    ];

    public function answer()
    {
        return $this->belongsTo(QuizAnswer::class, 'answer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
