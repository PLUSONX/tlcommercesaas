<?php

namespace Theme\TLCommerce\Http\Resources;

class QuizQuestionAnswersConfig
{
    public static function normalize(?array $answersConfig): array
    {
        $answersConfig = $answersConfig ?? [];
        $layout = $answersConfig['layout'] ?? 'list';
        if (!in_array($layout, ['list', 'grid'], true)) {
            $layout = 'list';
        }

        $useDefaults = !array_key_exists('use_quiz_defaults', $answersConfig)
            || (bool) $answersConfig['use_quiz_defaults'];

        $normalized = [
            'use_quiz_defaults' => $useDefaults,
            'layout' => $layout,
        ];

        if (!$useDefaults) {
            $styleKeys = array_merge(
                array_keys(QuizAnswerDefaultsConfig::defaultLayoutStyle('list')),
                ['grid_columns', 'image_size', 'image_position', 'hover_scale']
            );
            foreach ($styleKeys as $key) {
                if (array_key_exists($key, $answersConfig)) {
                    $normalized[$key] = $answersConfig[$key];
                }
            }
        }

        return $normalized;
    }

    public static function normalizeStored(?array $answersConfig, ?array $answersDefaults, ?array $legacyTheme = null): array
    {
        $answersConfig = self::normalize($answersConfig);
        $answersDefaults = QuizAnswerDefaultsConfig::normalize($answersDefaults, $legacyTheme);
        $layout = $answersConfig['layout'] ?? 'list';

        if ($answersConfig['use_quiz_defaults']) {
            return array_merge($answersConfig, [
                'resolved' => $answersDefaults[$layout] ?? QuizAnswerDefaultsConfig::defaultLayoutStyle($layout),
            ]);
        }

        $base = $answersDefaults[$layout] ?? QuizAnswerDefaultsConfig::defaultLayoutStyle($layout);
        $styleInput = array_merge($base, self::extractStyleOverrides($answersConfig));
        $resolved = QuizAnswerDefaultsConfig::normalizeLayoutStyle($styleInput, $layout);

        return array_merge($answersConfig, [
            'resolved' => $resolved,
        ]);
    }

    private static function extractStyleOverrides(array $answersConfig): array
    {
        $styleKeys = array_merge(
            array_keys(QuizAnswerDefaultsConfig::defaultLayoutStyle('list')),
            ['grid_columns', 'image_size', 'image_position', 'hover_scale']
        );

        $overrides = [];
        foreach ($styleKeys as $key) {
            if (array_key_exists($key, $answersConfig)) {
                $overrides[$key] = $answersConfig[$key];
            }
        }

        return $overrides;
    }

    public static function resolveForQuestion(?array $questionLayoutConfig, array $quizQuestionsConfig): array
    {
        $answersConfig = is_array($questionLayoutConfig['answers'] ?? null)
            ? $questionLayoutConfig['answers']
            : [];

        $legacyTheme = is_array($quizQuestionsConfig['theme'] ?? null)
            ? self::extractLegacyAnswerTheme($quizQuestionsConfig['theme'])
            : [];

        $answersDefaults = QuizAnswerDefaultsConfig::normalize(
            $quizQuestionsConfig['answers_defaults'] ?? null,
            $legacyTheme
        );

        return self::normalizeStored($answersConfig, $answersDefaults, $legacyTheme);
    }

    private static function extractLegacyAnswerTheme(array $theme): array
    {
        $keys = [
            'answer_gap', 'answer_border_width', 'answer_padding', 'answer_border_radius',
            'answer_show_input', 'answer_background', 'answer_border_color', 'answer_text_color',
            'answer_hover_background', 'answer_hover_border_color', 'answer_active_background',
            'answer_active_border_color', 'answer_active_text_color',
            'image_select_grid_columns', 'image_select_image_size', 'image_select_image_position',
        ];

        $legacy = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $theme)) {
                $legacy[$key] = $theme[$key];
            }
        }

        return $legacy;
    }
}
