<?php

namespace Theme\TLCommerce\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

class QuizFeature extends Model
{
    protected $table = 'quiz_features';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'layout_config',
        'is_active',
    ];

    protected $casts = [
        'layout_config' => 'array',
        'is_active' => 'boolean',
    ];

    public function translation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $row = $this->quiz_feature_translations->where('lang', $lang)->first();

        return $row != null ? $row->$field : $this->$field;
    }

    public function quiz_feature_translations()
    {
        return $this->hasMany(QuizFeatureTranslation::class, 'quiz_id');
    }

    public function submissions()
    {
        return $this->hasMany(QuizSubmission::class, 'quiz_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')->orderBy('sort_order');
    }
}
