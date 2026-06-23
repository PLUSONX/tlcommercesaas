<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class QuizFeatureTranslation extends Model
{
    protected $table = 'quiz_feature_translations';

    protected $fillable = [
        'quiz_id',
        'lang',
        'title',
        'description',
        'layout_config',
    ];

    protected $casts = [
        'layout_config' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(QuizFeature::class, 'quiz_id');
    }
}
