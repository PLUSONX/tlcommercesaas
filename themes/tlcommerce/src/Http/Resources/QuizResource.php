<?php

namespace Theme\TLCommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Session;
use Theme\TLCommerce\Services\QuizLayoutConfigTranslation;

class QuizResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = Session::get('api_locale', getDefaultLang());
        $questionCount = $this->questions->count();
        $baseLayout = is_array($this->layout_config) ? $this->layout_config : [];
        $overlay = QuizLayoutConfigTranslation::translationOverlay($this->resource, $locale);
        $mergedLayout = QuizLayoutConfigTranslation::mergeTranslatableText($baseLayout, $overlay);
        $layoutConfig = QuizIntroConfig::normalize($mergedLayout, $questionCount);
        $questionsConfig = $layoutConfig['questions'] ?? [];

        return [
            'id' => $this->id,
            'title' => $this->translation('title', $locale),
            'slug' => $this->slug,
            'description' => $this->translation('description', $locale),
            'layout_config' => $layoutConfig,
            'question_count' => $questionCount,
            'questions' => $this->questions->map(function ($question) use ($questionsConfig, $locale) {
                $questionLayout = is_array($question->layout_config) ? $question->layout_config : [];

                return [
                    'id' => $question->id,
                    'question_text' => $question->translation('question_text', $locale),
                    'question_type' => $question->question_type,
                    'is_required' => (bool) $question->is_required,
                    'sort_order' => (int) $question->sort_order,
                    'layout_config' => [
                        'answers' => QuizQuestionAnswersConfig::resolveForQuestion($questionLayout, $questionsConfig),
                    ],
                    'answers' => $question->answers->map(function ($answer) use ($locale) {
                        return [
                            'id' => $answer->id,
                            'answer_text' => $answer->translation('answer_text', $locale),
                            'answer_description' => $answer->translation('answer_description', $locale),
                            'answer_image' => $answer->answer_image
                                ? (str_starts_with($answer->answer_image, 'http')
                                    ? $answer->answer_image
                                    : getFilePath($answer->answer_image))
                                : null,
                            'sort_order' => (int) $answer->sort_order,
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
