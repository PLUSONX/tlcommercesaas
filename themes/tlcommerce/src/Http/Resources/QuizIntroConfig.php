<?php



namespace Theme\TLCommerce\Http\Resources;



class QuizIntroConfig

{

    private const BLOCK_TYPES = [

        'hero_image',

        'title',

        'subtitle',

        'tagline',

        'body',

        'separator',

        'meta',

        'cta',

        'secondary_link',

    ];

    private const SEPARATOR_STYLES = ['dot', 'line', 'dashed', 'double', 'diamond', 'dots', 'mixture'];

    private const SEPARATOR_WIDTHS = ['short', 'medium', 'wide', 'full'];

    private const SEPARATOR_COLORS = ['accent', 'text', 'custom'];

    private const MIXTURE_PART_TYPES = ['line', 'dashed', 'double', 'dot', 'diamond', 'dots', 'gap'];

    private const TEXT_COLOR_MODES = ['inherit', 'accent', 'text', 'custom'];

    private const BG_COLOR_MODES = ['transparent', 'accent', 'text', 'custom'];

    private const HTML_BLOCK_TYPES = ['title', 'subtitle', 'tagline', 'body', 'cta', 'secondary_link'];

    private const APPEARANCE_BLOCK_TYPES = ['title', 'subtitle', 'tagline', 'body', 'meta', 'cta', 'secondary_link'];



    public static function normalize(?array $layoutConfig, int $questionCount = 0): array

    {

        $layoutConfig = $layoutConfig ?? [];

        $intro = $layoutConfig['intro'] ?? [];



        return [

            'step_style' => $layoutConfig['step_style'] ?? 'wizard',

            'top_n' => (int) ($layoutConfig['top_n'] ?? 3),

            'skip_intro' => (bool) ($layoutConfig['skip_intro'] ?? false),

            'intro' => self::normalizeIntro($intro, $questionCount),

            'questions' => QuizQuestionsConfig::normalize($layoutConfig['questions'] ?? null),

            'results' => array_key_exists('results', $layoutConfig)
                ? QuizResultsConfig::normalize($layoutConfig['results'])
                : null,

        ];

    }



    public static function normalizeIntro(array $intro, int $questionCount = 0): array

    {

        $legacy = [

            'layout' => $intro['layout'] ?? 'minimal',

            'alignment' => $intro['alignment'] ?? 'center',

            'subtitle' => $intro['subtitle'] ?? null,

            'cta_text' => $intro['cta_text'] ?? null,

            'secondary_link_text' => $intro['secondary_link_text'] ?? null,

            'secondary_link_url' => $intro['secondary_link_url'] ?? null,

            'show_question_count' => array_key_exists('show_question_count', $intro)

                ? (bool) $intro['show_question_count']

                : true,

            'show_estimated_time' => (bool) ($intro['show_estimated_time'] ?? false),

            'estimated_minutes' => (int) ($intro['estimated_minutes'] ?? 2),

            'hero_image_desktop' => self::mediaUrl($intro['hero_image_desktop'] ?? null),

            'hero_image_mobile' => self::mediaUrl($intro['hero_image_mobile'] ?? null),

            'background_color' => $intro['background_color'] ?? '#ffffff',

            'text_color' => $intro['text_color'] ?? '#111111',

            'button_color' => $intro['button_color'] ?? '#ff5a1f',

            'button_text_color' => $intro['button_text_color'] ?? '#ffffff',

            'overlay_color' => $intro['overlay_color'] ?? '#000000',

            'overlay_opacity' => (int) ($intro['overlay_opacity'] ?? 40),

            'question_count' => $questionCount,

        ];



        $theme = self::normalizeTheme($intro['theme'] ?? null, $legacy);

        $blocks = self::normalizeBlocks(

            $intro['blocks'] ?? null,

            $legacy,

            $questionCount

        );



        return array_merge($legacy, [

            'theme' => $theme,

            'blocks' => $blocks,

        ]);

    }



    public static function normalizeTheme(?array $theme, array $legacy): array

    {

        $theme = $theme ?? [];

        $bgType = $theme['background_type'] ?? 'solid';

        if (!in_array($bgType, ['solid', 'radial_gradient'], true)) {

            $bgType = 'solid';

        }



        return [

            'background_type' => $bgType,

            'background_color' => $theme['background_color'] ?? $legacy['background_color'],

            'background_gradient_center' => $theme['background_gradient_center']

                ?? $theme['background_color']

                ?? $legacy['background_color'],

            'background_gradient_edge' => $theme['background_gradient_edge'] ?? '#050a07',

            'accent_color' => $theme['accent_color'] ?? $legacy['text_color'],

            'text_color' => $theme['text_color'] ?? $legacy['text_color'],

            'alignment' => in_array($theme['alignment'] ?? $legacy['alignment'], ['left', 'center', 'right'], true)

                ? ($theme['alignment'] ?? $legacy['alignment'])

                : 'center',

            'hero_size' => max(80, min(320, (int) ($theme['hero_size'] ?? 140))),

            'hero_frame' => in_array($theme['hero_frame'] ?? 'none', ['none', 'corners'], true)

                ? ($theme['hero_frame'] ?? 'none')

                : 'none',

            'hero_glow' => (bool) ($theme['hero_glow'] ?? false),

            'tagline_font' => in_array($theme['tagline_font'] ?? 'default', ['default', 'serif_caps'], true)

                ? ($theme['tagline_font'] ?? 'default')

                : 'default',

            'body_font' => in_array($theme['body_font'] ?? 'default', ['default', 'script'], true)

                ? ($theme['body_font'] ?? 'default')

                : 'default',

            'button_style' => in_array($theme['button_style'] ?? 'solid', ['solid', 'gradient_glow'], true)

                ? ($theme['button_style'] ?? 'solid')

                : 'solid',

            'button_color' => $theme['button_color'] ?? $legacy['button_color'],

            'button_text_color' => $theme['button_text_color'] ?? $legacy['button_text_color'],

            'min_height' => max(200, min(900, (int) ($theme['min_height'] ?? 400))),

            'content_max_width' => max(280, min(900, (int) ($theme['content_max_width'] ?? 560))),

        ];

    }



    public static function normalizeBlocks(?array $blocks, array $legacy, int $questionCount): array

    {

        if (empty($blocks) || !is_array($blocks)) {

            $blocks = self::synthesizeBlocksFromLegacy($legacy);

        }



        $normalized = [];

        foreach ($blocks as $index => $block) {

            if (!is_array($block)) {

                continue;

            }

            $sanitized = self::sanitizeBlock($block, $legacy, $questionCount);

            if ($sanitized) {

                $normalized[] = $sanitized;

            }

        }



        return $normalized ?: self::synthesizeBlocksFromLegacy($legacy);

    }



    public static function sanitizeBlock(array $block, array $legacy, int $questionCount): ?array

    {

        $type = $block['type'] ?? '';

        if (!in_array($type, self::BLOCK_TYPES, true)) {

            return null;

        }



        $id = $block['id'] ?? $type . '_' . uniqid();

        $result = [

            'id' => (string) $id,

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

            $text = $block['text'] ?? $legacy['subtitle'] ?? '';

            $result['text'] = self::resolveTaglineText((string) $text, $questionCount);

            if (!empty($result['html'])) {

                $result['html'] = self::resolveTaglineText($result['html'], $questionCount);

            }

        }



        if ($type === 'separator') {

            $result = array_merge($result, self::sanitizeSeparatorBlock($block));

        }



        if ($type === 'secondary_link') {

            $url = isset($block['url']) ? trim((string) $block['url']) : '';

            if ($url !== '') {

                $result['url'] = $url;

            }

        }



        $result['spacing'] = self::sanitizeBlockSpacing($block, $type);



        $appearance = self::sanitizeBlockAppearance($block, $type);

        if ($appearance !== null) {

            $result['appearance'] = $appearance;

        }



        return $result;

    }



    private static function sanitizeBlockSpacing(array $block, string $type): array

    {

        $spacing = is_array($block['spacing'] ?? null) ? $block['spacing'] : [];

        $marginTop = (int) ($spacing['margin_top'] ?? $block['spacing_top'] ?? 0);

        $marginBottom = (int) ($spacing['margin_bottom'] ?? $block['spacing_bottom'] ?? ($type === 'separator' ? 16 : 16));



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



        $colorCustom = self::sanitizeHexColor($block['color_custom'] ?? '#c9a84c');



        $result = [

            'style' => $style,

            'color' => $color,

            'color_custom' => $colorCustom,

            'thickness' => max(1, min(4, (int) ($block['thickness'] ?? 1))),

        ];



        if ($style === 'mixture') {

            $result['mixture_parts'] = self::sanitizeMixtureParts($block['mixture_parts'] ?? null);

        } else {

            $result['width'] = $width;

        }



        return $result;

    }



    private static function sanitizeHexColor(?string $value): string

    {

        $value = trim((string) $value);

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {

            return $value;

        }



        return '#c9a84c';

    }



    public static function synthesizeBlocksFromLegacy(array $legacy): array

    {

        $blocks = [

            ['id' => 'hero', 'type' => 'hero_image', 'enabled' => true],

            ['id' => 'title', 'type' => 'title', 'enabled' => true],

        ];



        if (!empty($legacy['subtitle'])) {

            $blocks[] = [

                'id' => 'subtitle',

                'type' => 'subtitle',

                'enabled' => true,

                'html' => $legacy['subtitle'],

            ];

        }



        $blocks[] = ['id' => 'body', 'type' => 'body', 'enabled' => true];



        if ($legacy['show_question_count'] || $legacy['show_estimated_time']) {

            $blocks[] = ['id' => 'meta', 'type' => 'meta', 'enabled' => true];

        }



        $blocks[] = ['id' => 'cta', 'type' => 'cta', 'enabled' => true];



        if (!empty($legacy['secondary_link_text']) && !empty($legacy['secondary_link_url'])) {

            $blocks[] = ['id' => 'secondary', 'type' => 'secondary_link', 'enabled' => true];

        }



        return $blocks;

    }



    public static function resolveTaglineText(string $text, int $questionCount): string

    {

        return str_replace('{count}', (string) $questionCount, $text);

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



    public static function defaultBlocks(): array

    {

        return [

            ['id' => 'hero', 'type' => 'hero_image', 'enabled' => true],

            ['id' => 'title', 'type' => 'title', 'enabled' => true],

            ['id' => 'subtitle', 'type' => 'subtitle', 'enabled' => false, 'html' => ''],

            ['id' => 'tagline_top', 'type' => 'tagline', 'enabled' => false, 'text' => ''],

            ['id' => 'separator', 'type' => 'separator', 'enabled' => false, 'style' => 'dot', 'width' => 'medium', 'color' => 'accent', 'color_custom' => '#c9a84c', 'thickness' => 1, 'spacing_top' => 8, 'spacing_bottom' => 16],

            ['id' => 'body', 'type' => 'body', 'enabled' => true],

            ['id' => 'tagline_bottom', 'type' => 'tagline', 'enabled' => false, 'text' => '{count} questions'],

            ['id' => 'meta', 'type' => 'meta', 'enabled' => true],

            ['id' => 'cta', 'type' => 'cta', 'enabled' => true],

            ['id' => 'secondary', 'type' => 'secondary_link', 'enabled' => false],

        ];

    }



    public static function defaultTheme(array $legacy = []): array

    {

        return self::normalizeTheme(null, array_merge([

            'background_color' => '#ffffff',

            'text_color' => '#111111',

            'button_color' => '#ff5a1f',

            'button_text_color' => '#ffffff',

            'alignment' => 'center',

        ], $legacy));

    }

}


