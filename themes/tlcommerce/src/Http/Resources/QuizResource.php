<?php

namespace Theme\TLCommerce\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray($request)
    {
        $questionCount = $this->questions->count();
        $layoutConfig = QuizIntroConfig::normalize($this->layout_config, $questionCount);
        $questionsConfig = $layoutConfig['questions'] ?? [];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'layout_config' => $layoutConfig,
            'question_count' => $questionCount,
            'questions' => $this->questions->map(function ($question) use ($questionsConfig) {
                $questionLayout = is_array($question->layout_config) ? $question->layout_config : [];

                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'is_required' => (bool) $question->is_required,
                    'sort_order' => (int) $question->sort_order,
                    'layout_config' => [
                        'answers' => QuizQuestionAnswersConfig::resolveForQuestion($questionLayout, $questionsConfig),
                    ],
                    'answers' => $question->answers->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'answer_text' => $answer->answer_text,
                            'answer_description' => $answer->answer_description,
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
