<script>
(function($) {
    "use strict";

    var previewQuestionCount = {{ isset($quiz) ? (int) $quiz->questions->count() : 0 }};

    var BLOCK_EDITOR_TYPES = ['title', 'subtitle', 'tagline', 'body', 'cta', 'secondary_link'];

    var BLOCK_LABELS = {
        hero_image: '{{ translate('Hero image') }}',
        title: '{{ translate('Title') }}',
        subtitle: '{{ translate('Subtitle') }}',
        tagline: '{{ translate('Tagline') }}',
        body: '{{ translate('Body') }}',
        separator: '{{ translate('Separator') }}',
        meta: '{{ translate('Meta') }}',
        cta: '{{ translate('CTA button') }}',
        secondary_link: '{{ translate('Secondary link') }}'
    };

    var BLOCK_HINTS = {
        hero_image: '{{ translate('Uses hero image upload') }}',
        title: '{{ translate('Rich text display title') }}',
        subtitle: '{{ translate('Rich text subtitle') }}',
        tagline: '{{ translate('Use {count} for question count') }}',
        body: '{{ translate('Rich text intro body') }}',
        meta: '{{ translate('Uses question count / time toggles') }}',
        cta: '{{ translate('Rich text button label') }}',
        secondary_link: '{{ translate('Link text and URL') }}',
        separator: '{{ translate('Style, width, color, spacing') }}'
    };

    var DEFAULT_SEPARATOR = {
        style: 'dot',
        width: 'medium',
        color: 'accent',
        color_custom: '#c9a84c',
        thickness: 1,
        spacing_top: 8,
        spacing_bottom: 16
    };

    var DEFAULT_SPACING = {
        margin_top: 0,
        margin_bottom: 16,
        padding_top: 0,
        padding_bottom: 0,
        padding_x: 0
    };

    var DEFAULT_APPEARANCE = {
        text_color: 'inherit',
        text_color_custom: '#c9a84c',
        background_color: 'transparent',
        background_color_custom: '#1a3d2a',
        padding_x: 0,
        padding_y: 0,
        border_radius: 0
    };

    var APPEARANCE_BLOCK_TYPES = ['title', 'subtitle', 'tagline', 'body', 'meta', 'cta', 'secondary_link'];

    var MIXTURE_LINE_DIAMOND_PRESET = [
        { type: 'line', width: 'wide' },
        { type: 'gap', size: 12 },
        { type: 'diamond' },
        { type: 'gap', size: 12 },
        { type: 'line', width: 'wide' }
    ];

    var DEFAULT_BLOCKS = [
        { id: 'hero', type: 'hero_image', enabled: true },
        { id: 'title', type: 'title', enabled: true, html: '' },
        { id: 'subtitle', type: 'subtitle', enabled: false, html: '' },
        { id: 'tagline_top', type: 'tagline', enabled: false, text: '', html: '' },
        { id: 'separator', type: 'separator', enabled: false, style: 'dot', width: 'medium', color: 'accent', color_custom: '#c9a84c', thickness: 1, spacing: { margin_top: 8, margin_bottom: 16, padding_top: 0, padding_bottom: 0, padding_x: 0 } },
        { id: 'body', type: 'body', enabled: true, html: '' },
        { id: 'tagline_bottom', type: 'tagline', enabled: false, text: '{count} questions', html: '' },
        { id: 'meta', type: 'meta', enabled: true },
        { id: 'cta', type: 'cta', enabled: true, html: '' },
        { id: 'secondary', type: 'secondary_link', enabled: false, html: '', url: '' }
    ];

    var summernoteBlockOptions = {
        height: 120,
        tabsize: 2,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ],
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'blockquote'],
        fontNames: [
            'Arial', 'Helvetica', 'Georgia', 'Times New Roman', 'Courier New', 'Verdana',
            'Playfair Display', 'Great Vibes'
        ],
        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48'],
        callbacks: {
            onChange: function() {
                if (typeof window.syncQuizIntroBuilder === 'function') {
                    window.syncQuizIntroBuilder();
                }
            }
        }
    };

    function hexFromInput(id, fallback) {
        var val = $('#' + id).val();
        return val || fallback;
    }

    function cleanMediaPreviewUrl(url) {
        if (!url) return '';
        return url.replace(/^\/public/, '');
    }

    function stripHtmlTags(html) {
        var tmp = document.createElement('div');
        tmp.innerHTML = html || '';
        return (tmp.textContent || tmp.innerText || '').trim();
    }

    function getBlocks() {
        try {
            return JSON.parse($('#intro-blocks-json').val() || '[]');
        } catch (e) {
            return DEFAULT_BLOCKS.slice();
        }
    }

    function setBlocks(blocks) {
        $('#intro-blocks-json').val(JSON.stringify(blocks));
    }

    function getTheme() {
        try {
            return JSON.parse($('#intro-theme-json').val() || '{}');
        } catch (e) {
            return {};
        }
    }

    function setTheme(theme) {
        $('#intro-theme-json').val(JSON.stringify(theme));
    }

    function buildThemeFromLegacy() {
        return {
            background_type: 'solid',
            background_color: hexFromInput('intro-bg-input', '#ffffff'),
            background_gradient_center: hexFromInput('intro-bg-input', '#ffffff'),
            background_gradient_edge: '#eeeeee',
            accent_color: hexFromInput('intro-text-input', '#111111'),
            text_color: hexFromInput('intro-text-input', '#111111'),
            alignment: $('#intro-alignment-input').val() || 'center',
            hero_size: 140,
            hero_frame: 'none',
            hero_glow: false,
            tagline_font: 'default',
            body_font: 'default',
            button_style: 'solid',
            button_color: hexFromInput('intro-btn-input', '#ff5a1f'),
            button_text_color: hexFromInput('intro-btn-text-input', '#ffffff'),
            min_height: 400,
            content_max_width: 560
        };
    }

    function readThemeFromPanel() {
        var theme = getTheme();
        theme.background_type = $('#intro-theme-bg-type').val() || 'solid';
        theme.background_color = hexFromInput('intro-theme-bg-color', '#ffffff');
        theme.background_gradient_center = hexFromInput('intro-theme-bg-center', '#1a3d2a');
        theme.background_gradient_edge = hexFromInput('intro-theme-bg-edge', '#050a07');
        theme.accent_color = hexFromInput('intro-theme-accent', '#111111');
        theme.text_color = hexFromInput('intro-theme-text', '#111111');
        theme.alignment = $('#intro-alignment-input').val() || 'center';
        theme.hero_size = parseInt($('#intro-theme-hero-size').val(), 10) || 140;
        theme.hero_frame = $('#intro-theme-hero-frame').val() || 'none';
        theme.hero_glow = $('#intro-theme-hero-glow').is(':checked');
        theme.tagline_font = $('#intro-theme-tagline-font').val() || 'default';
        theme.body_font = $('#intro-theme-body-font').val() || 'default';
        theme.button_style = $('#intro-theme-button-style').val() || 'solid';
        theme.button_color = hexFromInput('intro-theme-btn-color', '#ff5a1f');
        theme.button_text_color = hexFromInput('intro-theme-btn-text', '#ffffff');
        theme.min_height = parseInt($('#intro-theme-min-height').val(), 10) || 400;
        theme.content_max_width = parseInt($('#intro-theme-max-width').val(), 10) || 560;
        setTheme(theme);
        return theme;
    }

    function resolveTagline(text) {
        return (text || '').replace(/\{count\}/g, String(previewQuestionCount));
    }

    function blockHtmlFallback(block) {
        if (block.html) {
            if (block.type === 'tagline') {
                return resolveTagline(block.html);
            }
            return block.html;
        }
        if (block.type === 'title') {
            var title = $('#quiz-title-input').val() || '';
            return title ? '<p>' + $('<div>').text(title).html() + '</p>' : '';
        }
        if (block.type === 'subtitle') {
            var sub = $('#intro-subtitle-input').val() || '';
            return sub ? '<p>' + $('<div>').text(sub).html() + '</p>' : '';
        }
        if (block.type === 'body') {
            if ($('#quiz-description-input').summernote) {
                return $('#quiz-description-input').summernote('code') || '';
            }
            return $('#quiz-description-input').val() || '';
        }
        if (block.type === 'cta') {
            var cta = $('#intro-cta-input').val() || '';
            return cta ? '<span>' + $('<div>').text(cta).html() + '</span>' : '';
        }
        if (block.type === 'secondary_link') {
            var sec = $('#intro-secondary-text-input').val() || '';
            return sec ? '<span>' + $('<div>').text(sec).html() + '</span>' : '';
        }
        if (block.type === 'tagline') {
            var tagText = resolveTagline(block.text || '');
            return tagText ? '<span>' + $('<div>').text(tagText).html() + '</span>' : '';
        }
        return '';
    }

    function separatorColorValue(block, theme) {
        if (block.color === 'text') {
            return theme.text_color || '#111111';
        }
        if (block.color === 'custom') {
            return block.color_custom || '#c9a84c';
        }
        return theme.accent_color || theme.text_color || '#111111';
    }

    function separatorWidthCss(width) {
        var map = { short: '40px', medium: '80px', wide: '50%', full: '100%' };
        return map[width] || map.medium;
    }

    function getBlockSpacing(block) {
        var sp = block.spacing || {};
        if (block.spacing_top != null && sp.margin_top == null) {
            sp.margin_top = block.spacing_top;
        }
        if (block.spacing_bottom != null && sp.margin_bottom == null) {
            sp.margin_bottom = block.spacing_bottom;
        }
        return $.extend({}, DEFAULT_SPACING, sp);
    }

    function getBlockAppearance(block) {
        return $.extend({}, DEFAULT_APPEARANCE, block.appearance || {});
    }

    function resolveColorMode(mode, custom, theme, fallbackKey) {
        if (mode === 'custom') {
            return custom || '#c9a84c';
        }
        if (mode === 'accent') {
            return theme.accent_color || theme.text_color || '#111111';
        }
        if (mode === 'text') {
            return theme.text_color || '#111111';
        }
        if (mode === 'transparent') {
            return 'transparent';
        }
        return theme[fallbackKey] || theme.text_color || '#111111';
    }

    function blockSpacingStyles(block, options) {
        options = options || {};
        var fullWidth = options.fullWidth !== false;
        var sp = getBlockSpacing(block);
        var css = {
            marginTop: sp.margin_top + 'px',
            marginBottom: sp.margin_bottom + 'px',
            paddingTop: sp.padding_top + 'px',
            paddingBottom: sp.padding_bottom + 'px',
            paddingLeft: sp.padding_x + 'px',
            paddingRight: sp.padding_x + 'px',
            boxSizing: 'border-box'
        };
        if (fullWidth) {
            css.width = '100%';
        }
        return css;
    }

    function blockAppearanceStyles(block, theme, blockType) {
        var app = getBlockAppearance(block);
        var css = {};
        var textColor = resolveColorMode(app.text_color, app.text_color_custom, theme, blockType === 'cta' ? 'button_text_color' : 'text_color');
        if (app.text_color !== 'inherit' || blockType === 'meta') {
            css.color = textColor;
        }
        var bg = resolveColorMode(app.background_color, app.background_color_custom, theme, 'background_color');
        if (app.background_color !== 'transparent') {
            css.backgroundColor = bg;
        }
        if (app.padding_x || app.padding_y) {
            css.paddingLeft = (app.padding_x || 0) + 'px';
            css.paddingRight = (app.padding_x || 0) + 'px';
            css.paddingTop = (app.padding_y || 0) + 'px';
            css.paddingBottom = (app.padding_y || 0) + 'px';
        }
        if (app.border_radius) {
            css.borderRadius = app.border_radius + 'px';
        }
        return css;
    }

    function blockWrapperStyles(block, theme) {
        return $.extend({}, blockSpacingStyles(block), blockAppearanceStyles(block, theme, block.type));
    }

    function ctaAppearanceStyles(block, theme) {
        var app = getBlockAppearance(block);
        var css = {};
        if (app.text_color !== 'inherit') {
            css.color = resolveColorMode(app.text_color, app.text_color_custom, theme, 'button_text_color');
        }
        if (app.background_color !== 'transparent') {
            css.backgroundColor = resolveColorMode(app.background_color, app.background_color_custom, theme, 'button_color');
            css.borderColor = css.backgroundColor;
        }
        if (app.padding_x || app.padding_y) {
            css.paddingLeft = ((app.padding_x || 0) + 12) + 'px';
            css.paddingRight = ((app.padding_x || 0) + 12) + 'px';
            css.paddingTop = ((app.padding_y || 0) + 8) + 'px';
            css.paddingBottom = ((app.padding_y || 0) + 8) + 'px';
        }
        if (app.border_radius) {
            css.borderRadius = app.border_radius + 'px';
        }
        return css;
    }

    function separatorPreviewStyles(block, theme) {
        var color = separatorColorValue(block, theme);
        var style = block.style || 'dot';
        var thickness = parseInt(block.thickness, 10) || 1;
        var sp = getBlockSpacing(block);

        var css = {
            color: color
        };

        if (style === 'dot') {
            css.width = '6px';
            css.height = '6px';
            css.borderRadius = '50%';
            css.background = color;
        } else if (style === 'diamond') {
            css.width = '8px';
            css.height = '8px';
            css.background = color;
            css.transform = 'rotate(45deg)';
        } else if (style === 'dots') {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = '6px';
            css.background = 'transparent';
            css.display = 'flex';
            css.gap = '6px';
            css.justifyContent = 'center';
        } else if (style === 'double') {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = (thickness * 2 + 2) + 'px';
            css.borderTop = thickness + 'px solid ' + color;
            css.borderBottom = thickness + 'px solid ' + color;
            css.background = 'transparent';
            css.opacity = '0.7';
        } else if (style === 'dashed') {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = '0';
            css.borderTop = thickness + 'px dashed ' + color;
            css.background = 'transparent';
            css.opacity = '0.7';
        } else {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = thickness + 'px';
            css.background = color;
            css.opacity = '0.5';
        }

        return css;
    }

    function getHeroPreviewUrl() {
        var desktopImg = $('input[name="intro_hero_image_desktop"]').val();
        var heroUrl = '';
        if (desktopImg) {
            heroUrl = cleanMediaPreviewUrl($('#intro_hero_image_desktop_preview').attr('src') || '');
        }
        if (!heroUrl && $('#preview-hero-wrap').data('desktop-url')) {
            heroUrl = cleanMediaPreviewUrl($('#preview-hero-wrap').data('desktop-url'));
        }
        return heroUrl;
    }

    function destroyBlockEditors() {
        $('#intro-blocks-list .intro-block-editor').each(function() {
            var $el = $(this);
            if ($el.next('.note-editor').length || $el.data('summernote')) {
                try {
                    $el.summernote('destroy');
                } catch (e) {
                    // ignore
                }
            }
        });
    }

    function initBlockEditors() {
        $('#intro-blocks-list .intro-block-editor').each(function() {
            var $el = $(this);
            var initial = $el.val() || '';
            if (!$el.next('.note-editor').length) {
                $el.summernote(summernoteBlockOptions);
                if (initial) {
                    $el.summernote('code', initial);
                }
            }
        });
    }

    function buildBlockSpacingPanel(block) {
        var sp = getBlockSpacing(block);
        var $panel = $('<div class="intro-block-spacing-panel mt-2"></div>');
        $panel.append('<div class="intro-panel-label">{{ translate('Block spacing') }}</div>');
        var $row = $('<div class="intro-sep-row d-flex flex-wrap gap-2 align-items-center"></div>');
        $row.append('<label class="small mb-0">{{ translate('Margin top') }}</label>');
        $row.append('<input type="number" class="theme-input-style intro-sp-margin-top" min="0" max="64" style="width:56px" value="' + sp.margin_top + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Margin bottom') }}</label>');
        $row.append('<input type="number" class="theme-input-style intro-sp-margin-bottom" min="0" max="64" style="width:56px" value="' + sp.margin_bottom + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Pad top') }}</label>');
        $row.append('<input type="number" class="theme-input-style intro-sp-padding-top" min="0" max="64" style="width:56px" value="' + sp.padding_top + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Pad bottom') }}</label>');
        $row.append('<input type="number" class="theme-input-style intro-sp-padding-bottom" min="0" max="64" style="width:56px" value="' + sp.padding_bottom + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Pad X') }}</label>');
        $row.append('<input type="number" class="theme-input-style intro-sp-padding-x" min="0" max="64" style="width:56px" value="' + sp.padding_x + '">');
        $panel.append($row);
        return $panel;
    }

    function buildTextAppearancePanel(block) {
        var app = getBlockAppearance(block);
        var $panel = $('<div class="intro-block-appearance-panel mt-2"></div>');
        $panel.append('<div class="intro-panel-label">{{ translate('Text styling') }}</div>');
        var $row1 = $('<div class="intro-sep-row d-flex flex-wrap gap-2 align-items-center mb-2"></div>');
        var $textSel = $('<select class="theme-input-style intro-app-text-color"></select>');
        [['inherit', '{{ translate('Inherit') }}'], ['accent', '{{ translate('Accent') }}'], ['text', '{{ translate('Text') }}'], ['custom', '{{ translate('Custom') }}']].forEach(function(o) {
            $textSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $textSel.val(app.text_color);
        $row1.append('<label class="small mb-0">{{ translate('Text color') }}</label>').append($textSel);
        var $textCustom = $('<input type="color" class="intro-app-text-color-custom" value="' + (app.text_color_custom || '#c9a84c') + '">');
        if (app.text_color !== 'custom') $textCustom.hide();
        $row1.append($textCustom);

        var $bgSel = $('<select class="theme-input-style intro-app-bg-color"></select>');
        [['transparent', '{{ translate('None') }}'], ['accent', '{{ translate('Accent') }}'], ['text', '{{ translate('Text') }}'], ['custom', '{{ translate('Custom') }}']].forEach(function(o) {
            $bgSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $bgSel.val(app.background_color);
        $row1.append('<label class="small mb-0 ml-2">{{ translate('Background') }}</label>').append($bgSel);
        var $bgCustom = $('<input type="color" class="intro-app-bg-color-custom" value="' + (app.background_color_custom || '#1a3d2a') + '">');
        if (app.background_color !== 'custom') $bgCustom.hide();
        $row1.append($bgCustom);

        var $row2 = $('<div class="intro-sep-row d-flex flex-wrap gap-2 align-items-center"></div>');
        $row2.append('<label class="small mb-0">{{ translate('Pad X') }}</label>');
        $row2.append('<input type="number" class="theme-input-style intro-app-padding-x" min="0" max="64" style="width:56px" value="' + app.padding_x + '">');
        $row2.append('<label class="small mb-0 ml-1">{{ translate('Pad Y') }}</label>');
        $row2.append('<input type="number" class="theme-input-style intro-app-padding-y" min="0" max="64" style="width:56px" value="' + app.padding_y + '">');
        $row2.append('<label class="small mb-0 ml-1">{{ translate('Radius') }}</label>');
        $row2.append('<input type="number" class="theme-input-style intro-app-border-radius" min="0" max="32" style="width:56px" value="' + app.border_radius + '">');

        $panel.append($row1).append($row2);
        return $panel;
    }

    function buildMixturePartRow(part, index) {
        var $row = $('<div class="intro-mixture-part d-flex flex-wrap gap-2 align-items-center mb-1"></div>');
        $row.data('index', index);
        var $typeSel = $('<select class="theme-input-style intro-mix-type"></select>');
        [['line', '{{ translate('Line') }}'], ['dashed', '{{ translate('Dashed') }}'], ['double', '{{ translate('Double') }}'], ['dot', '{{ translate('Dot') }}'], ['diamond', '{{ translate('Diamond') }}'], ['dots', '{{ translate('Three dots') }}'], ['gap', '{{ translate('Gap') }}']].forEach(function(o) {
            $typeSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $typeSel.val(part.type || 'line');
        $row.append($typeSel);

        var $widthSel = $('<select class="theme-input-style intro-mix-width"></select>');
        [['short', '{{ translate('Short') }}'], ['medium', '{{ translate('Medium') }}'], ['wide', '{{ translate('Wide') }}'], ['full', '{{ translate('Full') }}']].forEach(function(o) {
            $widthSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $widthSel.val(part.width || 'wide');
        if (part.type === 'gap' || part.type === 'dot' || part.type === 'diamond' || part.type === 'dots') {
            $widthSel.hide();
        }
        $row.append($widthSel);

        var $sizeInput = $('<input type="number" class="theme-input-style intro-mix-size" min="4" max="32" style="width:56px" value="' + (part.size || 12) + '">');
        if (part.type !== 'gap') $sizeInput.hide();
        $row.append($sizeInput);

        $row.append('<button type="button" class="btn btn-sm btn-outline-secondary intro-mix-up">&uarr;</button>');
        $row.append('<button type="button" class="btn btn-sm btn-outline-secondary intro-mix-down">&darr;</button>');
        $row.append('<button type="button" class="btn btn-sm btn-outline-danger intro-mix-remove">&times;</button>');
        return $row;
    }

    function buildMixturePartsList(parts) {
        var $list = $('<div class="intro-mixture-parts-list"></div>');
        (parts || MIXTURE_LINE_DIAMOND_PRESET).forEach(function(part, i) {
            $list.append(buildMixturePartRow(part, i));
        });
        return $list;
    }

    function buildSeparatorPanel(block) {
        var sep = $.extend({}, DEFAULT_SEPARATOR, block);
        var $panel = $('<div class="intro-block-separator-panel mt-2"></div>');
        $panel.data('block-id', block.id);

        var styleOpts = [
            ['dot', '{{ translate('Dot') }}'],
            ['line', '{{ translate('Line') }}'],
            ['dashed', '{{ translate('Dashed') }}'],
            ['double', '{{ translate('Double') }}'],
            ['diamond', '{{ translate('Diamond') }}'],
            ['dots', '{{ translate('Three dots') }}'],
            ['mixture', '{{ translate('Mixture (custom)') }}']
        ];
        var widthOpts = [
            ['short', '{{ translate('Short') }}'],
            ['medium', '{{ translate('Medium') }}'],
            ['wide', '{{ translate('Wide') }}'],
            ['full', '{{ translate('Full') }}']
        ];
        var colorOpts = [
            ['accent', '{{ translate('Accent') }}'],
            ['text', '{{ translate('Text') }}'],
            ['custom', '{{ translate('Custom') }}']
        ];

        var $row1 = $('<div class="intro-sep-row d-flex flex-wrap gap-2 mb-2"></div>');
        var $styleSel = $('<select class="theme-input-style intro-sep-style"></select>');
        styleOpts.forEach(function(o) {
            $styleSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $styleSel.val(sep.style);
        $row1.append($('<label class="small mb-0 align-self-center">{{ translate('Style') }}</label>')).append($styleSel);

        var $singleControls = $('<div class="intro-sep-single-controls d-flex flex-wrap gap-2 align-items-center"></div>');
        var $widthSel = $('<select class="theme-input-style intro-sep-width"></select>');
        widthOpts.forEach(function(o) {
            $widthSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $widthSel.val(sep.width);
        $singleControls.append($('<label class="small mb-0 align-self-center">{{ translate('Width') }}</label>')).append($widthSel);

        var $colorSel = $('<select class="theme-input-style intro-sep-color"></select>');
        colorOpts.forEach(function(o) {
            $colorSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $colorSel.val(sep.color);
        $singleControls.append($('<label class="small mb-0 align-self-center ml-2">{{ translate('Color') }}</label>')).append($colorSel);

        var $customColor = $('<input type="color" class="intro-sep-color-custom ml-1" value="' + (sep.color_custom || '#c9a84c') + '">');
        if (sep.color !== 'custom') $customColor.hide();
        $singleControls.append($customColor);

        $singleControls.append('<label class="small mb-0 ml-2">{{ translate('Thickness') }}</label>');
        $singleControls.append('<input type="number" class="theme-input-style intro-sep-thickness" min="1" max="4" style="width:60px" value="' + sep.thickness + '">');

        if (sep.style === 'mixture') {
            $singleControls.hide();
        }

        var $mixtureWrap = $('<div class="intro-sep-mixture-wrap mt-2"></div>');
        $mixtureWrap.append('<div class="intro-panel-label">{{ translate('Mixture parts') }}</div>');
        $mixtureWrap.append(buildMixturePartsList(block.mixture_parts));
        var $mixBtns = $('<div class="d-flex gap-2 mt-1"></div>');
        $mixBtns.append('<button type="button" class="btn btn-sm btn-outline-dark intro-mix-add">{{ translate('Add part') }}</button>');
        $mixBtns.append('<button type="button" class="btn btn-sm btn-outline-secondary intro-mix-preset">{{ translate('Line · diamond · line') }}</button>');
        $mixtureWrap.append($mixBtns);
        if (sep.style !== 'mixture') {
            $mixtureWrap.hide();
        }

        $panel.append($row1).append($singleControls).append($mixtureWrap);
        return $panel;
    }

    function renderBlockList() {
        destroyBlockEditors();
        var blocks = getBlocks();
        var $list = $('#intro-blocks-list');
        $list.empty();

        blocks.forEach(function(block, index) {
            var label = BLOCK_LABELS[block.type] || block.type;
            var hint = BLOCK_HINTS[block.type] || '';
            var $row = $('<div class="intro-block-row"></div>');
            var $main = $('<div class="intro-block-row__main"></div>');
            $main.append('<div class="intro-block-row__type">' + label + '</div>');
            $main.append('<div class="intro-block-row__hint">' + hint + '</div>');

            if (BLOCK_EDITOR_TYPES.indexOf(block.type) !== -1) {
                var editorId = 'intro-block-editor-' + block.id;
                var $editor = $('<textarea class="intro-block-editor theme-input-style mt-2" id="' + editorId + '"></textarea>');
                $editor.data('block-id', block.id);
                $editor.val(blockHtmlFallback(block));
                $main.append($editor);
            }

            if (block.type === 'separator') {
                $main.append(buildSeparatorPanel(block));
            }

            if (block.type === 'secondary_link') {
                var $urlInput = $('<input type="text" class="theme-input-style mt-2 intro-block-secondary-url" placeholder="/products">');
                $urlInput.val(block.url || $('#intro-secondary-url-input').val() || '');
                $urlInput.data('block-id', block.id);
                $main.append($urlInput);
            }

            if (APPEARANCE_BLOCK_TYPES.indexOf(block.type) !== -1) {
                $main.append(buildTextAppearancePanel(block));
            }

            $main.append(buildBlockSpacingPanel(block));

            var $actions = $('<div class="intro-block-row__actions"></div>');
            var $enable = $('<label class="mb-0"><input type="checkbox" class="intro-block-enable" ' + (block.enabled !== false ? 'checked' : '') + '> {{ translate('On') }}</label>');
            $enable.find('input').data('block-id', block.id);
            var $up = $('<button type="button" class="btn btn-sm btn-outline-secondary intro-block-up">&uarr;</button>');
            var $down = $('<button type="button" class="btn btn-sm btn-outline-secondary intro-block-down">&darr;</button>');
            $up.data('index', index);
            $down.data('index', index);
            if (index === 0) $up.prop('disabled', true);
            if (index === blocks.length - 1) $down.prop('disabled', true);

            $actions.append($enable).append($up).append($down);
            $row.append($main).append($actions);
            $list.append($row);
        });

        initBlockEditors();
    }

    function toggleLayoutPanels() {
        var layout = $('#intro-layout-input').val() || 'minimal';
        var isCustom = layout === 'custom_stack';
        $('#intro-custom-stack-panel').toggleClass('d-none', !isCustom);
        $('#intro-legacy-color-fields').toggleClass('d-none', isCustom);
        $('.intro-legacy-only').toggleClass('d-none', isCustom);
        $('#intro-global-copy-fields').toggleClass('d-none', isCustom);
        $('#intro-custom-stack-title-note').toggleClass('d-none', !isCustom);
        $('#preview-legacy-wrap').toggleClass('d-none', isCustom);
        $('#preview-stack-wrap').toggleClass('d-none', !isCustom);
    }

    function initCustomStackIfNeeded() {
        var layout = $('#intro-layout-input').val() || 'minimal';
        if (layout !== 'custom_stack') {
            return;
        }
        var blocks = getBlocks();
        if (!blocks.length) {
            setBlocks(DEFAULT_BLOCKS.slice());
        }
        var theme = getTheme();
        if (!theme || !Object.keys(theme).length) {
            setTheme(buildThemeFromLegacy());
        }
    }

    function collectBlocksFromDom() {
        var blocks = getBlocks();
        $('#intro-blocks-list .intro-block-row').each(function(index) {
            var block = blocks[index];
            if (!block) {
                return;
            }
            var $row = $(this);

            var $editor = $row.find('.intro-block-editor');
            if ($editor.length) {
                var code = '';
                if ($editor.next('.note-editor').length) {
                    code = $editor.summernote('code') || '';
                } else {
                    code = $editor.val() || '';
                }
                block.html = code;
            }

            var $sepPanel = $row.find('.intro-block-separator-panel');
            if ($sepPanel.length) {
                block.style = $sepPanel.find('.intro-sep-style').val() || 'dot';
                block.width = $sepPanel.find('.intro-sep-width').val() || 'medium';
                block.color = $sepPanel.find('.intro-sep-color').val() || 'accent';
                block.color_custom = $sepPanel.find('.intro-sep-color-custom').val() || '#c9a84c';
                block.thickness = parseInt($sepPanel.find('.intro-sep-thickness').val(), 10) || 1;
                if (block.style === 'mixture') {
                    block.mixture_parts = [];
                    $sepPanel.find('.intro-mixture-part').each(function() {
                        var $part = $(this);
                        var pType = $part.find('.intro-mix-type').val() || 'line';
                        var part = { type: pType };
                        if (pType === 'gap') {
                            part.size = parseInt($part.find('.intro-mix-size').val(), 10) || 12;
                        } else if (pType === 'line' || pType === 'dashed' || pType === 'double') {
                            part.width = $part.find('.intro-mix-width').val() || 'wide';
                        }
                        block.mixture_parts.push(part);
                    });
                }
            }

            var $spPanel = $row.find('.intro-block-spacing-panel');
            if ($spPanel.length) {
                block.spacing = {
                    margin_top: parseInt($spPanel.find('.intro-sp-margin-top').val(), 10) || 0,
                    margin_bottom: parseInt($spPanel.find('.intro-sp-margin-bottom').val(), 10) || 16,
                    padding_top: parseInt($spPanel.find('.intro-sp-padding-top').val(), 10) || 0,
                    padding_bottom: parseInt($spPanel.find('.intro-sp-padding-bottom').val(), 10) || 0,
                    padding_x: parseInt($spPanel.find('.intro-sp-padding-x').val(), 10) || 0
                };
            }

            var $appPanel = $row.find('.intro-block-appearance-panel');
            if ($appPanel.length) {
                block.appearance = {
                    text_color: $appPanel.find('.intro-app-text-color').val() || 'inherit',
                    text_color_custom: $appPanel.find('.intro-app-text-color-custom').val() || '#c9a84c',
                    background_color: $appPanel.find('.intro-app-bg-color').val() || 'transparent',
                    background_color_custom: $appPanel.find('.intro-app-bg-color-custom').val() || '#1a3d2a',
                    padding_x: parseInt($appPanel.find('.intro-app-padding-x').val(), 10) || 0,
                    padding_y: parseInt($appPanel.find('.intro-app-padding-y').val(), 10) || 0,
                    border_radius: parseInt($appPanel.find('.intro-app-border-radius').val(), 10) || 0
                };
            }

            var $secUrl = $row.find('.intro-block-secondary-url');
            if ($secUrl.length) {
                block.url = $secUrl.val() || '';
            }

            block.enabled = $row.find('.intro-block-enable').is(':checked');
        });
        setBlocks(blocks);
    }

    function syncBlocksToGlobalFields(blocks) {
        blocks.forEach(function(block) {
            if (!block.html) {
                return;
            }
            if (block.type === 'title') {
                var plainTitle = stripHtmlTags(block.html);
                if (plainTitle) {
                    $('#quiz-title-input').val(plainTitle);
                }
            }
            if (block.type === 'subtitle') {
                $('#intro-subtitle-input').val(stripHtmlTags(block.html));
            }
            if (block.type === 'body') {
                if ($('#quiz-description-input').summernote) {
                    $('#quiz-description-input').summernote('code', block.html);
                } else {
                    $('#quiz-description-input').val(block.html);
                }
            }
            if (block.type === 'cta') {
                $('#intro-cta-input').val(stripHtmlTags(block.html));
            }
            if (block.type === 'secondary_link') {
                $('#intro-secondary-text-input').val(stripHtmlTags(block.html));
                if (block.url) {
                    $('#intro-secondary-url-input').val(block.url);
                }
            }
        });
    }

    function flushIntroBuilderState() {
        if (($('#intro-layout-input').val() || 'minimal') !== 'custom_stack') {
            return;
        }
        collectBlocksFromDom();
        var blocks = getBlocks();
        syncBlocksToGlobalFields(blocks);
        readThemeFromPanel();
    }

    function repairHiddenJsonOnInit() {
        try {
            JSON.parse($('#intro-blocks-json').val() || '[]');
        } catch (e) {
            setBlocks(DEFAULT_BLOCKS.slice());
        }
        try {
            JSON.parse($('#intro-theme-json').val() || '{}');
        } catch (e) {
            setTheme(buildThemeFromLegacy());
        }
    }

    function toggleBackgroundFields() {
        var type = $('#intro-theme-bg-type').val() || 'solid';
        $('.intro-theme-solid-row').toggleClass('d-none', type !== 'solid');
        $('.intro-theme-gradient-rows').toggleClass('d-none', type !== 'radial_gradient');
    }

    function mixturePartPreviewEl(part, block, theme, thickness) {
        var color = separatorColorValue(block, theme);
        var $el = $('<span class="quiz-stack-preview__mix-part"></span>');
        var pType = part.type || 'line';

        if (pType === 'gap') {
            return $('<span class="quiz-stack-preview__mix-gap"></span>').css({ width: (part.size || 12) + 'px', display: 'inline-block' });
        }
        if (pType === 'dot') {
            return $('<span></span>').css({ width: '6px', height: '6px', borderRadius: '50%', background: color, display: 'inline-block' });
        }
        if (pType === 'diamond') {
            return $('<span></span>').css({ width: '8px', height: '8px', background: color, transform: 'rotate(45deg)', display: 'inline-block' });
        }
        if (pType === 'dots') {
            var $dots = $('<span class="quiz-stack-preview__mix-dots"></span>').css({ display: 'inline-flex', gap: '4px' });
            for (var i = 0; i < 3; i++) {
                $dots.append($('<span></span>').css({ width: '4px', height: '4px', borderRadius: '50%', background: color }));
            }
            return $dots;
        }
        var w = separatorWidthCss(part.width || 'wide');
        if (pType === 'double') {
            return $('<span></span>').css({ width: w, height: (thickness * 2 + 2) + 'px', borderTop: thickness + 'px solid ' + color, borderBottom: thickness + 'px solid ' + color, display: 'inline-block', opacity: 0.7 });
        }
        if (pType === 'dashed') {
            return $('<span></span>').css({ width: w, height: '0', borderTop: thickness + 'px dashed ' + color, display: 'inline-block', opacity: 0.7 });
        }
        return $('<span></span>').css({ width: w, height: thickness + 'px', background: color, display: 'inline-block', opacity: 0.5 });
    }

    function appendSeparatorPreview($inner, block, theme, alignment) {
        var spWrap = blockSpacingStyles(block);
        var style = block.style || 'dot';
        var thickness = parseInt(block.thickness, 10) || 1;

        if (style === 'mixture') {
            var parts = block.mixture_parts || MIXTURE_LINE_DIAMOND_PRESET;
            var $mix = $('<div class="quiz-stack-preview__separator quiz-stack-preview__mixture"></div>').css($.extend({}, spWrap, {
                display: 'flex',
                alignItems: 'center',
                justifyContent: alignment === 'right' ? 'flex-end' : (alignment === 'left' ? 'flex-start' : 'center'),
                gap: '0',
                width: '100%'
            }));
            parts.forEach(function(part) {
                $mix.append(mixturePartPreviewEl(part, block, theme, thickness));
            });
            $inner.append($mix);
            return;
        }

        var css = separatorPreviewStyles(block, theme);

        if (style === 'dots') {
            var color = separatorColorValue(block, theme);
            var $wrap = $('<div class="quiz-stack-preview__separator"></div>').css($.extend({}, spWrap, {
                width: css.width,
                display: 'flex',
                gap: '6px',
                justifyContent: alignment === 'right' ? 'flex-end' : (alignment === 'left' ? 'flex-start' : 'center')
            }));
            for (var i = 0; i < 3; i++) {
                $wrap.append($('<span></span>').css({
                    width: '5px',
                    height: '5px',
                    borderRadius: '50%',
                    background: color,
                    display: 'inline-block'
                }));
            }
            $inner.append($wrap);
            return;
        }

        var $sep = $('<div class="quiz-stack-preview__separator quiz-stack-preview__sep-' + style + '"></div>').css($.extend({}, spWrap, css));
        if (alignment === 'center') {
            $sep.css({ marginLeft: 'auto', marginRight: 'auto' });
        } else if (alignment === 'right') {
            $sep.css({ marginLeft: 'auto', marginRight: 0 });
        }
        $inner.append($sep);
    }

    function appendPreviewBlock($inner, className, extraClass, html, block, theme, extraStyle) {
        if (!html && html !== 0) return;
        var $el = $('<div class="' + className + (extraClass ? ' ' + extraClass : '') + '"></div>').html(html);
        $el.css($.extend({}, blockWrapperStyles(block, theme), extraStyle || {}));
        $inner.append($el);
    }

    function updateStackPreview() {
        var theme = readThemeFromPanel();
        var blocks = getBlocks().filter(function(b) { return b.enabled !== false; });
        var alignment = theme.alignment || 'center';
        var $preview = $('#quiz-stack-preview');

        var bg = theme.background_color || '#ffffff';
        if (theme.background_type === 'radial_gradient') {
            var center = theme.background_gradient_center || theme.background_color || '#ffffff';
            var edge = theme.background_gradient_edge || '#050a07';
            bg = 'radial-gradient(circle at center, ' + center + ' 0%, ' + edge + ' 100%)';
        }

        $preview.removeClass('text-left text-center text-right').addClass('text-' + alignment);
        $preview.css({
            background: bg,
            color: theme.text_color || '#111111',
            minHeight: (theme.min_height || 400) + 'px',
            '--stack-accent': theme.accent_color || theme.text_color || '#111111',
            '--stack-btn': theme.button_color || '#ff5a1f',
            '--stack-btn-text': theme.button_text_color || '#ffffff'
        });

        var heroUrl = getHeroPreviewUrl();
        var heroSize = theme.hero_size || 140;
        var $inner = $('<div class="quiz-stack-preview__inner"></div>').css('maxWidth', (theme.content_max_width || 560) + 'px');

        blocks.forEach(function(block) {
            if (block.type === 'hero_image' && heroUrl) {
                var $hero = $('<div class="quiz-stack-preview__hero"></div>').css($.extend({}, blockSpacingStyles(block, { fullWidth: false }), { width: heroSize, height: heroSize }));
                if (theme.hero_glow) $hero.addClass('quiz-stack-preview__hero--glow');
                if (theme.hero_frame === 'corners') {
                    var $frame = $('<div class="quiz-stack-preview__hero-frame"></div>');
                    $frame.append('<img src="' + heroUrl + '" alt="">');
                    $hero.append($frame);
                } else {
                    $hero.append('<img src="' + heroUrl + '" alt="">');
                }
                $inner.append($hero);
            } else if (block.type === 'title') {
                appendPreviewBlock($inner, 'quiz-stack-preview__title', '', blockHtmlFallback(block) || '<span>{{ translate('Quiz Title') }}</span>', block, theme);
            } else if (block.type === 'subtitle') {
                appendPreviewBlock($inner, 'quiz-stack-preview__subtitle', '', blockHtmlFallback(block), block, theme);
            } else if (block.type === 'tagline') {
                var tagHtml = blockHtmlFallback(block);
                if (tagHtml) {
                    var tagClass = 'quiz-stack-preview__tagline' + (theme.tagline_font === 'serif_caps' ? ' quiz-stack-preview__tagline--serif' : '');
                    appendPreviewBlock($inner, tagClass, '', tagHtml, block, theme);
                }
            } else if (block.type === 'body') {
                var desc = blockHtmlFallback(block);
                if (desc) {
                    var bodyClass = 'quiz-stack-preview__body' + (theme.body_font === 'script' ? ' quiz-stack-preview__body--script' : '');
                    appendPreviewBlock($inner, bodyClass, '', desc, block, theme);
                }
            } else if (block.type === 'separator') {
                appendSeparatorPreview($inner, block, theme, alignment);
            } else if (block.type === 'meta') {
                var metaParts = [];
                if ($('#intro-show-count-input').is(':checked') && previewQuestionCount > 0) {
                    metaParts.push(previewQuestionCount + ' {{ translate('questions') }}');
                }
                if ($('#intro-show-time-input').is(':checked')) {
                    metaParts.push('~' + ($('#intro-minutes-input').val() || 2) + ' {{ translate('min') }}');
                }
                if (metaParts.length) {
                    appendPreviewBlock($inner, 'quiz-stack-preview__meta', '', metaParts.join(' · '), block, theme);
                }
            } else if (block.type === 'cta') {
                var ctaHtml = blockHtmlFallback(block) || '{{ translate('Start Quiz') }}';
                var ctaClass = 'quiz-stack-preview__cta' + (theme.button_style === 'gradient_glow' ? ' quiz-stack-preview__cta--glow' : '');
                var $ctaWrap = $('<div></div>').css(blockSpacingStyles(block));
                var $btn = $('<button type="button" class="' + ctaClass + '">' + ctaHtml + '</button>');
                $btn.css(ctaAppearanceStyles(block, theme));
                $ctaWrap.append($btn);
                $inner.append($ctaWrap);
            } else if (block.type === 'secondary_link') {
                var secHtml = blockHtmlFallback(block);
                var secUrl = block.url || $('#intro-secondary-url-input').val() || '';
                if (secHtml && secUrl) {
                    appendPreviewBlock($inner, 'quiz-stack-preview__secondary', '', secHtml, block, theme);
                }
            }
        });

        $preview.empty().append($inner);
    }

    function updateLegacyPreview() {
        var layout = $('#intro-layout-input').val() || 'minimal';
        var alignment = $('#intro-alignment-input').val() || 'center';
        var $preview = $('#quiz-intro-preview');

        $preview.removeClass('quiz-admin-preview--minimal quiz-admin-preview--hero_centered quiz-admin-preview--hero_image_left quiz-admin-preview--hero_square_top quiz-admin-preview--hero_square_bottom quiz-admin-preview--full_bleed quiz-admin-preview--custom_stack');
        $preview.removeClass('text-left text-center text-right');
        $preview.addClass('quiz-admin-preview--' + layout);
        $preview.addClass('text-' + alignment);

        $preview.css({
            '--quiz-bg': hexFromInput('intro-bg-input', '#ffffff'),
            '--quiz-text': hexFromInput('intro-text-input', '#111111'),
            '--quiz-btn': hexFromInput('intro-btn-input', '#ff5a1f'),
            '--quiz-btn-text': hexFromInput('intro-btn-text-input', '#ffffff'),
            '--quiz-overlay': hexFromInput('intro-overlay-input', '#000000'),
            '--quiz-overlay-opacity': (parseInt($('#intro-overlay-opacity-input').val(), 10) || 40) / 100
        });

        $('#preview-title').text($('#quiz-title-input').val() || '{{ translate('Quiz Title') }}');
        $('#preview-subtitle').text($('#intro-subtitle-input').val()).toggle(!!$('#intro-subtitle-input').val());
        var desc = $('#quiz-description-input').summernote ? $('#quiz-description-input').summernote('code') : $('#quiz-description-input').val();
        $('#preview-description').html(desc || '');
        $('#preview-cta').text($('#intro-cta-input').val() || '{{ translate('Start Quiz') }}');
        var secText = $('#intro-secondary-text-input').val();
        var secUrl = $('#intro-secondary-url-input').val();
        $('#preview-secondary').text(secText);
        $('#preview-secondary-wrap').toggle(!!(secText && secUrl));

        var metaParts = [];
        if ($('#intro-show-count-input').is(':checked') && previewQuestionCount > 0) {
            metaParts.push(previewQuestionCount + ' {{ translate('questions') }}');
        }
        if ($('#intro-show-time-input').is(':checked')) {
            metaParts.push('~' + ($('#intro-minutes-input').val() || 2) + ' {{ translate('min') }}');
        }
        $('#preview-meta').text(metaParts.join(' · ')).toggle(metaParts.length > 0);
        $('#preview-content').removeClass('text-left text-center text-right').addClass('text-' + alignment);

        var heroUrl = getHeroPreviewUrl();
        if (layout === 'hero_image_left') {
            $('#preview-hero-wrap').css('background-image', 'none');
            $('#preview-square-top, #preview-square-bottom').hide();
            var styleEl = document.getElementById('preview-hero-left-style');
            if (!styleEl) {
                styleEl = document.createElement('style');
                styleEl.id = 'preview-hero-left-style';
                document.head.appendChild(styleEl);
            }
            styleEl.textContent = '.quiz-admin-preview--hero_image_left .quiz-admin-preview__hero::before { background-image: ' + (heroUrl ? "url('" + heroUrl + "')" : 'none') + '; }';
        } else if (layout === 'hero_square_top') {
            $('#preview-hero-wrap').css('background-image', 'none');
            $('#preview-square-bottom').hide();
            if (heroUrl) {
                $('#preview-square-img-top').attr('src', heroUrl);
                $('#preview-square-top').show();
            } else {
                $('#preview-square-top').hide();
            }
        } else if (layout === 'hero_square_bottom') {
            $('#preview-hero-wrap').css('background-image', 'none');
            $('#preview-square-top').hide();
            if (heroUrl) {
                $('#preview-square-img-bottom').attr('src', heroUrl);
                $('#preview-square-bottom').show();
            } else {
                $('#preview-square-bottom').hide();
            }
        } else if (layout !== 'minimal') {
            $('#preview-square-top, #preview-square-bottom').hide();
            $('#preview-hero-wrap').css('background-image', heroUrl ? "url('" + heroUrl + "')" : 'none');
        } else {
            $('#preview-square-top, #preview-square-bottom').hide();
            $('#preview-hero-wrap').css('background-image', 'none');
        }
    }

    window.syncQuizIntroBuilder = function() {
        if (($('#intro-layout-input').val() || 'minimal') === 'custom_stack') {
            collectBlocksFromDom();
        }
        readThemeFromPanel();
        window.updateQuizIntroPreview();
    };

    window.updateQuizIntroPreview = function() {
        toggleLayoutPanels();
        var layout = $('#intro-layout-input').val() || 'minimal';
        if (layout === 'custom_stack') {
            updateStackPreview();
        } else {
            updateLegacyPreview();
        }
    };

    $('#copy-share-url').on('click', function() {
        var input = document.getElementById('quiz-share-url');
        if (input) {
            input.select();
            document.execCommand('copy');
        }
    });

    $('#intro-layout-input').on('change', function() {
        initCustomStackIfNeeded();
        renderBlockList();
        syncQuizIntroBuilder();
    });

    $('#intro-theme-bg-type').on('change', function() {
        toggleBackgroundFields();
        syncQuizIntroBuilder();
    });

    $(document).on('input change', '.intro-theme-input', function() {
        syncQuizIntroBuilder();
    });

    $(document).on('change', '.intro-block-enable', function() {
        var id = $(this).data('block-id');
        var enabled = $(this).is(':checked');
        collectBlocksFromDom();
        var blocks = getBlocks();
        blocks.forEach(function(b) {
            if (b.id === id) {
                b.enabled = enabled;
            }
        });
        setBlocks(blocks);
        syncQuizIntroBuilder();
    });

    $(document).on('change input', '.intro-block-separator-panel select, .intro-block-separator-panel input', function() {
        var $panel = $(this).closest('.intro-block-separator-panel');
        if ($(this).hasClass('intro-sep-color')) {
            $panel.find('.intro-sep-color-custom').toggle($(this).val() === 'custom');
        }
        if ($(this).hasClass('intro-sep-style')) {
            var isMix = $(this).val() === 'mixture';
            $panel.find('.intro-sep-single-controls').toggle(!isMix);
            $panel.find('.intro-sep-mixture-wrap').toggle(isMix);
        }
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('change input', '.intro-block-spacing-panel input, .intro-block-appearance-panel select, .intro-block-appearance-panel input', function() {
        var $panel = $(this).closest('.intro-block-appearance-panel');
        if ($panel.length && $(this).hasClass('intro-app-text-color')) {
            $panel.find('.intro-app-text-color-custom').toggle($(this).val() === 'custom');
        }
        if ($panel.length && $(this).hasClass('intro-app-bg-color')) {
            $panel.find('.intro-app-bg-color-custom').toggle($(this).val() === 'custom');
        }
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('change', '.intro-mix-type', function() {
        var $row = $(this).closest('.intro-mixture-part');
        var t = $(this).val();
        $row.find('.intro-mix-width').toggle(t === 'line' || t === 'dashed' || t === 'double');
        $row.find('.intro-mix-size').toggle(t === 'gap');
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('click', '.intro-mix-add', function() {
        var $list = $(this).closest('.intro-sep-mixture-wrap').find('.intro-mixture-parts-list');
        $list.append(buildMixturePartRow({ type: 'line', width: 'medium' }, $list.find('.intro-mixture-part').length));
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('click', '.intro-mix-preset', function() {
        var $wrap = $(this).closest('.intro-sep-mixture-wrap');
        var $list = $wrap.find('.intro-mixture-parts-list');
        $list.empty();
        MIXTURE_LINE_DIAMOND_PRESET.forEach(function(part, i) {
            $list.append(buildMixturePartRow(part, i));
        });
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('click', '.intro-mix-remove', function() {
        $(this).closest('.intro-mixture-part').remove();
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('click', '.intro-mix-up', function() {
        var $row = $(this).closest('.intro-mixture-part');
        var $prev = $row.prev('.intro-mixture-part');
        if ($prev.length) $row.insertBefore($prev);
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('click', '.intro-mix-down', function() {
        var $row = $(this).closest('.intro-mixture-part');
        var $next = $row.next('.intro-mixture-part');
        if ($next.length) $row.insertAfter($next);
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('input', '.intro-block-secondary-url', function() {
        collectBlocksFromDom();
        syncQuizIntroBuilder();
    });

    $(document).on('click', '.intro-block-up', function() {
        collectBlocksFromDom();
        var index = $(this).data('index');
        var blocks = getBlocks();
        if (index > 0) {
            var tmp = blocks[index - 1];
            blocks[index - 1] = blocks[index];
            blocks[index] = tmp;
            setBlocks(blocks);
            renderBlockList();
            syncQuizIntroBuilder();
        }
    });

    $(document).on('click', '.intro-block-down', function() {
        collectBlocksFromDom();
        var index = $(this).data('index');
        var blocks = getBlocks();
        if (index < blocks.length - 1) {
            var tmp = blocks[index + 1];
            blocks[index + 1] = blocks[index];
            blocks[index] = tmp;
            setBlocks(blocks);
            renderBlockList();
            syncQuizIntroBuilder();
        }
    });

    $('#intro-reset-blocks-btn').on('click', function() {
        setBlocks(DEFAULT_BLOCKS.slice());
        renderBlockList();
        syncQuizIntroBuilder();
    });

    $('#quiz-form').on('submit', function() {
        flushIntroBuilderState();
    });

    $('#quiz-description-input').summernote({
        height: 180,
        callbacks: {
            onChange: function() {
                updateQuizIntroPreview();
            }
        }
    });

    $(document).on('input change', '.quiz-preview-input, .quiz-color-input', updateQuizIntroPreview);
    $(document).on('change', 'input[name="intro_hero_image_desktop"], input[name="intro_hero_image_mobile"]', function() {
        setTimeout(updateQuizIntroPreview, 300);
    });

    initDropzone();
    $(document).ready(function() {
        is_for_browse_file = true;
        filtermedia();
        @if (!empty($intro['hero_image_desktop']))
            @php
                $previewHeroPath = preg_replace('#^/public#', '', getFilePath($intro['hero_image_desktop'], false) ?: '');
            @endphp
            $('#preview-hero-wrap').data('desktop-url', '{{ $previewHeroPath }}');
        @endif
        toggleBackgroundFields();
        repairHiddenJsonOnInit();
        initCustomStackIfNeeded();
        renderBlockList();
        updateQuizIntroPreview();
    });
})(jQuery);
</script>
