<?php

namespace Theme\TLCommerce\Services;

use Theme\TLCommerce\Models\QuizFeature;

class QuizLayoutConfigTranslation
{
    /**
     * @param  array<string, mixed>  $source
     * @return array<string, mixed>
     */
    public static function extractTranslatableText(array $source): array
    {
        $result = [];

        $intro = $source['intro'] ?? null;
        if (is_array($intro)) {
            $introExtract = [];

            foreach (['subtitle', 'cta_text', 'secondary_link_text'] as $key) {
                if (array_key_exists($key, $intro)) {
                    $introExtract[$key] = $intro[$key];
                }
            }

            if (!empty($intro['blocks']) && is_array($intro['blocks'])) {
                $blocks = self::extractBlockText($intro['blocks']);
                if (!empty($blocks)) {
                    $introExtract['blocks'] = $blocks;
                }
            }

            if (!empty($introExtract)) {
                $result['intro'] = $introExtract;
            }
        }

        $results = $source['results'] ?? null;
        if (is_array($results)) {
            $resultsExtract = [];

            if (!empty($results['product_grid']) && is_array($results['product_grid'])) {
                $grid = [];
                foreach (['heading', 'subheading'] as $key) {
                    if (array_key_exists($key, $results['product_grid'])) {
                        $grid[$key] = $results['product_grid'][$key];
                    }
                }
                if (!empty($grid)) {
                    $resultsExtract['product_grid'] = $grid;
                }
            }

            if (!empty($results['blocks']) && is_array($results['blocks'])) {
                $blocks = self::extractBlockText($results['blocks']);
                if (!empty($blocks)) {
                    $resultsExtract['blocks'] = $blocks;
                }
            }

            if (!empty($results['actions']) && is_array($results['actions'])) {
                $actions = [];
                foreach ($results['actions'] as $action) {
                    if (!is_array($action)) {
                        continue;
                    }
                    $id = $action['id'] ?? null;
                    if (!$id) {
                        continue;
                    }
                    $actions[] = [
                        'id' => (string) $id,
                        'label' => $action['label'] ?? '',
                    ];
                }
                if (!empty($actions)) {
                    $resultsExtract['actions'] = $actions;
                }
            }

            if (!empty($results['product_profiles']) && is_array($results['product_profiles'])) {
                $profiles = [];
                foreach ($results['product_profiles'] as $profile) {
                    if (!is_array($profile)) {
                        continue;
                    }
                    $productId = (int) ($profile['product_id'] ?? 0);
                    if ($productId <= 0) {
                        continue;
                    }
                    $item = ['product_id' => $productId];
                    if (array_key_exists('tagline', $profile)) {
                        $item['tagline'] = $profile['tagline'];
                    }
                    if (array_key_exists('description', $profile)) {
                        $item['description'] = $profile['description'];
                    }
                    if (array_key_exists('quote', $profile)) {
                        $item['quote'] = $profile['quote'];
                    }
                    $profiles[] = $item;
                }
                if (!empty($profiles)) {
                    $resultsExtract['product_profiles'] = $profiles;
                }
            }

            if (!empty($resultsExtract)) {
                $result['results'] = $resultsExtract;
            }
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>|null  $base
     * @param  array<string, mixed>|null  $overlay
     * @return array<string, mixed>
     */
    public static function mergeTranslatableText(?array $base, ?array $overlay): array
    {
        $base = is_array($base) ? $base : [];

        if (empty($overlay) || !is_array($overlay)) {
            return $base;
        }

        if (!empty($overlay['intro']) && is_array($overlay['intro'])) {
            $base['intro'] = is_array($base['intro'] ?? null) ? $base['intro'] : [];

            foreach (['subtitle', 'cta_text', 'secondary_link_text'] as $key) {
                if (array_key_exists($key, $overlay['intro'])) {
                    $base['intro'][$key] = $overlay['intro'][$key];
                }
            }

            if (!empty($overlay['intro']['blocks'])) {
                $base['intro']['blocks'] = self::mergeBlocksText(
                    is_array($base['intro']['blocks'] ?? null) ? $base['intro']['blocks'] : [],
                    $overlay['intro']['blocks']
                );
            }
        }

        if (!empty($overlay['results']) && is_array($overlay['results'])) {
            $base['results'] = is_array($base['results'] ?? null) ? $base['results'] : [];

            if (!empty($overlay['results']['product_grid']) && is_array($overlay['results']['product_grid'])) {
                $base['results']['product_grid'] = is_array($base['results']['product_grid'] ?? null)
                    ? $base['results']['product_grid']
                    : [];

                foreach (['heading', 'subheading'] as $key) {
                    if (array_key_exists($key, $overlay['results']['product_grid'])) {
                        $base['results']['product_grid'][$key] = $overlay['results']['product_grid'][$key];
                    }
                }
            }

            if (!empty($overlay['results']['blocks'])) {
                $base['results']['blocks'] = self::mergeBlocksText(
                    is_array($base['results']['blocks'] ?? null) ? $base['results']['blocks'] : [],
                    $overlay['results']['blocks']
                );
            }

            if (!empty($overlay['results']['actions'])) {
                $base['results']['actions'] = self::mergeActionsText(
                    is_array($base['results']['actions'] ?? null) ? $base['results']['actions'] : [],
                    $overlay['results']['actions']
                );
            }

            if (!empty($overlay['results']['product_profiles'])) {
                $base['results']['product_profiles'] = self::mergeProductProfileText(
                    is_array($base['results']['product_profiles'] ?? null) ? $base['results']['product_profiles'] : [],
                    $overlay['results']['product_profiles']
                );
            }
        }

        return $base;
    }

    public static function translatedLayoutForAdmin(QuizFeature $quiz, string $lang): array
    {
        $base = is_array($quiz->layout_config) ? $quiz->layout_config : [];

        if ($lang === getDefaultLang()) {
            return $base;
        }

        $translation = $quiz->quiz_feature_translations
            ->where('lang', $lang)
            ->first();

        $overlay = is_array($translation?->layout_config) ? $translation->layout_config : null;

        return self::mergeTranslatableText($base, $overlay);
    }

    public static function translationOverlay(QuizFeature $quiz, string $lang): ?array
    {
        if ($lang === getDefaultLang()) {
            return null;
        }

        $translation = $quiz->quiz_feature_translations
            ->where('lang', $lang)
            ->first();

        return is_array($translation?->layout_config) ? $translation->layout_config : null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $blocks
     * @return array<int, array<string, mixed>>
     */
    private static function extractBlockText(array $blocks): array
    {
        $extracted = [];

        foreach ($blocks as $block) {
            if (!is_array($block)) {
                continue;
            }

            $id = $block['id'] ?? null;
            if (!$id) {
                continue;
            }

            $item = ['id' => (string) $id];

            if (array_key_exists('html', $block)) {
                $item['html'] = $block['html'];
            }

            if (array_key_exists('text', $block)) {
                $item['text'] = $block['text'];
            }

            if (count($item) > 1) {
                $extracted[] = $item;
            }
        }

        return $extracted;
    }

    /**
     * @param  array<int, array<string, mixed>>  $baseBlocks
     * @param  array<int, array<string, mixed>>  $overlayBlocks
     * @return array<int, array<string, mixed>>
     */
    private static function mergeBlocksText(array $baseBlocks, array $overlayBlocks): array
    {
        if (empty($baseBlocks)) {
            return $baseBlocks;
        }

        $overlayById = [];
        foreach ($overlayBlocks as $block) {
            if (!is_array($block) || empty($block['id'])) {
                continue;
            }
            $overlayById[(string) $block['id']] = $block;
        }

        $merged = [];
        foreach ($baseBlocks as $block) {
            if (!is_array($block)) {
                $merged[] = $block;
                continue;
            }

            $id = isset($block['id']) ? (string) $block['id'] : null;
            if ($id && isset($overlayById[$id])) {
                $overlay = $overlayById[$id];
                if (array_key_exists('html', $overlay)) {
                    $block['html'] = $overlay['html'];
                }
                if (array_key_exists('text', $overlay)) {
                    $block['text'] = $overlay['text'];
                }
            }

            $merged[] = $block;
        }

        return $merged;
    }

    /**
     * @param  array<int, array<string, mixed>>  $baseActions
     * @param  array<int, array<string, mixed>>  $overlayActions
     * @return array<int, array<string, mixed>>
     */
    private static function mergeActionsText(array $baseActions, array $overlayActions): array
    {
        if (empty($baseActions)) {
            return $baseActions;
        }

        $overlayById = [];
        foreach ($overlayActions as $action) {
            if (!is_array($action) || empty($action['id'])) {
                continue;
            }
            $overlayById[(string) $action['id']] = $action;
        }

        $merged = [];
        foreach ($baseActions as $action) {
            if (!is_array($action)) {
                $merged[] = $action;
                continue;
            }

            $id = isset($action['id']) ? (string) $action['id'] : null;
            if ($id && isset($overlayById[$id]) && array_key_exists('label', $overlayById[$id])) {
                $action['label'] = $overlayById[$id]['label'];
            }

            $merged[] = $action;
        }

        return $merged;
    }

    /**
     * @param  array<int, array<string, mixed>>  $baseProfiles
     * @param  array<int, array<string, mixed>>  $overlayProfiles
     * @return array<int, array<string, mixed>>
     */
    private static function mergeProductProfileText(array $baseProfiles, array $overlayProfiles): array
    {
        if (empty($baseProfiles)) {
            return $baseProfiles;
        }

        $overlayByProductId = [];
        foreach ($overlayProfiles as $profile) {
            if (!is_array($profile) || empty($profile['product_id'])) {
                continue;
            }
            $overlayByProductId[(int) $profile['product_id']] = $profile;
        }

        $merged = [];
        foreach ($baseProfiles as $profile) {
            if (!is_array($profile)) {
                $merged[] = $profile;
                continue;
            }

            $productId = isset($profile['product_id']) ? (int) $profile['product_id'] : 0;
            if ($productId && isset($overlayByProductId[$productId])) {
                if (array_key_exists('tagline', $overlayByProductId[$productId])) {
                    $profile['tagline'] = $overlayByProductId[$productId]['tagline'];
                }
                if (array_key_exists('description', $overlayByProductId[$productId])) {
                    $profile['description'] = $overlayByProductId[$productId]['description'];
                }
                if (array_key_exists('quote', $overlayByProductId[$productId])) {
                    $profile['quote'] = $overlayByProductId[$productId]['quote'];
                }
            }

            $merged[] = $profile;
        }

        return $merged;
    }
}
