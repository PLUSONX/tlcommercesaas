<?php



namespace Theme\TLCommerce\Http\Resources;



class QuizAnswerDefaultsConfig

{

    private const FILL_KEYS = [

        'background',

        'border_color',

        'text_color',

        'hover_background',

        'hover_border_color',

        'active_background',

        'active_border_color',

        'active_text_color',

    ];



    private const DEFAULT_EDGE = '#050a07';



    public static function normalize(?array $defaults, ?array $legacyTheme = null): array

    {

        $defaults = $defaults ?? [];

        $legacyTheme = $legacyTheme ?? [];



        return [

            'list' => self::normalizeLayoutStyle(

                is_array($defaults['list'] ?? null) ? $defaults['list'] : [],

                'list',

                $legacyTheme

            ),

            'grid' => self::normalizeLayoutStyle(

                is_array($defaults['grid'] ?? null) ? $defaults['grid'] : [],

                'grid',

                $legacyTheme

            ),

        ];

    }



    public static function defaultLayoutStyle(string $layout): array

    {

        $base = [

            'gap' => 8,

            'border_width' => 1,

            'padding' => 12,

            'radius' => 8,

            'show_native_input' => true,

            'background' => '#ffffff',

            'border_color' => '#e2e2e2',

            'text_color' => '#111111',

            'hover_background' => '#f3f4f6',

            'hover_border_color' => '#d1d5db',

            'active_background' => '#f8f9fa',

            'active_border_color' => '#333333',

            'active_text_color' => '#111111',

        ];



        foreach (self::FILL_KEYS as $key) {

            $base[$key . '_type'] = 'solid';

            $base[$key . '_gradient_center'] = $base[$key];

            $base[$key . '_gradient_edge'] = self::DEFAULT_EDGE;

        }



        if ($layout === 'grid') {

            $base['grid_columns'] = 2;

            $base['image_size'] = 80;

            $base['image_position'] = 'top';

            $base['hover_scale'] = 1;

        }



        return $base;

    }



    public static function normalizeLayoutStyle(array $style, string $layout, ?array $legacyTheme = null): array

    {

        $defaults = self::defaultLayoutStyle($layout);

        $legacyTheme = $legacyTheme ?? [];



        if (!empty($legacyTheme) && empty($style)) {

            $defaults = self::mergeLegacyTheme($defaults, $legacyTheme, $layout);

        }



        $imagePosition = $style['image_position'] ?? $defaults['image_position'] ?? 'top';

        if (!in_array($imagePosition, ['left', 'top'], true)) {

            $imagePosition = 'top';

        }



        $normalized = [

            'gap' => max(0, min(32, (int) ($style['gap'] ?? $defaults['gap']))),

            'border_width' => max(0, min(4, (int) ($style['border_width'] ?? $defaults['border_width']))),

            'padding' => max(0, min(64, (int) ($style['padding'] ?? $defaults['padding']))),

            'radius' => max(0, min(32, (int) ($style['radius'] ?? $defaults['radius']))),

            'show_native_input' => array_key_exists('show_native_input', $style)

                ? (bool) $style['show_native_input']

                : $defaults['show_native_input'],

        ];



        foreach (self::FILL_KEYS as $key) {

            $normalized = array_merge($normalized, self::normalizeFill($style, $defaults, $key));

        }



        if ($layout === 'grid') {

            $normalized['grid_columns'] = max(2, min(4, (int) ($style['grid_columns'] ?? $defaults['grid_columns'] ?? 2)));

            $normalized['image_size'] = max(40, min(160, (int) ($style['image_size'] ?? $defaults['image_size'] ?? 80)));

            $normalized['image_position'] = $imagePosition;

            $normalized['hover_scale'] = round(max(1.0, min(1.2, (float) ($style['hover_scale'] ?? $defaults['hover_scale'] ?? 1))), 2);

        }



        return $normalized;

    }



    public static function resolveFill(array $style, string $baseKey): string

    {

        $defaults = self::defaultLayoutStyle('list');

        $type = $style[$baseKey . '_type'] ?? 'solid';

        $solid = self::sanitizeHexColor($style[$baseKey] ?? $defaults[$baseKey] ?? '#111111');



        if ($type === 'radial_gradient') {

            $center = self::sanitizeHexColor(

                $style[$baseKey . '_gradient_center'] ?? $solid

            );

            $edge = self::sanitizeHexColor(

                $style[$baseKey . '_gradient_edge'] ?? self::DEFAULT_EDGE

            );



            return "radial-gradient(circle at center, {$center} 0%, {$edge} 100%)";

        }



        return $solid;

    }



    private static function normalizeFill(array $style, array $defaults, string $key): array

    {

        $type = $style[$key . '_type'] ?? $defaults[$key . '_type'] ?? 'solid';

        if (!in_array($type, ['solid', 'radial_gradient'], true)) {

            $type = 'solid';

        }



        $solid = self::sanitizeHexColor($style[$key] ?? $defaults[$key]);



        return [

            $key => $solid,

            $key . '_type' => $type,

            $key . '_gradient_center' => self::sanitizeHexColor(

                $style[$key . '_gradient_center'] ?? $defaults[$key . '_gradient_center'] ?? $solid

            ),

            $key . '_gradient_edge' => self::sanitizeHexColor(

                $style[$key . '_gradient_edge'] ?? $defaults[$key . '_gradient_edge'] ?? self::DEFAULT_EDGE

            ),

        ];

    }



    private static function mergeLegacyTheme(array $defaults, array $legacyTheme, string $layout): array

    {

        $merged = array_merge($defaults, [

            'gap' => (int) ($legacyTheme['answer_gap'] ?? $defaults['gap']),

            'border_width' => (int) ($legacyTheme['answer_border_width'] ?? $defaults['border_width']),

            'padding' => (int) ($legacyTheme['answer_padding'] ?? $defaults['padding']),

            'radius' => (int) ($legacyTheme['answer_border_radius'] ?? $defaults['radius']),

            'show_native_input' => array_key_exists('answer_show_input', $legacyTheme)

                ? (bool) $legacyTheme['answer_show_input']

                : $defaults['show_native_input'],

            'background' => $legacyTheme['answer_background'] ?? $defaults['background'],

            'border_color' => $legacyTheme['answer_border_color'] ?? $defaults['border_color'],

            'text_color' => $legacyTheme['answer_text_color'] ?? $defaults['text_color'],

            'hover_background' => $legacyTheme['answer_hover_background'] ?? $defaults['hover_background'],

            'hover_border_color' => $legacyTheme['answer_hover_border_color'] ?? $defaults['hover_border_color'],

            'active_background' => $legacyTheme['answer_active_background'] ?? $defaults['active_background'],

            'active_border_color' => $legacyTheme['answer_active_border_color'] ?? $defaults['active_border_color'],

            'active_text_color' => $legacyTheme['answer_active_text_color'] ?? $defaults['active_text_color'],

        ]);



        if ($layout === 'grid') {

            $merged['grid_columns'] = (int) ($legacyTheme['image_select_grid_columns'] ?? $merged['grid_columns'] ?? 2);

            $merged['image_size'] = (int) ($legacyTheme['image_select_image_size'] ?? $merged['image_size'] ?? 80);

            $merged['image_position'] = $legacyTheme['image_select_image_position'] ?? $merged['image_position'] ?? 'top';

        }



        return $merged;

    }



    private static function sanitizeHexColor(?string $value): string

    {

        $value = trim((string) $value);

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {

            return $value;

        }



        return '#111111';

    }

}

