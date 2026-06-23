<?php

namespace Theme\TLCommerce\Repositories;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Theme\TLCommerce\Models\QuizAnswer;
use Theme\TLCommerce\Models\QuizFeature;
use Theme\TLCommerce\Models\QuizQuestion;
use Theme\TLCommerce\Models\QuizAnswerProductScore;
use Theme\TLCommerce\Models\QuizFeatureTranslation;
use Theme\TLCommerce\Models\QuizQuestionTranslation;
use Theme\TLCommerce\Models\QuizAnswerTranslation;
use Theme\TLCommerce\Services\QuizLayoutConfigTranslation;
use Theme\TLCommerce\Http\Resources\QuizAnswerDefaultsConfig;
use Theme\TLCommerce\Http\Resources\QuizQuestionAnswersConfig;
use Theme\TLCommerce\Http\Resources\QuizResultsConfig;

class QuizRepository
{
    public function listQuizzes()
    {
        return QuizFeature::withCount('questions')
            ->orderByDesc('id')
            ->get();
    }

    public function findQuiz($id)
    {
        return QuizFeature::with([
            'quiz_feature_translations',
            'questions.quiz_question_translations',
            'questions.answers.quiz_answer_translations',
        ])->findOrFail($id);
    }

    public function storeQuiz(Request $request)
    {
        $slug = $this->uniqueSlug($request->input('slug') ?: $request->input('title'));

        $quiz = new QuizFeature();
        $quiz->title = $request->input('title');
        $quiz->slug = $slug;
        $quiz->description = $request->input('description');
        $quiz->layout_config = $this->parseLayoutConfig($request);
        $quiz->is_active = $request->has('is_active') ? 1 : 0;
        $quiz->save();

        return $quiz;
    }

    public function updateQuiz(Request $request)
    {
        $quiz = QuizFeature::findOrFail($request->input('id'));

        if ($this->isNonDefaultLang($request)) {
            $translation = QuizFeatureTranslation::firstOrNew([
                'quiz_id' => $quiz->id,
                'lang' => $request->input('lang'),
            ]);
            $translation->title = $request->input('title');
            $translation->description = $request->input('description');
            $translation->layout_config = QuizLayoutConfigTranslation::extractTranslatableText(
                $this->translatableTextSourceFromRequest($request)
            );
            $translation->save();

            return $quiz;
        }

        $quiz->title = $request->input('title');
        $quiz->slug = $this->uniqueSlug(
            $request->input('slug') ?: $request->input('title'),
            $quiz->id
        );
        $quiz->description = $request->input('description');
        $quiz->layout_config = $this->parseLayoutConfig($request, $quiz->layout_config ?? []);
        $quiz->is_active = $request->has('is_active') ? 1 : 0;
        $quiz->save();

        return $quiz;
    }

    public function deleteQuiz($id)
    {
        $quiz = QuizFeature::with(['questions.answers.productScores'])->findOrFail($id);

        QuizFeatureTranslation::where('quiz_id', $quiz->id)->delete();

        foreach ($quiz->questions as $question) {
            QuizQuestionTranslation::where('question_id', $question->id)->delete();

            foreach ($question->answers as $answer) {
                QuizAnswerTranslation::where('answer_id', $answer->id)->delete();
                QuizAnswerProductScore::where('answer_id', $answer->id)->delete();
                $answer->delete();
            }
            $question->delete();
        }

        return $quiz->delete();
    }

    public function updateQuizStatus($id)
    {
        $quiz = QuizFeature::findOrFail($id);
        $quiz->is_active = $quiz->is_active ? 0 : 1;
        $quiz->save();

        return true;
    }

    public function storeQuestion(Request $request)
    {
        $maxOrder = QuizQuestion::where('quiz_id', $request->input('quiz_id'))->max('sort_order');

        $question = new QuizQuestion();
        $question->quiz_id = $request->input('quiz_id');
        $question->question_text = $request->input('question_text');
        $question->question_type = $request->input('question_type');
        $question->is_required = $request->has('is_required') ? 1 : 0;
        $question->sort_order = ($maxOrder ?? -1) + 1;
        $question->layout_config = $request->input('layout_config')
            ? json_decode($request->input('layout_config'), true)
            : null;
        $question->save();

        return $question;
    }

    public function updateQuestion(Request $request)
    {
        $question = QuizQuestion::findOrFail($request->input('id'));

        if ($this->isNonDefaultLang($request)) {
            $translation = QuizQuestionTranslation::firstOrNew([
                'question_id' => $question->id,
                'lang' => $request->input('lang'),
            ]);
            $translation->question_text = $request->input('question_text');
            $translation->save();

            return $question;
        }

        $question->question_text = $request->input('question_text');
        $question->question_type = $request->input('question_type');
        $question->is_required = $request->has('is_required') ? 1 : 0;
        $question->layout_config = $request->input('layout_config')
            ? json_decode($request->input('layout_config'), true)
            : null;
        $question->save();

        return $question;
    }

    public function deleteQuestion($id)
    {
        $question = QuizQuestion::with('answers.productScores')->findOrFail($id);

        QuizQuestionTranslation::where('question_id', $question->id)->delete();

        foreach ($question->answers as $answer) {
            QuizAnswerTranslation::where('answer_id', $answer->id)->delete();
            QuizAnswerProductScore::where('answer_id', $answer->id)->delete();
            $answer->delete();
        }

        return $question->delete();
    }

    public function reorderQuestions(Request $request)
    {
        foreach ($request->input('order', []) as $index => $questionId) {
            QuizQuestion::where('id', $questionId)->update(['sort_order' => $index]);
        }

        return true;
    }

    public function storeAnswer(Request $request)
    {
        $maxOrder = QuizAnswer::where('question_id', $request->input('question_id'))->max('sort_order');

        $answer = new QuizAnswer();
        $answer->question_id = $request->input('question_id');
        $answer->answer_text = $request->input('answer_text');
        $answer->answer_image = $request->input('answer_image');
        $answer->answer_description = $request->input('answer_description');
        $answer->sort_order = ($maxOrder ?? -1) + 1;
        $answer->save();

        return $answer;
    }

    public function updateAnswer(Request $request)
    {
        $answer = QuizAnswer::findOrFail($request->input('id'));

        if ($this->isNonDefaultLang($request)) {
            $translation = QuizAnswerTranslation::firstOrNew([
                'answer_id' => $answer->id,
                'lang' => $request->input('lang'),
            ]);
            $translation->answer_text = $request->input('answer_text');
            $translation->answer_description = $request->input('answer_description');
            $translation->save();

            return $answer;
        }

        $answer->answer_text = $request->input('answer_text');
        $answer->answer_image = $request->input('edit_answer_image', $request->input('answer_image'));
        $answer->answer_description = $request->input('answer_description');
        $answer->save();

        return $answer;
    }

    public function deleteAnswer($id)
    {
        QuizAnswerTranslation::where('answer_id', $id)->delete();
        QuizAnswerProductScore::where('answer_id', $id)->delete();

        return QuizAnswer::findOrFail($id)->delete();
    }

    public function saveProductScores(Request $request)
    {
        $answerId = $request->input('answer_id');
        $scores = $request->input('scores', []);

        QuizAnswer::findOrFail($answerId);

        $submittedProductIds = [];

        foreach ($scores as $row) {
            $productId = $row['product_id'] ?? null;
            $score = $row['score'] ?? 0;

            if (!$productId) {
                continue;
            }

            $submittedProductIds[] = $productId;

            QuizAnswerProductScore::updateOrCreate(
                [
                    'answer_id' => $answerId,
                    'product_id' => $productId,
                ],
                ['score' => $score]
            );
        }

        if (!empty($submittedProductIds)) {
            QuizAnswerProductScore::where('answer_id', $answerId)
                ->whereNotIn('product_id', $submittedProductIds)
                ->delete();
        } else {
            QuizAnswerProductScore::where('answer_id', $answerId)->delete();
        }

        return true;
    }

    public function updateAnswerDefaults($quizId, array $defaults)
    {
        $quiz = QuizFeature::findOrFail($quizId);
        $config = is_array($quiz->layout_config) ? $quiz->layout_config : [];
        $config['questions'] = is_array($config['questions'] ?? null) ? $config['questions'] : [];
        $config['questions']['answers_defaults'] = QuizAnswerDefaultsConfig::normalize($defaults);
        $quiz->layout_config = $config;
        $quiz->save();

        return $quiz;
    }

    public function updateQuestionAnswerLayout($questionId, array $answersConfig)
    {
        $question = QuizQuestion::findOrFail($questionId);
        $layoutConfig = is_array($question->layout_config) ? $question->layout_config : [];
        $normalized = QuizQuestionAnswersConfig::normalize($answersConfig);
        unset($normalized['resolved']);
        $layoutConfig['answers'] = $normalized;
        $question->layout_config = $layoutConfig;
        $question->save();

        return $question;
    }

    private function uniqueSlug($value, $ignoreId = null)
    {
        $slug = Str::slug($value);
        $original = $slug;
        $counter = 1;

        while (
            QuizFeature::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function parseLayoutConfig(Request $request, ?array $existingConfig = null)
    {
        $config = [
            'step_style' => $request->input('step_style', 'wizard'),
            'top_n' => max(1, (int) $request->input('top_n', 3)),
            'skip_intro' => $request->has('skip_intro'),
            'intro' => [
                'layout' => $request->input('intro_layout', 'minimal'),
                'alignment' => $request->input('intro_alignment', 'center'),
                'subtitle' => $request->input('intro_subtitle'),
                'cta_text' => $request->input('intro_cta_text'),
                'secondary_link_text' => $request->input('intro_secondary_link_text'),
                'secondary_link_url' => $request->input('intro_secondary_link_url'),
                'show_question_count' => $request->has('intro_show_question_count'),
                'show_estimated_time' => $request->has('intro_show_estimated_time'),
                'estimated_minutes' => max(1, (int) $request->input('intro_estimated_minutes', 2)),
                'hero_image_desktop' => $request->input('intro_hero_image_desktop'),
                'hero_image_mobile' => $request->input('intro_hero_image_mobile'),
                'background_color' => $request->input('intro_background_color', '#ffffff'),
                'text_color' => $request->input('intro_text_color', '#111111'),
                'button_color' => $request->input('intro_button_color', '#ff5a1f'),
                'button_text_color' => $request->input('intro_button_text_color', '#ffffff'),
                'overlay_color' => $request->input('intro_overlay_color', '#000000'),
                'overlay_opacity' => min(100, max(0, (int) $request->input('intro_overlay_opacity', 40))),
            ],
        ];

        $isCustomStack = $request->input('intro_layout') === 'custom_stack';
        $existingIntro = is_array($existingConfig) ? ($existingConfig['intro'] ?? []) : [];

        $blocks = $this->decodeRequestJson($request, 'intro_blocks_json');
        if (is_array($blocks)) {
            $config['intro']['blocks'] = $blocks;
        } elseif ($isCustomStack && !empty($existingIntro['blocks']) && is_array($existingIntro['blocks'])) {
            $config['intro']['blocks'] = $existingIntro['blocks'];
        }

        $theme = $this->decodeRequestJson($request, 'intro_theme_json');
        if (is_array($theme)) {
            $config['intro']['theme'] = $theme;
        } elseif ($isCustomStack && !empty($existingIntro['theme']) && is_array($existingIntro['theme'])) {
            $config['intro']['theme'] = $existingIntro['theme'];
        }

        $existingQuestions = is_array($existingConfig) ? ($existingConfig['questions'] ?? []) : [];
        $questionsTheme = $this->decodeRequestJson($request, 'questions_theme_json');
        $config['questions'] = is_array($existingQuestions) ? $existingQuestions : [];
        if (is_array($questionsTheme)) {
            $config['questions']['theme'] = $questionsTheme;
        } elseif (!empty($existingQuestions['theme']) && is_array($existingQuestions['theme'])) {
            $config['questions']['theme'] = $existingQuestions['theme'];
        }

        $existingResults = is_array($existingConfig) ? ($existingConfig['results'] ?? null) : null;
        $resultsLayoutMode = $request->input('results_layout_mode');
        $resultsBlocks = $this->decodeRequestJson($request, 'results_blocks_json');
        $resultsTheme = $this->decodeRequestJson($request, 'results_theme_json');
        $resultsActions = $this->decodeRequestJson($request, 'results_actions_json');
        $resultsProductGrid = $this->decodeRequestJson($request, 'results_product_grid_json');

        if ($resultsLayoutMode !== null || $resultsBlocks !== null || $resultsTheme !== null
            || $resultsActions !== null || $resultsProductGrid !== null || is_array($existingResults)) {
            $config['results'] = is_array($existingResults) ? $existingResults : [];

            if ($resultsLayoutMode !== null) {
                $config['results']['layout_mode'] = $resultsLayoutMode;
            }

            if (is_array($resultsBlocks)) {
                $config['results']['blocks'] = $resultsBlocks;
            } elseif (is_array($existingResults) && !empty($existingResults['blocks'])) {
                $config['results']['blocks'] = $existingResults['blocks'];
            }

            if (is_array($resultsTheme)) {
                $config['results']['theme'] = $resultsTheme;
            } elseif (is_array($existingResults) && !empty($existingResults['theme'])) {
                $config['results']['theme'] = $existingResults['theme'];
            }

            if (is_array($resultsActions)) {
                $config['results']['actions'] = QuizResultsConfig::normalizeActions($resultsActions);
            } elseif (is_array($existingResults) && !empty($existingResults['actions'])) {
                $config['results']['actions'] = $existingResults['actions'];
            }

            if (is_array($resultsProductGrid)) {
                $config['results']['product_grid'] = $resultsProductGrid;
            } elseif (is_array($existingResults) && !empty($existingResults['product_grid'])) {
                $config['results']['product_grid'] = $existingResults['product_grid'];
            }
        }

        if ($request->filled('layout_config_json')) {
            $decoded = json_decode($request->input('layout_config_json'), true);
            if (is_array($decoded)) {
                $config = array_merge($config, $decoded);
            }
        }

        return $config;
    }

    private function decodeRequestJson(Request $request, string $key): ?array
    {
        if (!$request->filled($key)) {
            return null;
        }

        $raw = $request->input($key);
        $decoded = json_decode($raw, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        $decoded = json_decode(html_entity_decode($raw, ENT_QUOTES, 'UTF-8'), true);

        return is_array($decoded) ? $decoded : null;
    }

    private function isNonDefaultLang(Request $request): bool
    {
        $lang = $request->input('lang');

        return $lang && $lang !== getDefaultLang();
    }

    /**
     * @return array<string, mixed>
     */
    private function translatableTextSourceFromRequest(Request $request): array
    {
        return [
            'intro' => [
                'subtitle' => $request->input('intro_subtitle'),
                'cta_text' => $request->input('intro_cta_text'),
                'secondary_link_text' => $request->input('intro_secondary_link_text'),
                'blocks' => $this->decodeRequestJson($request, 'intro_blocks_json'),
            ],
            'results' => [
                'blocks' => $this->decodeRequestJson($request, 'results_blocks_json'),
                'actions' => $this->decodeRequestJson($request, 'results_actions_json'),
                'product_grid' => $this->decodeRequestJson($request, 'results_product_grid_json'),
            ],
        ];
    }
}
