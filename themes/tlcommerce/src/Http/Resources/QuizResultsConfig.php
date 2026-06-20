<?php

namespace Theme\TLCommerce\Http\Resources;

class QuizResultsConfig
{
    private const BLOCK_TYPES = [
        'hero_image',
        'title',
        'subtitle',
        'tagline',
        'body',
        'separator',
        'meta',
        'product_image',
        'match_badge',
    ];

    private const LAYOUT_MODES = ['featured_card', 'product_grid', 'featured_and_grid'];

    private const SEPARATOR_STYLES = ['dot', 'line', 'dashed', 'double', 'diamond', 'dots', 'mixture'];

    private const SEPARATOR_WIDTHS = ['short', 'medium', 'wide', 'full'];

    private const SEPARATOR_COLORS = ['accent', 'text', 'custom'];

    private const MIXTURE_PART_TYPES = ['line', 'dashed', 'double', 'dot', 'diamond', 'dots', 'gap'];

    private const TEXT_COLOR_MODES = ['inherit', 'accent', 'text', 'custom'];

    private const BG_COLOR_MODES = ['transparent', 'accent', 'text', 'custom'];

    private const HTML_BLOCK_TYPES = ['title', 'subtitle', 'tagline', 'body', 'meta'];

    private const APPEARANCE_BLOCK_TYPES = ['title', 'subtitle', 'tagline', 'body', 'meta', 'match_badge'];

    private const ACTION_STYLES = ['solid', 'outline', 'gradient_glow'];

    private const ACTION_TYPES = ['restart', 'link', 'share', 'top_product'];

    /**
     * @return array<string, mixed>|null null when results section was never configured (legacy quizzes)
     */
    public static function normalize(?array $results): ?array
    {
        if ($results === null) {
            return null;
        }

        $layoutMode = $results['layout_mode'] ?? 'product_grid';
        if (!in_array($layoutMode, self::LAYOUT_MODES, true)) {
            $layoutMode = 'product_grid';
        }

        $theme = self::normalizeTheme(is_array($results['theme'] ?? null) ? $results['theme'] : []);
        $blocks = self::normalizeBlocks(is_array($results['blocks'] ?? null) ? $results['blocks'] : null);
        $productGrid = self::normalizeProductGrid(is_array($results['product_grid'] ?? null) ? $results['product_grid'] : []);
        $actions = self::normalizeActions(is_array($results['actions'] ?? null) ? $results['actions'] : null);

        return [
            'layout_mode' => $layoutMode,
            'show_featured_card' => in_array($layoutMode, ['featured_card', 'featured_and_grid'], true),
            'show_product_grid' => in_array($layoutMode, ['product_grid', 'featured_and_grid'], true),
            'theme' => $theme,
            'blocks' => $blocks,
            'product_grid' => $productGrid,
            'actions' => $actions,
        ];
    }

    public static function defaultTheme(): array
    {
        return [
            'background_type' => 'solid',
            'background_color' => '#1a3d2a',
            'background_gradient_center' => '#1a3d2a',
            'background_gradient_edge' => '#050a07',
            'fill_viewport' => true,
            'content_max_width' => 560,
            'alignment' => 'center',
            'text_color' => '#ffffff',
            'accent_color' => '#c9a84c',
            'hero_size' => 80,
            'hero_frame' => 'none',
            'hero_glow' => true,
            'tagline_font' => 'serif_caps',
            'body_font' => 'script',
            'card_enabled' => true,
            'card_background' => '#1f4530',
            'card_border_style' => 'double',
            'card_border_color' => '#c9a84c',
            'card_border_radius' => 0,
            'card_padding' => 32,
            'card_max_width' => 480,
        ];
    }

    public static function defaultProductGrid(): array
    {
        return [
            'show_heading' => true,
            'heading' => 'Your recommendations',
            'subheading' => 'Based on your answers, these products are the best match for you.',
            'show_match_badge' => true,
            'match_badge_bg' => '#ff5a1f',
            'match_badge_text' => '#ffffff',
        ];
    }

    public static function defaultActions(): array
    {
        return [
            [
                'id' => 'explore',
                'enabled' => true,
                'label' => 'EXPLORE COLLECTION',
                'style' => 'gradient_glow',
                'action' => 'top_product',
                'url' => '',
                'product_rank' => 1,
                'bg_color' => '#c9a84c',
                'text_color' => '#1a3d2a',
                'border_color' => '#c9a84c',
            ],
            [
                'id' => 'share',
                'enabled' => true,
                'label' => 'SHARE YOUR STORY',
                'style' => 'outline',
                'action' => 'share',
                'url' => '',
                'product_rank' => 1,
                'bg_color' => 'transparent',
                'text_color' => '#c9a84c',
                'border_color' => '#c9a84c',
            ],
            [
                'id' => 'restart',
                'enabled' => true,
                'label' => 'RESTART',
                'style' => 'outline',
                'action' => 'restart',
                'url' => '',
                'product_rank' => 1,
                'bg_color' => 'transparent',
                'text_color' => '#c9a84c',
                'border_color' => '#c9a84c',
            ],
        ];
    }

    /** Legacy-compatible defaults (product grid + retake). */
    public static function legacyDefaults(): array
    {
        return [
            'layout_mode' => 'product_grid',
            'show_featured_card' => false,
            'show_product_grid' => true,
            'theme' => [
                'background_type' => 'solid',
                'background_color' => 'transparent',
                'background_gradient_center' => '#ffffff',
                'background_gradient_edge' => '#eeeeee',
                'fill_viewport' => false,
                'content_max_width' => 900,
                'alignment' => 'left',
                'text_color' => '#111111',
                'accent_color' => '#ff5a1f',
                'hero_size' => 140,
                'hero_frame' => 'none',
                'hero_glow' => false,
                'tagline_font' => 'default',
                'body_font' => 'default',
                'card_enabled' => false,
                'card_background' => '#ffffff',
                'card_border_style' => 'none',
                'card_border_color' => '#e5e7eb',
                'card_border_radius' => 12,
                'card_padding' => 24,
                'card_max_width' => 900,
            ],
            'blocks' => [],
            'product_grid' => self::defaultProductGrid(),
            'actions' => [
                [
                    'id' => 'restart',
                    'enabled' => true,
                    'label' => 'Retake Quiz',
                    'style' => 'outline',
                    'action' => 'restart',
                    'url' => '',
                    'product_rank' => 1,
                    'bg_color' => 'transparent',
                    'text_color' => '#111111',
                    'border_color' => '#111111',
                ],
            ],
        ];
    }

    public static function defaultBlocks(): array
    {
        return [
            ['id' => 'hero', 'type' => 'hero_image', 'enabled' => true],
            [
                'id' => 'tagline_top',
                'type' => 'tagline',
                'enabled' => true,
                'html' => '<p style="text-align:center"><span style="font-size:11px;letter-spacing:0.2em">GOLDEN BEE</span></p>',
                'spacing' => ['margin_top' => 0, 'margin_bottom' => 8, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'subtitle',
                'type' => 'subtitle',
                'enabled' => true,
                'html' => '<p style="text-align:center"><em>Golden Sand</em></p>',
                'spacing' => ['margin_top' => 0, 'margin_bottom' => 8, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'title',
                'type' => 'title',
                'enabled' => true,
                'html' => '<h2 style="text-align:center">{{product.name}}</h2>',
                'spacing' => ['margin_top' => 0, 'margin_bottom' => 8, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'tagline_mid',
                'type' => 'tagline',
                'enabled' => true,
                'html' => '<p style="text-align:center"><span style="font-size:10px;letter-spacing:0.15em">THE NATURALLY ELEGANT</span></p>',
                'spacing' => ['margin_top' => 0, 'margin_bottom' => 12, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'product_swatch',
                'type' => 'product_image',
                'enabled' => true,
                'product_rank' => 1,
                'display_mode' => 'swatch',
                'size' => 48,
                'spacing' => ['margin_top' => 0, 'margin_bottom' => 12, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'separator',
                'type' => 'separator',
                'enabled' => true,
                'style' => 'mixture',
                'color' => 'accent',
                'color_custom' => '#c9a84c',
                'thickness' => 1,
                'mixture_parts' => [
                    ['type' => 'line', 'width' => 'wide'],
                    ['type' => 'gap', 'size' => 12],
                    ['type' => 'diamond'],
                    ['type' => 'gap', 'size' => 12],
                    ['type' => 'line', 'width' => 'wide'],
                ],
                'spacing' => ['margin_top' => 8, 'margin_bottom' => 16, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'body',
                'type' => 'body',
                'enabled' => true,
                'html' => '<p style="text-align:center"><em>{{product.summary}}</em></p>',
                'spacing' => ['margin_top' => 0, 'margin_bottom' => 12, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
            [
                'id' => 'meta',
                'type' => 'meta',
                'enabled' => true,
                'html' => '<p style="text-align:center"><span style="font-size:10px;letter-spacing:0.12em">TWO BEES · ONE YOU · BE YOU</span></p>',
                'spacing' => ['margin_top' => 16, 'margin_bottom' => 0, 'padding_top' => 0, 'padding_bottom' => 0, 'padding_x' => 0],
            ],
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
            $alignment = 'center';
        }

        $borderStyle = $theme['card_border_style'] ?? $defaults['card_border_style'];
        if (!in_array($borderStyle, ['none', 'single', 'double'], true)) {
            $borderStyle = 'single';
        }

        return [
            'background_type' => $bgType,
            'background_color' => self::sanitizeHexColor($theme['background_color'] ?? $defaults['background_color']),
            'background_gradient_center' => self::sanitizeHexColor($theme['background_gradient_center'] ?? $defaults['background_gradient_center']),
            'background_gradient_edge' => self::sanitizeHexColor($theme['background_gradient_edge'] ?? $defaults['background_gradient_edge']),
            'fill_viewport' => array_key_exists('fill_viewport', $theme) ? (bool) $theme['fill_viewport'] : $defaults['fill_viewport'],
            'content_max_width' => max(280, min(900, (int) ($theme['content_max_width'] ?? $defaults['content_max_width']))),
            'alignment' => $alignment,
            'text_color' => self::sanitizeHexColor($theme['text_color'] ?? $defaults['text_color']),
            'accent_color' => self::sanitizeHexColor($theme['accent_color'] ?? $defaults['accent_color']),
            'hero_size' => max(40, min(320, (int) ($theme['hero_size'] ?? $defaults['hero_size']))),
            'hero_frame' => in_array($theme['hero_frame'] ?? 'none', ['none', 'corners'], true)
                ? ($theme['hero_frame'] ?? 'none')
                : 'none',
            'hero_glow' => (bool) ($theme['hero_glow'] ?? $defaults['hero_glow']),
            'tagline_font' => in_array($theme['tagline_font'] ?? 'default', ['default', 'serif_caps'], true)
                ? ($theme['tagline_font'] ?? 'default')
                : 'default',
            'body_font' => in_array($theme['body_font'] ?? 'default', ['default', 'script'], true)
                ? ($theme['body_font'] ?? 'default')
                : 'default',
            'card_enabled' => array_key_exists('card_enabled', $theme) ? (bool) $theme['card_enabled'] : $defaults['card_enabled'],
            'card_background' => self::sanitizeHexColor($theme['card_background'] ?? $defaults['card_background']),
            'card_border_style' => $borderStyle,
            'card_border_color' => self::sanitizeHexColor($theme['card_border_color'] ?? $defaults['card_border_color']),
            'card_border_radius' => max(0, min(32, (int) ($theme['card_border_radius'] ?? $defaults['card_border_radius']))),
            'card_padding' => max(0, min(64, (int) ($theme['card_padding'] ?? $defaults['card_padding']))),
            'card_max_width' => max(280, min(900, (int) ($theme['card_max_width'] ?? $defaults['card_max_width']))),
        ];
    }

    public static function normalizeProductGrid(array $grid): array
    {
        $defaults = self::defaultProductGrid();

        return [
            'show_heading' => array_key_exists('show_heading', $grid) ? (bool) $grid['show_heading'] : $defaults['show_heading'],
            'heading' => trim((string) ($grid['heading'] ?? $defaults['heading'])),
            'subheading' => trim((string) ($grid['subheading'] ?? $defaults['subheading'])),
            'show_match_badge' => array_key_exists('show_match_badge', $grid) ? (bool) $grid['show_match_badge'] : $defaults['show_match_badge'],
            'match_badge_bg' => self::sanitizeHexColor($grid['match_badge_bg'] ?? $defaults['match_badge_bg']),
            'match_badge_text' => self::sanitizeHexColor($grid['match_badge_text'] ?? $defaults['match_badge_text']),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $actions
     * @return array<int, array<string, mixed>>
     */
    public static function normalizeActions(?array $actions): array
    {
        if (empty($actions) || !is_array($actions)) {
            return self::defaultActions();
        }

        $normalized = [];
        foreach (array_slice($actions, 0, 5) as $index => $action) {
            if (!is_array($action)) {
                continue;
            }
            $sanitized = self::sanitizeAction($action, $index);
            if ($sanitized) {
                $normalized[] = $sanitized;
            }
        }

        return $normalized ?: self::defaultActions();
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $blocks
     * @return array<int, array<string, mixed>>
     */
    public static function normalizeBlocks(?array $blocks): array
    {
        if (empty($blocks) || !is_array($blocks)) {
            return self::defaultBlocks();
        }

        $normalized = [];
        foreach ($blocks as $block) {
            if (!is_array($block)) {
                continue;
            }
            $sanitized = self::sanitizeBlock($block);
            if ($sanitized) {
                $normalized[] = $sanitized;
            }
        }

        return $normalized ?: self::defaultBlocks();
    }

    public static function sanitizeBlock(array $block): ?array
    {
        $type = $block['type'] ?? '';
        if (!in_array($type, self::BLOCK_TYPES, true)) {
            return null;
        }

        $result = [
            'id' => (string) ($block['id'] ?? $type . '_' . uniqid()),
            'type' => $type,
            'enabled' => array_key_exists('enabled', $block) ? (bool) $block['enabled'] : true,
        ];

        if (in_array($type, self::HTML_BLOCK_TYPES, true)) {
            $html = isset($block['html']) ? trim((string) $block['html']) : '';
            if ($html !== '') {
                $result['html'] = $html;
            }
        }

        if ($type === 'tagline') {
            $text = isset($block['text']) ? trim((string) $block['text']) : '';
            if ($text !== '') {
                $result['text'] = $text;
            }
        }

        if ($type === 'separator') {
            $result = array_merge($result, self::sanitizeSeparatorBlock($block));
        }

        if ($type === 'hero_image') {
            $media = $block['image'] ?? $block['media_id'] ?? null;
            if ($media) {
                $result['image'] = is_numeric($media) || !str_starts_with((string) $media, 'http')
                    ? (QuizIntroConfig::mediaUrl($media) ?? $media)
                    : $media;
            }
        }

        if ($type === 'product_image') {
            $result['product_rank'] = max(1, min(20, (int) ($block['product_rank'] ?? 1)));
            $displayMode = $block['display_mode'] ?? 'image';
            $result['display_mode'] = in_array($displayMode, ['image', 'swatch'], true) ? $displayMode : 'image';
            $result['size'] = max(32, min(320, (int) ($block['size'] ?? 120)));
        }

        if ($type === 'match_badge') {
            $result['product_rank'] = max(1, min(20, (int) ($block['product_rank'] ?? 1)));
        }

        $result['spacing'] = self::sanitizeBlockSpacing($block, $type);

        $appearance = self::sanitizeBlockAppearance($block, $type);
        if ($appearance !== null) {
            $result['appearance'] = $appearance;
        }

        return $result;
    }

    private static function sanitizeAction(array $action, int $index): ?array
    {
        $style = $action['style'] ?? 'solid';
        if (!in_array($style, self::ACTION_STYLES, true)) {
            $style = 'solid';
        }

        $actionType = $action['action'] ?? 'link';
        if (!in_array($actionType, self::ACTION_TYPES, true)) {
            $actionType = 'link';
        }

        $label = trim((string) ($action['label'] ?? ''));
        if ($label === '') {
            return null;
        }

        return [
            'id' => (string) ($action['id'] ?? 'action_' . ($index + 1)),
            'enabled' => array_key_exists('enabled', $action) ? (bool) $action['enabled'] : true,
            'label' => $label,
            'style' => $style,
            'action' => $actionType,
            'url' => in_array($actionType, ['link', 'top_product'], true)
                ? self::normalizeActionUrl(trim((string) ($action['url'] ?? '')))
                : '',
            'product_rank' => max(1, min(20, (int) ($action['product_rank'] ?? 1))),
            'bg_color' => self::sanitizeActionColor($action['bg_color'] ?? '#c9a84c'),
            'text_color' => self::sanitizeActionColor($action['text_color'] ?? '#1a3d2a'),
            'border_color' => self::sanitizeActionColor($action['border_color'] ?? '#c9a84c'),
        ];
    }

    private static function sanitizeActionColor(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === 'transparent') {
            return 'transparent';
        }

        return self::sanitizeHexColor($value);
    }

    private static function normalizeActionUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (preg_match('#^https?://#i', $url)) {
            $parts = parse_url($url);
            $path = $parts['path'] ?? '/';
            if (!empty($parts['query'])) {
                $path .= '?' . $parts['query'];
            }
            if (!empty($parts['fragment'])) {
                $path .= '#' . $parts['fragment'];
            }

            return $path !== '' ? $path : '/';
        }
        if (str_starts_with($url, '//')) {
            return $url;
        }

        return '/' . ltrim($url, '/');
    }

    private static function sanitizeBlockSpacing(array $block, string $type): array
    {
        $spacing = is_array($block['spacing'] ?? null) ? $block['spacing'] : [];
        $marginTop = (int) ($spacing['margin_top'] ?? $block['spacing_top'] ?? 0);
        $marginBottom = (int) ($spacing['margin_bottom'] ?? $block['spacing_bottom'] ?? 16);

        return [
            'margin_top' => max(0, min(64, $marginTop)),
            'margin_bottom' => max(0, min(64, $marginBottom)),
            'padding_top' => max(0, min(64, (int) ($spacing['padding_top'] ?? 0))),
            'padding_bottom' => max(0, min(64, (int) ($spacing['padding_bottom'] ?? 0))),
            'padding_x' => max(0, min(64, (int) ($spacing['padding_x'] ?? 0))),
        ];
    }

    private static function sanitizeBlockAppearance(array $block, string $type): ?array
    {
        if (!in_array($type, self::APPEARANCE_BLOCK_TYPES, true)) {
            return null;
        }

        $appearance = is_array($block['appearance'] ?? null) ? $block['appearance'] : [];
        $textColor = $appearance['text_color'] ?? 'inherit';
        if (!in_array($textColor, self::TEXT_COLOR_MODES, true)) {
            $textColor = 'inherit';
        }

        $bgColor = $appearance['background_color'] ?? 'transparent';
        if (!in_array($bgColor, self::BG_COLOR_MODES, true)) {
            $bgColor = 'transparent';
        }

        return [
            'text_color' => $textColor,
            'text_color_custom' => self::sanitizeHexColor($appearance['text_color_custom'] ?? '#c9a84c'),
            'background_color' => $bgColor,
            'background_color_custom' => self::sanitizeHexColor($appearance['background_color_custom'] ?? '#1a3d2a'),
            'padding_x' => max(0, min(64, (int) ($appearance['padding_x'] ?? 0))),
            'padding_y' => max(0, min(64, (int) ($appearance['padding_y'] ?? 0))),
            'border_radius' => max(0, min(32, (int) ($appearance['border_radius'] ?? 0))),
        ];
    }

    private static function sanitizeSeparatorBlock(array $block): array
    {
        $style = $block['style'] ?? 'dot';
        if (!in_array($style, self::SEPARATOR_STYLES, true)) {
            $style = 'dot';
        }

        $width = $block['width'] ?? 'medium';
        if (!in_array($width, self::SEPARATOR_WIDTHS, true)) {
            $width = 'medium';
        }

        $color = $block['color'] ?? 'accent';
        if (!in_array($color, self::SEPARATOR_COLORS, true)) {
            $color = 'accent';
        }

        $result = [
            'style' => $style,
            'color' => $color,
            'color_custom' => self::sanitizeHexColor($block['color_custom'] ?? '#c9a84c'),
            'thickness' => max(1, min(4, (int) ($block['thickness'] ?? 1))),
        ];

        if ($style === 'mixture') {
            $result['mixture_parts'] = self::sanitizeMixtureParts($block['mixture_parts'] ?? null);
        } else {
            $result['width'] = $width;
        }

        return $result;
    }

    private static function sanitizeMixtureParts(?array $parts): array
    {
        if (empty($parts) || !is_array($parts)) {
            return [
                ['type' => 'line', 'width' => 'wide'],
                ['type' => 'gap', 'size' => 12],
                ['type' => 'diamond'],
                ['type' => 'gap', 'size' => 12],
                ['type' => 'line', 'width' => 'wide'],
            ];
        }

        $normalized = [];
        foreach (array_slice($parts, 0, 7) as $part) {
            if (!is_array($part)) {
                continue;
            }
            $type = $part['type'] ?? 'line';
            if (!in_array($type, self::MIXTURE_PART_TYPES, true)) {
                continue;
            }
            $item = ['type' => $type];
            if ($type === 'gap') {
                $item['size'] = max(4, min(32, (int) ($part['size'] ?? 12)));
            } elseif (in_array($type, ['line', 'dashed', 'double'], true)) {
                $width = $part['width'] ?? 'medium';
                $item['width'] = in_array($width, self::SEPARATOR_WIDTHS, true) ? $width : 'medium';
            }
            $normalized[] = $item;
        }

        return $normalized ?: [
            ['type' => 'line', 'width' => 'wide'],
            ['type' => 'gap', 'size' => 12],
            ['type' => 'diamond'],
            ['type' => 'gap', 'size' => 12],
            ['type' => 'line', 'width' => 'wide'],
        ];
    }

    public static function mediaUrl($mediaId): ?string
    {
        if (empty($mediaId)) {
            return null;
        }

        $path = getFilePath($mediaId, false);
        if (!$path) {
            return null;
        }

        return preg_replace('#^/public#', '', $path) ?: null;
    }

    private static function sanitizeHexColor(?string $value): string
    {
        $value = trim((string) $value);
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
            return $value;
        }

        return '#c9a84c';
    }
}
