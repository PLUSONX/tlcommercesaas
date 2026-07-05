<?php

namespace Theme\TLCommerce\Http\Resources;

class QuizQuestionsConfig
{
    public static function normalize(?array $questionsConfig): array
    {
        $questionsConfig = $questionsConfig ?? [];
        $theme = is_array($questionsConfig['theme'] ?? null) ? $questionsConfig['theme'] : [];
        $legacyTheme = self::extractLegacyAnswerKeys($theme);

        return [
            'theme' => self::normalizeTheme($theme),
            'answers_defaults' => QuizAnswerDefaultsConfig::normalize(
                is_array($questionsConfig['answers_defaults'] ?? null) ? $questionsConfig['answers_defaults'] : null,
                $legacyTheme
            ),
        ];
    }

    public static function defaultTheme(): array
    {
        return [
            'background_type' => 'solid',
            'background_color' => '#f7f8fa',
            'background_gradient_center' => '#ffffff',
            'background_gradient_edge' => '#050a07',
            'fill_viewport' => true,
            'content_max_width' => 640,
            'alignment' => 'left',
            'card_enabled' => true,
            'card_background' => '#ffffff',
            'card_border_color' => '#e5e7eb',
            'card_border_radius' => 12,
            'card_padding' => 24,
            'card_shadow' => true,
            'question_color' => '#111111',
            'required_color' => '#dc3545',
            'question_background' => 'transparent',
            'question_alignment' => '',
            'question_padding' => 0,
            'question_margin' => 16,
            'progress_track_color' => '#e9ecef',
            'progress_bar_color' => '#ff5a1f',
            'progress_height' => 6,
            'progress_label_color' => '#6b7280',
            'progress_label_background' => 'transparent',
            'progress_label_alignment' => 'left',
            'show_progress' => true,
            'show_back_button' => true,
            'show_next_button' => true,
            'btn_back_label' => 'Back',
            'btn_next_label' => 'Next',
            'btn_submit_label' => 'See Results',
            'btn_back_color' => '#111111',
            'btn_back_text_color' => '#111111',
            'btn_next_color' => '#ff5a1f',
            'btn_next_text_color' => '#ffffff',
            'btn_border_radius' => 4,
            'error_color' => '#dc3545',
        ];
    }

    public static function normalizeTheme(array $theme): array
    {
        $defaults = self::defaultTheme();
        $bgType = $theme['background_type'] ?? $defaults['background_type'];
        if (!in_array($bgType, ['solid', 'radial_gradient'], true)) {
            $bgType = 'solid';
        }

        $alignment = $theme['alignment'] ?? $defaults['alignment'];
        if (!in_array($alignment, ['left', 'center', 'right'], true)) {
            $alignment = 'left';
        }

        $progressLabelAlignment = $theme['progress_label_alignment'] ?? $defaults['progress_label_alignment'];
        if (!in_array($progressLabelAlignment, ['left', 'center', 'right'], true)) {
            $progressLabelAlignment = 'left';
        }

        $questionAlignment = $theme['question_alignment'] ?? $defaults['question_alignment'];
        if ($questionAlignment !== '' && !in_array($questionAlignment, ['left', 'center', 'right'], true)) {
            $questionAlignment = '';
        }

        return [
            'background_type' => $bgType,
            'background_color' => self::sanitizeHexColor($theme['background_color'] ?? $defaults['background_color']),
            'background_gradient_center' => self::sanitizeHexColor($theme['background_gradient_center'] ?? $defaults['background_gradient_center']),
            'background_gradient_edge' => self::sanitizeHexColor($theme['background_gradient_edge'] ?? $defaults['background_gradient_edge']),
            'fill_viewport' => array_key_exists('fill_viewport', $theme) ? (bool) $theme['fill_viewport'] : $defaults['fill_viewport'],
            'content_max_width' => max(400, min(900, (int) ($theme['content_max_width'] ?? $defaults['content_max_width']))),
            'alignment' => $alignment,
            'card_enabled' => array_key_exists('card_enabled', $theme) ? (bool) $theme['card_enabled'] : $defaults['card_enabled'],
            'card_background' => self::sanitizeHexColor($theme['card_background'] ?? $defaults['card_background']),
            'card_border_color' => self::sanitizeHexColor($theme['card_border_color'] ?? $defaults['card_border_color']),
            'card_border_radius' => max(0, min(32, (int) ($theme['card_border_radius'] ?? $defaults['card_border_radius']))),
            'card_padding' => max(0, min(64, (int) ($theme['card_padding'] ?? $defaults['card_padding']))),
            'card_shadow' => array_key_exists('card_shadow', $theme) ? (bool) $theme['card_shadow'] : $defaults['card_shadow'],
            'question_color' => self::sanitizeHexColor($theme['question_color'] ?? $defaults['question_color']),
            'required_color' => self::sanitizeHexColor($theme['required_color'] ?? $defaults['required_color']),
            'question_background' => self::sanitizeOptionalColor($theme['question_background'] ?? $defaults['question_background']),
            'question_alignment' => $questionAlignment,
            'question_padding' => max(0, min(64, (int) ($theme['question_padding'] ?? $defaults['question_padding']))),
            'question_margin' => max(0, min(64, (int) ($theme['question_margin'] ?? $defaults['question_margin']))),
            'progress_track_color' => self::sanitizeHexColor($theme['progress_track_color'] ?? $defaults['progress_track_color']),
            'progress_bar_color' => self::sanitizeHexColor($theme['progress_bar_color'] ?? $defaults['progress_bar_color']),
            'progress_height' => max(4, min(16, (int) ($theme['progress_height'] ?? $defaults['progress_height']))),
            'progress_label_color' => self::sanitizeHexColor($theme['progress_label_color'] ?? $defaults['progress_label_color']),
            'progress_label_background' => self::sanitizeOptionalColor($theme['progress_label_background'] ?? $defaults['progress_label_background']),
            'progress_label_alignment' => $progressLabelAlignment,
            'show_progress' => array_key_exists('show_progress', $theme) ? (bool) $theme['show_progress'] : $defaults['show_progress'],
            'show_back_button' => array_key_exists('show_back_button', $theme) ? (bool) $theme['show_back_button'] : $defaults['show_back_button'],
            'show_next_button' => array_key_exists('show_next_button', $theme) ? (bool) $theme['show_next_button'] : $defaults['show_next_button'],
            'btn_back_label' => self::sanitizeLabel($theme['btn_back_label'] ?? $defaults['btn_back_label'], $defaults['btn_back_label']),
            'btn_next_label' => self::sanitizeLabel($theme['btn_next_label'] ?? $defaults['btn_next_label'], $defaults['btn_next_label']),
            'btn_submit_label' => self::sanitizeLabel($theme['btn_submit_label'] ?? $defaults['btn_submit_label'], $defaults['btn_submit_label']),
            'btn_back_color' => self::sanitizeHexColor($theme['btn_back_color'] ?? $defaults['btn_back_color']),
            'btn_back_text_color' => self::sanitizeHexColor($theme['btn_back_text_color'] ?? $defaults['btn_back_text_color']),
            'btn_next_color' => self::sanitizeHexColor($theme['btn_next_color'] ?? $defaults['btn_next_color']),
            'btn_next_text_color' => self::sanitizeHexColor($theme['btn_next_text_color'] ?? $defaults['btn_next_text_color']),
            'btn_border_radius' => max(0, min(32, (int) ($theme['btn_border_radius'] ?? $defaults['btn_border_radius']))),
            'error_color' => self::sanitizeHexColor($theme['error_color'] ?? $defaults['error_color']),
        ];
    }

    private static function extractLegacyAnswerKeys(array $theme): array
    {
        $legacy = [];
        $answerKeys = [
            'answer_gap', 'answer_border_width', 'answer_padding', 'answer_border_radius',
            'answer_show_input', 'answer_background', 'answer_border_color', 'answer_text_color',
            'answer_hover_background', 'answer_hover_border_color', 'answer_active_background',
            'answer_active_border_color', 'answer_active_text_color',
            'image_select_grid_columns', 'image_select_image_size', 'image_select_image_position',
        ];

        foreach ($answerKeys as $key) {
            if (array_key_exists($key, $theme)) {
                $legacy[$key] = $theme[$key];
            }
        }

        return $legacy;
    }

    private static function sanitizeLabel(?string $value, string $fallback): string
    {
        $value = trim(strip_tags((string) $value));
        if ($value === '') {
            return $fallback;
        }

        return mb_substr($value, 0, 40);
    }

    private static function sanitizeHexColor(?string $value): string
    {
        $value = trim((string) $value);
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
            return $value;
        }

        return '#111111';
    }

    private static function sanitizeOptionalColor(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '' || strtolower($value) === 'transparent') {
            return 'transparent';
        }

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
            return $value;
        }

        return 'transparent';
    }
}
