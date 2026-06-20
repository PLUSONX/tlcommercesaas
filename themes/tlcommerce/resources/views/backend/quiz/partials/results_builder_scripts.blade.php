<script>
(function($) {
    "use strict";

    var MOCK_PRODUCT = {
        name: 'CUOIO',
        url: '/products/cuoio',
        summary: 'Like soft morning sunlight on warm leather.',
        price: '$120',
        match_pct: 92,
        rank: 1,
        image: ''
    };

    var BLOCK_EDITOR_TYPES = ['title', 'subtitle', 'tagline', 'body', 'meta'];
    var BLOCK_LABELS = {
        hero_image: '{{ translate('Icon / hero image') }}',
        title: '{{ translate('Title') }}',
        subtitle: '{{ translate('Subtitle') }}',
        tagline: '{{ translate('Tagline') }}',
        body: '{{ translate('Body') }}',
        separator: '{{ translate('Separator') }}',
        meta: '{{ translate('Meta / footer') }}',
        product_image: '{{ translate('Product image') }}',
        match_badge: '{{ translate('Match badge') }}'
    };
    var BLOCK_HINTS = {
        hero_image: '{{ translate('Static icon upload for result card') }}',
        product_image: '{{ translate('Dynamic image from ranked product') }}',
        match_badge: '{{ translate('Shows match percentage for a product rank') }}',
        separator: '{{ translate('Decorative divider') }}'
    };

    var DEFAULT_BLOCKS = {!! json_encode(\Theme\TLCommerce\Http\Resources\QuizResultsConfig::defaultBlocks()) !!};
    var DEFAULT_ACTIONS = {!! json_encode(\Theme\TLCommerce\Http\Resources\QuizResultsConfig::defaultActions()) !!};

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

    var MIXTURE_LINE_DIAMOND_PRESET = [
        { type: 'line', width: 'wide' },
        { type: 'gap', size: 12 },
        { type: 'diamond' },
        { type: 'gap', size: 12 },
        { type: 'line', width: 'wide' }
    ];

    var summernoteOpts = {
        height: 100,
        toolbar: [
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onChange: function() { syncResultsBuilder(); }
        }
    };

    function hexFrom(id, fb) { return $('#' + id).val() || fb; }

    function parseJsonField(raw, fallback) {
        if (raw == null || raw === '') return fallback;
        try {
            var parsed = JSON.parse(raw);
            return parsed != null ? parsed : fallback;
        } catch (e) {
            try {
                var decoded = $('<textarea>').html(String(raw)).text();
                parsed = JSON.parse(decoded);
                return parsed != null ? parsed : fallback;
            } catch (e2) {
                return fallback;
            }
        }
    }

    function getBlocks() {
        return parseJsonField($('#results-blocks-json').val(), DEFAULT_BLOCKS.slice());
    }
    function setBlocks(b) { $('#results-blocks-json').val(JSON.stringify(b)); }

    function getActions() {
        return parseJsonField($('#results-actions-json').val(), DEFAULT_ACTIONS.slice());
    }
    function setActions(a) { $('#results-actions-json').val(JSON.stringify(a)); }

    function findExistingActionById(existing, actionId, index) {
        if (actionId) {
            for (var i = 0; i < existing.length; i++) {
                if (existing[i] && existing[i].id === actionId) return existing[i];
            }
        }
        return existing[index] || null;
    }

    function actionTypeUsesUrl(type) {
        return type === 'link' || type === 'top_product';
    }

    function actionsHaveUrls(list) {
        return (list || []).some(function(a) {
            return actionTypeUsesUrl(a.action) && String(a.url || '').trim() !== '';
        });
    }

    function domActionUrlsEmpty($rows) {
        var anyUrl = false;
        $rows.each(function() {
            var $r = $(this);
            if (!actionTypeUsesUrl($r.find('.results-action-type').val() || 'link')) return;
            if (String($r.find('.results-action-url').val() || '').trim() !== '') anyUrl = true;
        });
        return !anyUrl;
    }

    function syncActionRowUrlField($row) {
        var type = $row.find('.results-action-type').val() || 'link';
        var $urlWrap = $row.find('.results-action-url-wrap');
        var $hint = $row.find('.results-action-type-hint');
        if (type === 'link') {
            $urlWrap.show();
            $hint.hide().text('');
        } else if (type === 'top_product') {
            $urlWrap.show();
            $hint.show().text('{{ translate('Optional — leave empty to link to top matched product') }}');
        } else {
            $urlWrap.hide();
            $row.find('.results-action-url').val('');
            $hint.hide().text('');
        }
    }

    function repairActionsJsonOnInit() {
        if (!$('#results-actions-json').length) return;
        var parsed = parseJsonField($('#results-actions-json').val(), null);
        if (!Array.isArray(parsed)) {
            setActions(DEFAULT_ACTIONS.slice());
        }
    }

    function getTheme() {
        try { return JSON.parse($('#results-theme-json').val() || '{}'); }
        catch (e) { return {}; }
    }
    function setTheme(t) { $('#results-theme-json').val(JSON.stringify(t)); }

    function getProductGrid() {
        try { return JSON.parse($('#results-product-grid-json').val() || '{}'); }
        catch (e) { return {}; }
    }
    function setProductGrid(g) { $('#results-product-grid-json').val(JSON.stringify(g)); }

    function resolveTokens(text) {
        if (!text) return '';
        return String(text)
            .replace(/\{\{product\.name\}\}/g, MOCK_PRODUCT.name)
            .replace(/\{\{product\.url\}\}/g, MOCK_PRODUCT.url)
            .replace(/\{\{product\.summary\}\}/g, MOCK_PRODUCT.summary)
            .replace(/\{\{product\.price\}\}/g, MOCK_PRODUCT.price)
            .replace(/\{\{match_pct\}\}/g, String(MOCK_PRODUCT.match_pct))
            .replace(/\{\{rank\}\}/g, String(MOCK_PRODUCT.rank))
            .replace(/\{\{product_1\.name\}\}/g, MOCK_PRODUCT.name)
            .replace(/\{\{product_1\.match_pct\}\}/g, String(MOCK_PRODUCT.match_pct));
    }

    function readThemeFromPanel() {
        var t = getTheme();
        t.background_type = $('#results-bg-type').val() || 'solid';
        t.background_color = hexFrom('results-bg-color', '#1a3d2a');
        t.background_gradient_center = hexFrom('results-bg-center', '#1a3d2a');
        t.background_gradient_edge = hexFrom('results-bg-edge', '#050a07');
        t.alignment = $('#results-alignment').val() || 'center';
        t.text_color = hexFrom('results-text-color', '#ffffff');
        t.accent_color = hexFrom('results-accent-color', '#c9a84c');
        t.fill_viewport = $('#results-fill-viewport').is(':checked');
        t.card_enabled = $('#results-card-enabled').is(':checked');
        t.card_background = hexFrom('results-card-bg', '#1f4530');
        t.card_border_style = $('#results-card-border-style').val() || 'double';
        t.card_border_color = hexFrom('results-card-border-color', '#c9a84c');
        t.card_padding = parseInt($('#results-card-padding').val(), 10) || 32;
        t.card_max_width = parseInt($('#results-card-max-width').val(), 10) || 480;
        t.hero_size = parseInt($('#results-hero-size').val(), 10) || 80;
        t.hero_glow = true;
        t.tagline_font = 'serif_caps';
        t.body_font = 'script';
        setTheme(t);
        return t;
    }

    function readProductGridFromPanel() {
        var g = {
            show_heading: $('#results-grid-show-heading').is(':checked'),
            heading: $('#results-grid-heading').val() || '',
            subheading: $('#results-grid-subheading').val() || '',
            show_match_badge: $('#results-grid-show-badge').is(':checked'),
            match_badge_bg: hexFrom('results-badge-bg', '#ff5a1f'),
            match_badge_text: hexFrom('results-badge-text', '#ffffff')
        };
        setProductGrid(g);
        return g;
    }

    function getBlockSpacing(block) {
        var sp = block.spacing || {};
        if (block.spacing_top != null && sp.margin_top == null) sp.margin_top = block.spacing_top;
        if (block.spacing_bottom != null && sp.margin_bottom == null) sp.margin_bottom = block.spacing_bottom;
        return $.extend({}, DEFAULT_SPACING, sp);
    }

    function blockSpacingStyles(block) {
        var sp = getBlockSpacing(block);
        return {
            marginTop: sp.margin_top + 'px',
            marginBottom: sp.margin_bottom + 'px',
            paddingTop: sp.padding_top + 'px',
            paddingBottom: sp.padding_bottom + 'px',
            paddingLeft: sp.padding_x + 'px',
            paddingRight: sp.padding_x + 'px'
        };
    }

    function separatorColorValue(block, theme) {
        if (block.color === 'text') return theme.text_color || '#ffffff';
        if (block.color === 'custom') return block.color_custom || '#c9a84c';
        return theme.accent_color || '#c9a84c';
    }

    function separatorWidthCss(width) {
        var map = { short: '40px', medium: '80px', wide: '50%', full: '100%' };
        return map[width] || map.medium;
    }

    function separatorPreviewStyles(block, theme) {
        var color = separatorColorValue(block, theme);
        var style = block.style || 'dot';
        var thickness = parseInt(block.thickness, 10) || 1;
        var css = { color: color };
        if (style === 'dot') {
            css.width = '6px'; css.height = '6px'; css.borderRadius = '50%'; css.background = color;
        } else if (style === 'diamond') {
            css.width = '8px'; css.height = '8px'; css.background = color; css.transform = 'rotate(45deg)';
        } else if (style === 'dots') {
            css.width = separatorWidthCss(block.width || 'medium'); css.height = 'auto'; css.background = 'transparent';
            css.display = 'flex'; css.gap = '6px'; css.justifyContent = 'center';
        } else if (style === 'double') {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = (thickness * 2 + 2) + 'px';
            css.borderTop = thickness + 'px solid ' + color;
            css.borderBottom = thickness + 'px solid ' + color;
            css.background = 'transparent'; css.opacity = '0.7';
        } else if (style === 'dashed') {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = '0';
            css.borderTop = thickness + 'px dashed ' + color;
            css.background = 'transparent'; css.opacity = '0.7';
        } else {
            css.width = separatorWidthCss(block.width || 'medium');
            css.height = thickness + 'px';
            css.background = color; css.opacity = '0.5';
        }
        return css;
    }

    function buildResultsBlockSpacingPanel(block) {
        var sp = getBlockSpacing(block);
        var $panel = $('<div class="results-block-spacing-panel mt-2"></div>');
        $panel.append('<div class="intro-panel-label">{{ translate('Block spacing') }}</div>');
        var $row = $('<div class="results-sep-row d-flex flex-wrap gap-2 align-items-center"></div>');
        $row.append('<label class="small mb-0">{{ translate('Margin top') }}</label>');
        $row.append('<input type="number" class="theme-input-style results-sp-margin-top" min="0" max="64" style="width:56px" value="' + sp.margin_top + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Margin bottom') }}</label>');
        $row.append('<input type="number" class="theme-input-style results-sp-margin-bottom" min="0" max="64" style="width:56px" value="' + sp.margin_bottom + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Pad top') }}</label>');
        $row.append('<input type="number" class="theme-input-style results-sp-padding-top" min="0" max="64" style="width:56px" value="' + sp.padding_top + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Pad bottom') }}</label>');
        $row.append('<input type="number" class="theme-input-style results-sp-padding-bottom" min="0" max="64" style="width:56px" value="' + sp.padding_bottom + '">');
        $row.append('<label class="small mb-0 ml-1">{{ translate('Pad X') }}</label>');
        $row.append('<input type="number" class="theme-input-style results-sp-padding-x" min="0" max="64" style="width:56px" value="' + sp.padding_x + '">');
        $panel.append($row);
        return $panel;
    }

    function buildResultsMixturePartRow(part, index) {
        var $row = $('<div class="results-mixture-part d-flex flex-wrap gap-2 align-items-center mb-1"></div>');
        $row.data('index', index);
        var $typeSel = $('<select class="theme-input-style results-mix-type"></select>');
        [['line', '{{ translate('Line') }}'], ['dashed', '{{ translate('Dashed') }}'], ['double', '{{ translate('Double') }}'], ['dot', '{{ translate('Dot') }}'], ['diamond', '{{ translate('Diamond') }}'], ['dots', '{{ translate('Three dots') }}'], ['gap', '{{ translate('Gap') }}']].forEach(function(o) {
            $typeSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $typeSel.val(part.type || 'line');
        $row.append($typeSel);
        var $widthSel = $('<select class="theme-input-style results-mix-width"></select>');
        [['short', '{{ translate('Short') }}'], ['medium', '{{ translate('Medium') }}'], ['wide', '{{ translate('Wide') }}'], ['full', '{{ translate('Full') }}']].forEach(function(o) {
            $widthSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $widthSel.val(part.width || 'wide');
        if (part.type === 'gap' || part.type === 'dot' || part.type === 'diamond' || part.type === 'dots') $widthSel.hide();
        $row.append($widthSel);
        var $sizeInput = $('<input type="number" class="theme-input-style results-mix-size" min="4" max="32" style="width:56px" value="' + (part.size || 12) + '">');
        if (part.type !== 'gap') $sizeInput.hide();
        $row.append($sizeInput);
        $row.append('<button type="button" class="btn btn-sm btn-outline-secondary results-mix-up">&uarr;</button>');
        $row.append('<button type="button" class="btn btn-sm btn-outline-secondary results-mix-down">&darr;</button>');
        $row.append('<button type="button" class="btn btn-sm btn-outline-danger results-mix-remove">&times;</button>');
        return $row;
    }

    function buildResultsMixturePartsList(parts) {
        var $list = $('<div class="results-mixture-parts-list"></div>');
        (parts || MIXTURE_LINE_DIAMOND_PRESET).forEach(function(part, i) {
            $list.append(buildResultsMixturePartRow(part, i));
        });
        return $list;
    }

    function buildResultsSeparatorPanel(block) {
        var sep = $.extend({}, DEFAULT_SEPARATOR, block);
        var $panel = $('<div class="results-block-separator-panel mt-2"></div>');
        $panel.data('block-id', block.id);
        var styleOpts = [
            ['dot', '{{ translate('Dot') }}'], ['line', '{{ translate('Line') }}'], ['dashed', '{{ translate('Dashed') }}'],
            ['double', '{{ translate('Double') }}'], ['diamond', '{{ translate('Diamond') }}'], ['dots', '{{ translate('Three dots') }}'],
            ['mixture', '{{ translate('Mixture (custom)') }}']
        ];
        var widthOpts = [
            ['short', '{{ translate('Short') }}'], ['medium', '{{ translate('Medium') }}'],
            ['wide', '{{ translate('Wide') }}'], ['full', '{{ translate('Full') }}']
        ];
        var colorOpts = [
            ['accent', '{{ translate('Accent') }}'], ['text', '{{ translate('Text') }}'], ['custom', '{{ translate('Custom') }}']
        ];
        var $row1 = $('<div class="results-sep-row d-flex flex-wrap gap-2 mb-2"></div>');
        var $styleSel = $('<select class="theme-input-style results-sep-style"></select>');
        styleOpts.forEach(function(o) { $styleSel.append('<option value="' + o[0] + '">' + o[1] + '</option>'); });
        $styleSel.val(sep.style);
        $row1.append($('<label class="small mb-0 align-self-center">{{ translate('Style') }}</label>')).append($styleSel);
        var $singleControls = $('<div class="results-sep-single-controls d-flex flex-wrap gap-2 align-items-center"></div>');
        var $widthSel = $('<select class="theme-input-style results-sep-width"></select>');
        widthOpts.forEach(function(o) { $widthSel.append('<option value="' + o[0] + '">' + o[1] + '</option>'); });
        $widthSel.val(sep.width);
        $singleControls.append($('<label class="small mb-0 align-self-center">{{ translate('Width') }}</label>')).append($widthSel);
        var $colorSel = $('<select class="theme-input-style results-sep-color"></select>');
        colorOpts.forEach(function(o) { $colorSel.append('<option value="' + o[0] + '">' + o[1] + '</option>'); });
        $colorSel.val(sep.color);
        $singleControls.append($('<label class="small mb-0 align-self-center ml-2">{{ translate('Color') }}</label>')).append($colorSel);
        var $customColor = $('<input type="color" class="results-sep-color-custom ml-1" value="' + (sep.color_custom || '#c9a84c') + '">');
        if (sep.color !== 'custom') $customColor.hide();
        $singleControls.append($customColor);
        $singleControls.append('<label class="small mb-0 ml-2">{{ translate('Thickness') }}</label>');
        $singleControls.append('<input type="number" class="theme-input-style results-sep-thickness" min="1" max="4" style="width:60px" value="' + sep.thickness + '">');
        if (sep.style === 'mixture') $singleControls.hide();
        var $mixtureWrap = $('<div class="results-sep-mixture-wrap mt-2"></div>');
        $mixtureWrap.append('<div class="intro-panel-label">{{ translate('Mixture parts') }}</div>');
        $mixtureWrap.append(buildResultsMixturePartsList(block.mixture_parts));
        var $mixBtns = $('<div class="d-flex gap-2 mt-1"></div>');
        $mixBtns.append('<button type="button" class="btn btn-sm btn-outline-dark results-mix-add">{{ translate('Add part') }}</button>');
        $mixBtns.append('<button type="button" class="btn btn-sm btn-outline-secondary results-mix-preset">{{ translate('Line · diamond · line') }}</button>');
        $mixtureWrap.append($mixBtns);
        if (sep.style !== 'mixture') $mixtureWrap.hide();
        $panel.append($row1).append($singleControls).append($mixtureWrap);
        return $panel;
    }

    function spacingStyleString(block) {
        var sp = blockSpacingStyles(block);
        return Object.keys(sp).map(function(k) {
            return k.replace(/([A-Z])/g, '-$1').toLowerCase() + ':' + sp[k];
        }).join(';') + ';';
    }

    function renderSeparatorPreviewHtml(block, theme) {
        var spStyle = spacingStyleString(block);
        var style = block.style || 'dot';
        var thickness = parseInt(block.thickness, 10) || 1;
        var color = separatorColorValue(block, theme);
        if (style === 'mixture') {
            var parts = block.mixture_parts || MIXTURE_LINE_DIAMOND_PRESET;
            var inner = '';
            parts.forEach(function(part) {
                var pType = part.type || 'line';
                if (pType === 'gap') {
                    inner += '<span style="width:' + (part.size || 12) + 'px;display:inline-block"></span>';
                } else if (pType === 'dot') {
                    inner += '<span style="width:6px;height:6px;border-radius:50%;background:' + color + ';display:inline-block"></span>';
                } else if (pType === 'diamond') {
                    inner += '<span style="width:8px;height:8px;background:' + color + ';transform:rotate(45deg);display:inline-block"></span>';
                } else if (pType === 'dots') {
                    inner += '<span style="display:inline-flex;gap:4px"><span style="width:4px;height:4px;border-radius:50%;background:' + color + '"></span><span style="width:4px;height:4px;border-radius:50%;background:' + color + '"></span><span style="width:4px;height:4px;border-radius:50%;background:' + color + '"></span></span>';
                } else {
                    var w = separatorWidthCss(part.width || 'wide');
                    if (pType === 'double') {
                        inner += '<span style="width:' + w + ';height:' + (thickness * 2 + 2) + 'px;border-top:' + thickness + 'px solid ' + color + ';border-bottom:' + thickness + 'px solid ' + color + ';display:inline-block;opacity:0.7"></span>';
                    } else if (pType === 'dashed') {
                        inner += '<span style="width:' + w + ';height:0;border-top:' + thickness + 'px dashed ' + color + ';display:inline-block;opacity:0.7"></span>';
                    } else {
                        inner += '<span style="width:' + w + ';height:' + thickness + 'px;background:' + color + ';display:inline-block;opacity:0.5"></span>';
                    }
                }
            });
            return '<div style="display:flex;align-items:center;justify-content:center;gap:0;width:100%;' + spStyle + '">' + inner + '</div>';
        }
        if (style === 'dots') {
            var dots = '';
            for (var i = 0; i < 3; i++) {
                dots += '<span style="width:5px;height:5px;border-radius:50%;background:' + color + ';display:inline-block"></span>';
            }
            return '<div style="display:flex;gap:6px;justify-content:center;width:' + separatorWidthCss(block.width || 'medium') + ';' + spStyle + '">' + dots + '</div>';
        }
        var css = separatorPreviewStyles(block, theme);
        var elStyle = Object.keys(css).map(function(k) {
            return k.replace(/([A-Z])/g, '-$1').toLowerCase() + ':' + css[k];
        }).join(';');
        return '<div style="' + elStyle + ';margin-left:auto;margin-right:auto;' + spStyle + '"></div>';
    }

    function destroyBlockEditors() {
        $('#results-blocks-list .results-block-editor').each(function() {
            var $el = $(this);
            if ($el.next('.note-editor').length) {
                try { $el.summernote('destroy'); } catch (e) {}
            }
        });
    }

    function initBlockEditors() {
        $('#results-blocks-list .results-block-editor').each(function() {
            var $el = $(this);
            if (!$el.next('.note-editor').length) {
                $el.summernote(summernoteOpts);
                if ($el.val()) $el.summernote('code', $el.val());
            }
        });
    }

    function renderBlockList() {
        destroyBlockEditors();
        var blocks = getBlocks();
        var $list = $('#results-blocks-list').empty();
        blocks.forEach(function(block, index) {
            var $row = $('<div class="results-block-row"></div>');
            var $main = $('<div class="results-block-row__main"></div>');
            $main.append('<div class="results-block-row__type">' + (BLOCK_LABELS[block.type] || block.type) + '</div>');
            if (BLOCK_HINTS[block.type]) {
                $main.append('<div class="results-block-row__hint">' + BLOCK_HINTS[block.type] + '</div>');
            }
            if (BLOCK_EDITOR_TYPES.indexOf(block.type) !== -1) {
                var html = block.html || '';
                if (block.type === 'tagline' && block.text && !html) html = '<span>' + block.text + '</span>';
                $main.append('<textarea class="results-block-editor theme-input-style mt-2">' + $('<div>').text(html).html() + '</textarea>');
            }
            if (block.type === 'product_image' || block.type === 'match_badge') {
                var $rank = $('<div class="mt-2 d-flex gap-2 align-items-center"><label class="small mb-0">{{ translate('Product rank') }}</label><input type="number" class="theme-input-style results-block-rank" min="1" max="20" style="width:70px" value="' + (block.product_rank || 1) + '"></div>');
                $main.append($rank);
            }
            if (block.type === 'product_image') {
                var mode = block.display_mode || 'image';
                var $mode = $('<div class="mt-2 d-flex gap-2 align-items-center"><label class="small mb-0">{{ translate('Display') }}</label><select class="theme-input-style results-block-display-mode"><option value="image">' + '{{ translate('Image') }}' + '</option><option value="swatch">' + '{{ translate('Swatch') }}' + '</option></select></div>');
                $mode.find('select').val(mode);
                $main.append($mode);
            }
            if (block.type === 'hero_image') {
                $main.append('<p class="small text-muted mt-1 mb-0">{{ translate('Upload icon in block media field after save, or use product image block.') }}</p>');
            }
            if (block.type === 'separator') {
                $main.append(buildResultsSeparatorPanel(block));
            }
            $main.append(buildResultsBlockSpacingPanel(block));
            var $actions = $('<div class="results-block-row__actions"></div>');
            $actions.append('<label class="mb-0"><input type="checkbox" class="results-block-enable" ' + (block.enabled !== false ? 'checked' : '') + '> {{ translate('On') }}</label>');
            var $up = $('<button type="button" class="btn btn-sm btn-outline-secondary results-block-up">&uarr;</button>').prop('disabled', index === 0);
            var $down = $('<button type="button" class="btn btn-sm btn-outline-secondary results-block-down">&darr;</button>').prop('disabled', index === blocks.length - 1);
            $up.data('index', index); $down.data('index', index);
            $actions.append($up).append($down);
            $row.append($main).append($actions);
            $list.append($row);
        });
        initBlockEditors();
    }

    function collectBlocksFromDom() {
        if (!$('#results-blocks-list .results-block-row').length) return;
        var blocks = getBlocks();
        $('#results-blocks-list .results-block-row').each(function(i) {
            var block = blocks[i];
            if (!block) return;
            var $row = $(this);
            var $ed = $row.find('.results-block-editor');
            if ($ed.length) {
                block.html = $ed.next('.note-editor').length ? ($ed.summernote('code') || '') : ($ed.val() || '');
            }
            var rank = $row.find('.results-block-rank').val();
            if (rank) block.product_rank = parseInt(rank, 10) || 1;
            var dm = $row.find('.results-block-display-mode').val();
            if (dm) block.display_mode = dm;
            var $sepPanel = $row.find('.results-block-separator-panel');
            if ($sepPanel.length) {
                block.style = $sepPanel.find('.results-sep-style').val() || 'dot';
                block.width = $sepPanel.find('.results-sep-width').val() || 'medium';
                block.color = $sepPanel.find('.results-sep-color').val() || 'accent';
                block.color_custom = $sepPanel.find('.results-sep-color-custom').val() || '#c9a84c';
                block.thickness = parseInt($sepPanel.find('.results-sep-thickness').val(), 10) || 1;
                if (block.style === 'mixture') {
                    block.mixture_parts = [];
                    $sepPanel.find('.results-mixture-part').each(function() {
                        var $part = $(this);
                        var pType = $part.find('.results-mix-type').val() || 'line';
                        var part = { type: pType };
                        if (pType === 'gap') {
                            part.size = parseInt($part.find('.results-mix-size').val(), 10) || 12;
                        } else if (pType === 'line' || pType === 'dashed' || pType === 'double') {
                            part.width = $part.find('.results-mix-width').val() || 'wide';
                        }
                        block.mixture_parts.push(part);
                    });
                }
            }
            var $spPanel = $row.find('.results-block-spacing-panel');
            if ($spPanel.length) {
                block.spacing = {
                    margin_top: parseInt($spPanel.find('.results-sp-margin-top').val(), 10) || 0,
                    margin_bottom: parseInt($spPanel.find('.results-sp-margin-bottom').val(), 10) || 16,
                    padding_top: parseInt($spPanel.find('.results-sp-padding-top').val(), 10) || 0,
                    padding_bottom: parseInt($spPanel.find('.results-sp-padding-bottom').val(), 10) || 0,
                    padding_x: parseInt($spPanel.find('.results-sp-padding-x').val(), 10) || 0
                };
            }
            block.enabled = $row.find('.results-block-enable').is(':checked');
        });
        setBlocks(blocks);
    }

    function renderActionsList() {
        var actions = getActions();
        var $list = $('#results-actions-list').empty();
        actions.forEach(function(action, index) {
            var actionId = action.id || ('action_' + (index + 1));
            var $row = $('<div class="results-action-row"></div>');
            $row.attr('data-action-id', actionId);
            if (action.enabled === false) $row.addClass('disabled');

            var $row1 = $('<div class="d-flex flex-wrap gap-2 align-items-center mb-2"></div>');
            var $enable = $('<label class="mb-0"><input type="checkbox" class="results-action-enable"> {{ translate('On') }}</label>');
            $enable.find('input').prop('checked', action.enabled !== false);
            var $label = $('<input type="text" class="theme-input-style results-action-label flex-grow-1">');
            $label.attr('placeholder', '{{ translate('Label') }}');
            $label.val(action.label || '');
            var $style = $('<select class="theme-input-style results-action-style"></select>');
            $style.append('<option value="solid">{{ translate('Solid') }}</option>');
            $style.append('<option value="outline">{{ translate('Outline') }}</option>');
            $style.append('<option value="gradient_glow">{{ translate('Glow') }}</option>');
            $style.val(action.style || 'solid');
            var $type = $('<select class="theme-input-style results-action-type"></select>');
            $type.append('<option value="top_product">{{ translate('Top product') }}</option>');
            $type.append('<option value="link">{{ translate('Link') }}</option>');
            $type.append('<option value="share">{{ translate('Share') }}</option>');
            $type.append('<option value="restart">{{ translate('Restart') }}</option>');
            $type.val(action.action || 'link');
            $row1.append($enable).append($label).append($style).append($type);

            var $row2 = $('<div class="d-flex flex-wrap gap-2 align-items-center"></div>');
            var $url = $('<input type="text" class="theme-input-style results-action-url">');
            $url.attr('placeholder', '{{ translate('URL (for link action)') }}');
            $url.css('min-width', '140px');
            $url.val((action.action === 'link' || action.action === 'top_product') ? (action.url || '') : '');
            var $urlWrap = $('<span class="results-action-url-wrap"></span>');
            $urlWrap.append($url);
            var $hint = $('<small class="results-action-type-hint text-muted"></small>');
            var $bg = $('<input type="color" class="results-action-bg">');
            $bg.val(action.bg_color === 'transparent' ? '#c9a84c' : (action.bg_color || '#c9a84c'));
            var $text = $('<input type="color" class="results-action-text">');
            $text.val(action.text_color || '#1a3d2a');
            var $border = $('<input type="color" class="results-action-border">');
            $border.val(action.border_color || '#c9a84c');
            var $remove = $('<button type="button" class="btn btn-sm btn-outline-danger results-action-remove">&times;</button>');
            $row2.append($urlWrap).append($hint);
            $row2.append('<label class="small mb-0">{{ translate('BG') }}</label>').append($bg);
            $row2.append('<label class="small mb-0">{{ translate('Text') }}</label>').append($text);
            $row2.append('<label class="small mb-0">{{ translate('Border') }}</label>').append($border);
            $row2.append($remove);

            $row.append($row1).append($row2);
            $row.data('index', index);
            syncActionRowUrlField($row);
            $list.append($row);
        });
    }

    function collectActionsFromDom() {
        var $rows = $('#results-actions-list .results-action-row');
        if (!$rows.length) return;
        var existing = getActions();
        if (actionsHaveUrls(existing) && domActionUrlsEmpty($rows)) return;
        var actions = [];
        $rows.each(function(i) {
            var $r = $(this);
            var label = ($r.find('.results-action-label').val() || '').trim();
            if (!label) return;
            var actionId = $r.attr('data-action-id') || '';
            var existingAction = findExistingActionById(existing, actionId, i);
            var actionType = $r.find('.results-action-type').val() || 'link';
            actions.push({
                id: actionId || (existingAction && existingAction.id) || ('action_' + (actions.length + 1)),
                enabled: $r.find('.results-action-enable').is(':checked'),
                label: label,
                style: $r.find('.results-action-style').val() || 'solid',
                action: actionType,
                url: actionTypeUsesUrl(actionType) ? ($r.find('.results-action-url').val() || '') : '',
                product_rank: (existingAction && existingAction.product_rank) ? existingAction.product_rank : 1,
                bg_color: $r.find('.results-action-bg').val() || '#c9a84c',
                text_color: $r.find('.results-action-text').val() || '#1a3d2a',
                border_color: $r.find('.results-action-border').val() || '#c9a84c'
            });
        });
        setActions(actions.slice(0, 5));
    }

    function pageBackgroundCss(theme) {
        if (theme.background_type === 'radial_gradient') {
            var c = theme.background_gradient_center || theme.background_color || '#1a3d2a';
            var e = theme.background_gradient_edge || '#050a07';
            return 'radial-gradient(circle at center, ' + c + ' 0%, ' + e + ' 100%)';
        }
        return theme.background_color || '#1a3d2a';
    }

    function actionBtnStyle(action) {
        var css = {
            padding: '8px 16px',
            borderRadius: '4px',
            fontSize: '11px',
            fontWeight: '600',
            letterSpacing: '0.08em',
            border: '1px solid ' + (action.border_color || '#c9a84c'),
            cursor: 'default',
            marginRight: '8px',
            marginBottom: '8px'
        };
        if (action.style === 'outline') {
            css.background = 'transparent';
            css.color = action.text_color || '#c9a84c';
        } else if (action.style === 'gradient_glow') {
            css.background = action.bg_color || '#c9a84c';
            css.color = action.text_color || '#1a3d2a';
            css.boxShadow = '0 0 12px ' + (action.bg_color || '#c9a84c');
        } else {
            css.background = action.bg_color || '#c9a84c';
            css.color = action.text_color || '#1a3d2a';
        }
        return css;
    }

    function resolveActionPreviewHref(action) {
        var raw = '';
        if (action.action === 'top_product') {
            raw = action.url || MOCK_PRODUCT.url || '';
        } else if (action.action === 'link') {
            raw = action.url || '';
        }
        raw = String(raw).trim();
        if (!raw) return null;
        if (/^https?:\/\//i.test(raw)) {
            try {
                var parsed = new URL(raw);
                if (parsed.origin === window.location.origin) {
                    return parsed.pathname + parsed.search + parsed.hash;
                }
                return raw;
            } catch (e) {
                return raw;
            }
        }
        if (raw.indexOf('//') === 0) return raw;
        return raw.indexOf('/') === 0 ? raw : '/' + raw.replace(/^\/+/, '');
    }

    function actionPreviewHtml(action) {
        var st = actionBtnStyle(action);
        var styleStr = Object.keys(st).map(function(k) {
            return k.replace(/([A-Z])/g, '-$1').toLowerCase() + ':' + st[k];
        }).join(';');
        var label = $('<div>').text(action.label).html();
        var href = resolveActionPreviewHref(action);
        if (href && (action.action === 'link' || action.action === 'top_product')) {
            var target = /^https?:\/\//i.test(href) || href.indexOf('//') === 0 ? ' target="_blank" rel="noopener noreferrer"' : '';
            return '<a href="' + $('<div>').text(href).html() + '"' + target + ' style="' + styleStr + ';text-decoration:none;cursor:pointer">' + label + '</a>';
        }
        return '<span style="' + styleStr + '">' + label + '</span>';
    }

    window.updateQuizResultsPreview = function() {
        if (!$('#preview-results-panel').length) return;
        var theme = readThemeFromPanel();
        var layout = $('#results-layout-mode').val() || 'featured_card';
        var blocks = getBlocks();
        var actions = getActions().filter(function(a) { return a.enabled !== false; });
        var grid = readProductGridFromPanel();
        var $root = $('#quiz-results-preview');
        var bg = pageBackgroundCss(theme);
        $root.css({
            background: bg,
            color: theme.text_color || '#fff',
            minHeight: theme.fill_viewport ? '320px' : 'auto',
            padding: '20px 16px',
            borderRadius: '12px',
            border: '1px solid #e5e7eb'
        });
        var html = '';
        if (layout === 'featured_card' || layout === 'featured_and_grid') {
            var cardStyle = 'max-width:' + (theme.card_max_width || 480) + 'px;margin:0 auto;padding:' + (theme.card_padding || 32) + 'px;';
            if (theme.card_enabled) {
                cardStyle += 'background:' + (theme.card_background || '#1f4530') + ';';
                if (theme.card_border_style === 'double') {
                    cardStyle += 'border:3px double ' + (theme.card_border_color || '#c9a84c') + ';';
                } else if (theme.card_border_style === 'single') {
                    cardStyle += 'border:1px solid ' + (theme.card_border_color || '#c9a84c') + ';';
                }
            }
            html += '<div style="text-align:center;' + cardStyle + '">';
            blocks.filter(function(b) { return b.enabled !== false; }).forEach(function(block) {
                if (block.type === 'product_image') {
                    html += '<div style="width:48px;height:48px;border-radius:50%;background:#c9a84c;margin:8px auto;opacity:0.8"></div>';
                } else if (block.type === 'match_badge') {
                    html += '<span style="display:inline-block;background:#ff5a1f;color:#fff;font-size:11px;padding:4px 10px;border-radius:999px;margin:8px 0">' + MOCK_PRODUCT.match_pct + '% match</span>';
                } else if (block.type === 'hero_image') {
                    html += '<div style="width:' + (theme.hero_size || 80) + 'px;height:' + (theme.hero_size || 80) + 'px;margin:0 auto 12px;background:' + (theme.accent_color || '#c9a84c') + ';border-radius:50%;opacity:0.5"></div>';
                } else if (block.html) {
                    html += '<div style="margin:8px 0">' + resolveTokens(block.html) + '</div>';
                } else if (block.type === 'separator') {
                    html += renderSeparatorPreviewHtml(block, theme);
                }
            });
            html += '</div>';
        }
        if (layout === 'product_grid' || layout === 'featured_and_grid') {
            if (grid.show_heading && grid.heading) {
                html += '<h6 class="mt-3 mb-1" style="color:inherit">' + resolveTokens(grid.heading) + '</h6>';
            }
            if (grid.subheading) {
                html += '<p class="small mb-2" style="opacity:0.8">' + resolveTokens(grid.subheading) + '</p>';
            }
            html += '<div class="d-flex gap-2 flex-wrap mt-2"><div style="width:80px;height:100px;background:rgba(255,255,255,0.1);border-radius:8px"></div><div style="width:80px;height:100px;background:rgba(255,255,255,0.1);border-radius:8px"></div></div>';
        }
        if (actions.length) {
            html += '<div class="mt-3 d-flex flex-wrap justify-content-center">';
            actions.forEach(function(a) {
                html += actionPreviewHtml(a);
            });
            html += '</div>';
        }
        $root.html(html || '<p class="small text-muted mb-0">{{ translate('Configure results blocks and actions') }}</p>');
    };

    window.syncResultsBuilder = function() {
        $('#results-actions-list .results-action-row').each(function() {
            syncActionRowUrlField($(this));
        });
        collectBlocksFromDom();
        collectActionsFromDom();
        readThemeFromPanel();
        readProductGridFromPanel();
        if (typeof window.updateQuizResultsPreview === 'function') {
            window.updateQuizResultsPreview();
        }
    };

    function toggleLayoutSections() {
        var mode = $('#results-layout-mode').val() || 'featured_card';
        $('#results-blocks-panel').toggle(mode !== 'product_grid');
        $('#results-card-theme-section').toggle(mode !== 'product_grid');
        $('#results-product-grid-section').toggle(mode !== 'featured_card');
    }

    $(function() {
        if (!$('#results-blocks-json').length && !$('#results-actions-json').length) return;

        repairActionsJsonOnInit();
        renderActionsList();
        try {
            renderBlockList();
        } catch (e) {
            console.error('Results block list init failed', e);
        }
        toggleLayoutSections();
        window.updateQuizResultsPreview();

        $(document).on('change input', '.results-theme-input, .results-grid-input, .results-preview-input', function() {
            if ($(this).attr('id') === 'results-bg-type') {
                $('.results-bg-solid-row').toggleClass('d-none', $(this).val() !== 'solid');
                $('.results-bg-gradient-rows').toggleClass('d-none', $(this).val() !== 'radial_gradient');
            }
            if ($(this).attr('id') === 'results-layout-mode') {
                toggleLayoutSections();
            }
            syncResultsBuilder();
        });

        $(document).on('click', '.results-block-up, .results-block-down', function() {
            var idx = $(this).data('index');
            var blocks = getBlocks();
            var ni = $(this).hasClass('results-block-up') ? idx - 1 : idx + 1;
            if (ni < 0 || ni >= blocks.length) return;
            var tmp = blocks[idx]; blocks[idx] = blocks[ni]; blocks[ni] = tmp;
            setBlocks(blocks);
            renderBlockList();
            syncResultsBuilder();
        });

        $(document).on('click', '#results-reset-blocks-btn', function() {
            if (confirm('{{ translate('Reset blocks to defaults?') }}')) {
                setBlocks(DEFAULT_BLOCKS.slice());
                renderBlockList();
                syncResultsBuilder();
            }
        });

        $(document).on('click', '#results-add-action-btn', function() {
            var actions = getActions();
            if (actions.length >= 5) return;
            actions.push({
                id: 'action_' + (actions.length + 1),
                enabled: true,
                label: 'BUTTON',
                style: 'outline',
                action: 'link',
                url: '',
                product_rank: 1,
                bg_color: '#c9a84c',
                text_color: '#c9a84c',
                border_color: '#c9a84c'
            });
            setActions(actions);
            renderActionsList();
            syncResultsBuilder();
        });

        $(document).on('click', '.results-action-remove', function() {
            var idx = $(this).closest('.results-action-row').data('index');
            var actions = getActions();
            actions.splice(idx, 1);
            setActions(actions);
            renderActionsList();
            syncResultsBuilder();
        });

        $(document).on('change input', '.results-action-row input, .results-action-row select', function() {
            syncResultsBuilder();
        });

        $(document).on('change', '.results-block-enable', function() {
            syncResultsBuilder();
        });

        $(document).on('change input', '.results-block-separator-panel select, .results-block-separator-panel input', function() {
            var $panel = $(this).closest('.results-block-separator-panel');
            if ($(this).hasClass('results-sep-color')) {
                $panel.find('.results-sep-color-custom').toggle($(this).val() === 'custom');
            }
            if ($(this).hasClass('results-sep-style')) {
                var isMix = $(this).val() === 'mixture';
                $panel.find('.results-sep-single-controls').toggle(!isMix);
                $panel.find('.results-sep-mixture-wrap').toggle(isMix);
            }
            syncResultsBuilder();
        });

        $(document).on('change input', '.results-block-spacing-panel input', function() {
            syncResultsBuilder();
        });

        $(document).on('change', '.results-mix-type', function() {
            var $row = $(this).closest('.results-mixture-part');
            var t = $(this).val();
            $row.find('.results-mix-width').toggle(t === 'line' || t === 'dashed' || t === 'double');
            $row.find('.results-mix-size').toggle(t === 'gap');
            syncResultsBuilder();
        });

        $(document).on('click', '.results-mix-add', function() {
            var $list = $(this).closest('.results-sep-mixture-wrap').find('.results-mixture-parts-list');
            $list.append(buildResultsMixturePartRow({ type: 'line', width: 'medium' }, $list.find('.results-mixture-part').length));
            syncResultsBuilder();
        });

        $(document).on('click', '.results-mix-preset', function() {
            var $wrap = $(this).closest('.results-sep-mixture-wrap');
            var $list = $wrap.find('.results-mixture-parts-list');
            $list.empty();
            MIXTURE_LINE_DIAMOND_PRESET.forEach(function(part, i) {
                $list.append(buildResultsMixturePartRow(part, i));
            });
            syncResultsBuilder();
        });

        $(document).on('click', '.results-mix-remove', function() {
            $(this).closest('.results-mixture-part').remove();
            syncResultsBuilder();
        });

        $(document).on('click', '.results-mix-up', function() {
            var $row = $(this).closest('.results-mixture-part');
            var $prev = $row.prev('.results-mixture-part');
            if ($prev.length) $row.insertBefore($prev);
            syncResultsBuilder();
        });

        $(document).on('click', '.results-mix-down', function() {
            var $row = $(this).closest('.results-mixture-part');
            var $next = $row.next('.results-mixture-part');
            if ($next.length) $row.insertAfter($next);
            syncResultsBuilder();
        });

        $('#quiz-form').on('submit', function() {
            syncResultsBuilder();
        });

        function showPreviewTab(tab) {
            $('#preview-tab-intro, #preview-tab-questions, #preview-tab-results').removeClass('active');
            $('#preview-intro-panel, #preview-questions-panel, #preview-results-panel').addClass('d-none');
            if (tab === 'intro') {
                $('#preview-tab-intro').addClass('active');
                $('#preview-intro-panel').removeClass('d-none');
            } else if (tab === 'questions') {
                $('#preview-tab-questions').addClass('active');
                $('#preview-questions-panel').removeClass('d-none');
                if (typeof window.updateQuestionsPreview === 'function') window.updateQuestionsPreview();
            } else if (tab === 'results') {
                $('#preview-tab-results').addClass('active');
                $('#preview-results-panel').removeClass('d-none');
                window.updateQuizResultsPreview();
            }
        }
        window.showPreviewTab = showPreviewTab;

        $('#preview-tab-results').on('click', function() { showPreviewTab('results'); });
        $('#preview-tab-intro').off('click.results').on('click.results', function() { showPreviewTab('intro'); });
        $('#preview-tab-questions').off('click.results').on('click.results', function() { showPreviewTab('questions'); });
    });
})(jQuery);
</script>
