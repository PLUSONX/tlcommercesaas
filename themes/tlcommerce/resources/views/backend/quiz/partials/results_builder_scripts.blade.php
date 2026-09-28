<script>
(function($) {
    "use strict";

    var PREVIEW_LOCALE = @json($lang ?? getDefaultLang());
    var RESULTS_STRUCTURE_LOCKED = @json(!($isDefaultLang ?? true));

    var MOCK_PRODUCT = {
        name: 'CUOIO',
        url: '/products/cuoio',
        summary: 'Like soft morning sunlight on warm leather.',
        price: '$120',
        match_pct: 92,
        rank: 1,
        image: '',
        tagline: 'THE GOLDEN OPTIMIST',
        color: '#c9a84c',
        description: 'Like soft morning sunlight on warm leather.',
        quote: 'Softness that never loses its features.'
    };

    var APPEARANCE_BLOCK_TYPES = ['title', 'subtitle', 'tagline', 'body', 'meta'];

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
    var DEFAULT_PRODUCT_COLOR_BLOCKS = {!! json_encode(\Theme\TLCommerce\Http\Resources\QuizResultsConfig::productColorCardBlocks()) !!};
    var DEFAULT_ACTIONS = {!! json_encode(\Theme\TLCommerce\Http\Resources\QuizResultsConfig::defaultActions()) !!};
    var DEFAULT_PRODUCT_PROFILES = {!! json_encode(\Theme\TLCommerce\Http\Resources\QuizResultsConfig::defaultProductProfiles()) !!};

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

    function parseJsonFieldStrict(raw) {
        if (raw == null || raw === '') return null;
        try {
            var parsed = JSON.parse(raw);
            return parsed != null ? parsed : null;
        } catch (e) {
            try {
                var decoded = $('<textarea>').html(String(raw)).text();
                parsed = JSON.parse(decoded);
                return parsed != null ? parsed : null;
            } catch (e2) {
                return null;
            }
        }
    }

    function isProductColorCardLayout() {
        return ($('#results-layout-mode').val() || '') === 'product_color_card';
    }

    function defaultBlocksForLayout() {
        return isProductColorCardLayout()
            ? DEFAULT_PRODUCT_COLOR_BLOCKS.slice()
            : DEFAULT_BLOCKS.slice();
    }

    function seedBlocksFromDataElement() {
        var $data = $('#results-blocks-json-data');
        if (!$data.length) return;
        var parsed = parseJsonFieldStrict($data.text());
        if (parsed !== null) {
            $('#results-blocks-json').val(JSON.stringify(parsed));
        } else {
            console.error('Results blocks: failed to parse #results-blocks-json-data');
        }
    }

    function getBlocks() {
        return parseJsonField($('#results-blocks-json').val(), defaultBlocksForLayout());
    }
    function setBlocks(b) { $('#results-blocks-json').val(JSON.stringify(b)); }

    function findBlockById(blocks, blockId, index) {
        if (blockId) {
            var id = String(blockId);
            for (var j = 0; j < blocks.length; j++) {
                if (blocks[j] && String(blocks[j].id) === id) return blocks[j];
            }
        }
        return blocks[index] || null;
    }

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

    function getProfiles() {
        return parseJsonField($('#results-product-profiles-json').val(), DEFAULT_PRODUCT_PROFILES.slice());
    }
    function setProfiles(list) { $('#results-product-profiles-json').val(JSON.stringify(list)); }

    function getFirstEnabledProfile() {
        var profiles = getProfiles().filter(function(p) { return p && p.enabled !== false; });
        return profiles[0] || null;
    }

    function getPreviewProductContext() {
        var profile = getFirstEnabledProfile();
        return {
            name: (profile && profile.product_name) ? profile.product_name : MOCK_PRODUCT.name,
            url: MOCK_PRODUCT.url,
            summary: MOCK_PRODUCT.summary,
            price: MOCK_PRODUCT.price,
            match_pct: MOCK_PRODUCT.match_pct,
            rank: MOCK_PRODUCT.rank,
            image: MOCK_PRODUCT.image,
            tagline: (profile && profile.tagline) ? profile.tagline : MOCK_PRODUCT.tagline,
            color: (profile && profile.color) ? profile.color : MOCK_PRODUCT.color,
            description: (profile && profile.description) ? profile.description : MOCK_PRODUCT.description,
            quote: (profile && profile.quote) ? profile.quote : MOCK_PRODUCT.quote,
            collection: (profile && profile.collection)
    ? profile.collection
    : 'Silver BEE'
        };
    }

    function filterBlocksForPreview(blocks) {
        return blocks.filter(function(block) {
            return block && block.enabled !== false;
        });
    }

    function normalizeProductSummary(raw) {
        if (raw == null || raw === '') return '';
        var text = String(raw);
        var el = document.createElement('textarea');
        el.innerHTML = text;
        text = el.value;
        text = text.replace(/<\s*br\s*\/?>/gi, '\n');
        text = text.replace(/<\/\s*p\s*>/gi, '\n\n');
        text = text.replace(/<\/\s*div\s*>/gi, '\n\n');
        text = text.replace(/<[^>]+>/g, '');
        var lines = text.split(/\r\n|\r|\n/).map(function(line) {
            return line.replace(/[ \t\u00a0]+/g, ' ').trim();
        }).filter(function(line) { return line !== ''; });
        return lines.join('\n\n').trim();
    }

    function escapeHtmlToken(text) {
        return String(text == null ? '' : text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatSummaryToken(text) {
        var escaped = escapeHtmlToken(String(text == null ? '' : text));
        return escaped.replace(/\n\n/g, '<br>').replace(/\n/g, '<br>');
    }

    function blocksMatchDefaults(blocks) {
        try {
            return JSON.stringify(blocks) === JSON.stringify(DEFAULT_BLOCKS)
                || JSON.stringify(blocks) === JSON.stringify(DEFAULT_PRODUCT_COLOR_BLOCKS);
        } catch (e) {
            return false;
        }
    }

    function upgradeProductColorCardBlocks(blocks) {
        blocks = Array.isArray(blocks) ? blocks : [];
        var preset = DEFAULT_PRODUCT_COLOR_BLOCKS;
        var savedById = {};
        blocks.forEach(function(block) {
            if (!block || !block.id) return;
            savedById[String(block.id)] = block;
        });

        var mergeKeys = ['html', 'text', 'spacing', 'appearance', 'size', 'display_mode', 'product_rank'];
        var merged = [];
        var usedIds = {};

        preset.forEach(function(presetBlock) {
            var id = String(presetBlock.id || '');
            var block = $.extend(true, {}, presetBlock);
            if (id && savedById[id]) {
                var saved = savedById[id];
                mergeKeys.forEach(function(key) {
                    if (Object.prototype.hasOwnProperty.call(saved, key)) {
                        block[key] = saved[key];
                    }
                });
                if (Object.prototype.hasOwnProperty.call(saved, 'enabled')) {
                    block.enabled = saved.enabled;
                }
            }
            delete block.visible_locales;
            merged.push(block);
            if (id) usedIds[id] = true;
        });

        blocks.forEach(function(block) {
            if (!block || !block.id) return;
            var id = String(block.id);
            if (usedIds[id]) return;
            var extra = $.extend(true, {}, block);
            delete extra.visible_locales;
            merged.push(extra);
        });

        return merged.length ? merged : JSON.parse(JSON.stringify(preset));
    }

    function applyProductColorPresetIfNeeded() {
        if (($('#results-layout-mode').val() || '') !== 'product_color_card') return;
        var blocks = getBlocks();
        if (!blocks.length || blocksMatchDefaults(blocks)) {
            setBlocks(JSON.parse(JSON.stringify(DEFAULT_PRODUCT_COLOR_BLOCKS)));
            renderBlockList();
            return;
        }
        var upgraded = upgradeProductColorCardBlocks(blocks);
        if (JSON.stringify(upgraded) !== JSON.stringify(blocks)) {
            setBlocks(upgraded);
            renderBlockList();
        }
    }

    function buildProfileRowHtml(productId, productName, color, tagline, description, quote, collection) {
        color = color || '#c9a84c';
        var colorId = 'results-profile-color-' + productId;
        var taglineId = 'results-profile-tagline-' + productId;
        var descriptionId = 'results-profile-description-' + productId;
        var quoteId = 'results-profile-quote-' + productId;
var collectionId = 'results-profile-collection-' + productId;
        var safeName = $('<div>').text(productName || '').html();
        var safeTagline = $('<div>').text(tagline || '').html();
        var safeDescription = $('<div>').text(description || '').html();
        var safeQuote = $('<div>').text(quote || '').html();
var safeCollection = $('<div>').text(collection || '').html();
        return '<tr data-product-id="' + productId + '" data-product-name="' + safeName + '">' +
            '<td class="results-profile-name">' + safeName + '</td>' +
            '<td><div class="input-group addon">' +
            '<input type="text" id="' + colorId + '" class="color-input form-control style--two results-profile-color-input" value="' + color + '">' +
            '<div class="input-group-append">' +
            '<input type="color" class="input-group-text theme-input-style2 color-picker results-profile-color-input" value="' + color + '" oninput="document.getElementById(\'' + colorId + '\').value = this.value; if (typeof window.syncResultsBuilder === \'function\') window.syncResultsBuilder();">' +
            '</div></div></td>' +
            '<td><input type="text" id="' + taglineId + '" class="theme-input-style w-100 results-profile-tagline-input" value="' + safeTagline + '" placeholder="{{ translate('THE GOLDEN OPTIMIST') }}"></td>' +
            '<td><textarea id="' + descriptionId + '" class="theme-input-style w-100 results-profile-description-input" rows="2" maxlength="2000" placeholder="{{ translate('Short description shown on the result card') }}">' + safeDescription + '</textarea></td>' +
            '<td><input type="text" id="' + quoteId + '" class="theme-input-style w-100 results-profile-quote-input" value="' + safeQuote + '" maxlength="255" placeholder="{{ translate('Quoted tagline below description') }}"></td>' +
'<td><input type="text" id="' + collectionId + '" class="theme-input-style w-100 results-profile-collection-input" value="' + safeCollection + '" maxlength="100" placeholder="{{ translate('Collection') }}"></td>' +
            '<td><button type="button" class="btn long btn-sm btn-danger results-profile-remove">{{ translate('Remove') }}</button></td>' +
            '</tr>';
    }

    function collectProfilesFromDom() {
        if (!$('#results-product-profiles-json').length) return;
        var profiles = [];
        $('#results-product-profiles-body tr').each(function() {
            var $row = $(this);
            var productId = parseInt($row.data('product-id'), 10);
            if (!productId) return;
            var color = ($row.find('.results-profile-color-input').first().val() || '#c9a84c').trim();
            profiles.push({
    product_id: productId,
    product_name: String($row.data('product-name') || $row.find('.results-profile-name').text().trim()),
    color: color,
    tagline: ($row.find('.results-profile-tagline-input').val() || '').trim(),
    description: ($row.find('.results-profile-description-input').val() || '').trim(),
    quote: ($row.find('.results-profile-quote-input').val() || '').trim(),
    collection: ($row.find('.results-profile-collection-input').val() || '').trim(),
    enabled: true
});
        });
        setProfiles(profiles);
        $('#results-product-profiles-empty').toggleClass('d-none', profiles.length > 0);
    }

    function resolveTokens(text) {
        if (!text) return '';
        var p = getPreviewProductContext();
        var summary = formatSummaryToken(normalizeProductSummary(p.summary));
        return String(text)
            .replace(/\{\{product\.name\}\}/g, p.name)
            .replace(/\{\{product\.url\}\}/g, p.url)
            .replace(/\{\{product\.summary\}\}/g, summary)
            .replace(/\{\{product\.price\}\}/g, p.price)
            .replace(/\{\{product\.tagline\}\}/g, p.tagline || '')
            .replace(/\{\{product\.collection\}\}/g, p.collection || '')
            .replace(/\{\{product\.description\}\}/g, formatSummaryToken(p.description || ''))
            .replace(/\{\{product\.quote\}\}/g, p.quote || '')
            .replace(/\{\{product\.color\}\}/g, p.color || '')
            .replace(/\{\{match_pct\}\}/g, String(p.match_pct))
            .replace(/\{\{rank\}\}/g, String(p.rank))
            .replace(/\{\{product_1\.name\}\}/g, p.name)
            .replace(/\{\{product_1\.match_pct\}\}/g, String(p.match_pct));
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
        t.hero_frame = $('#results-hero-frame').val() || 'none';
        t.hero_frame_color = hexFrom('results-hero-frame-color', '#c9a84c');
        t.hero_frame_thickness = parseInt($('#results-hero-frame-thickness').val(), 10) || 2;
        t.hero_frame_inset = parseInt($('#results-hero-frame-inset').val(), 10) || 10;
        t.hero_frame_corner_size = parseInt($('#results-hero-frame-corner-size').val(), 10) || 18;
        t.hero_glow = $('#results-hero-glow').is(':checked');
        t.tagline_font = $('#results-tagline-font').val() || 'default';
        t.tagline_color = hexFrom('results-tagline-color', '#ffffff');
        t.tagline_font_size = parseInt($('#results-tagline-font-size').val(), 10) || 11;
        t.tagline_letter_spacing = parseFloat($('#results-tagline-letter-spacing').val()) || 0.15;
        t.body_font = $('#results-description-font').val() || 'default';
        t.description_color = hexFrom('results-description-color', '#ffffff');
        t.description_font_size = parseInt($('#results-description-font-size').val(), 10) || 15;
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

    function getBlockAppearance(block) {
        return $.extend({}, DEFAULT_APPEARANCE, block.appearance || {});
    }

    function resolveColorMode(mode, custom, theme, fallbackKey) {
        if (mode === 'custom') return custom || '#c9a84c';
        if (mode === 'accent') return theme.accent_color || '#c9a84c';
        if (mode === 'text') return theme.text_color || '#ffffff';
        if (mode === 'transparent') return 'transparent';
        return theme[fallbackKey] || theme.text_color || '#ffffff';
    }

    function parseHexColor(hex) {
        var raw = String(hex || '').trim().replace(/^#/, '');
        if (raw.length === 3) {
            return {
                r: parseInt(raw.charAt(0) + raw.charAt(0), 16),
                g: parseInt(raw.charAt(1) + raw.charAt(1), 16),
                b: parseInt(raw.charAt(2) + raw.charAt(2), 16)
            };
        }
        if (raw.length === 6) {
            return {
                r: parseInt(raw.slice(0, 2), 16),
                g: parseInt(raw.slice(2, 4), 16),
                b: parseInt(raw.slice(4, 6), 16)
            };
        }
        return null;
    }

    function relativeLuminance(rgb) {
        function toLinear(c) {
            var s = c / 255;
            return s <= 0.03928 ? s / 12.92 : Math.pow((s + 0.055) / 1.055, 2.4);
        }
        return 0.2126 * toLinear(rgb.r) + 0.7152 * toLinear(rgb.g) + 0.0722 * toLinear(rgb.b);
    }

    function colorsTooSimilar(a, b) {
        var c1 = parseHexColor(a);
        var c2 = parseHexColor(b);
        if (!c1 || !c2) return false;
        if (String(a).toLowerCase() === String(b).toLowerCase()) return true;
        return Math.abs(relativeLuminance(c1) - relativeLuminance(c2)) < 0.08;
    }

    function readableColor(textColor, backgroundColor, fallback) {
        var text = String(textColor || '').trim();
        var bg = String(backgroundColor || '').trim();
        var fb = String(fallback || '#111111').trim();
        if (!text || !bg) return text || fb;
        if (colorsTooSimilar(text, bg)) return fb;
        return text;
    }

    function blockTypeTypographyStyles(block, theme) {
        var css = {};
        var textFallback = theme.text_color || theme.accent_color || '#c9a84c';
        var cardBg = theme.card_background || '#ffffff';
        var onCard = !!theme.card_enabled;
        if (block.type === 'tagline') {
            if (theme.tagline_color) {
                css.color = onCard
                    ? readableColor(theme.tagline_color, cardBg, textFallback)
                    : theme.tagline_color;
            }
            if (theme.tagline_font_size) css.fontSize = theme.tagline_font_size + 'px';
            if (theme.tagline_letter_spacing != null) css.letterSpacing = theme.tagline_letter_spacing + 'em';
            if (theme.tagline_font === 'serif_caps') {
                css.fontFamily = '"Playfair Display", Georgia, serif';
                css.textTransform = 'uppercase';
            }
        }
        if (block.type === 'body') {
            if (theme.description_color) {
                css.color = onCard
                    ? readableColor(theme.description_color, cardBg, textFallback)
                    : theme.description_color;
            }
            if (theme.description_font_size) css.fontSize = theme.description_font_size + 'px';
            if (theme.body_font === 'script') css.fontFamily = '"Great Vibes", cursive';
        }
        return css;
    }

    function blockAppearanceStyles(block, theme) {
        var app = getBlockAppearance(block);
        var css = {};
        if (app.text_color !== 'inherit' || block.type === 'meta') {
            css.color = resolveColorMode(app.text_color, app.text_color_custom, theme, 'text_color');
        }
        if (app.background_color !== 'transparent') {
            css.backgroundColor = resolveColorMode(app.background_color, app.background_color_custom, theme, 'card_background');
        }
        if (app.padding_x || app.padding_y) {
            css.paddingLeft = (app.padding_x || 0) + 'px';
            css.paddingRight = (app.padding_x || 0) + 'px';
            css.paddingTop = (app.padding_y || 0) + 'px';
            css.paddingBottom = (app.padding_y || 0) + 'px';
        }
        if (app.border_radius) css.borderRadius = app.border_radius + 'px';
        return css;
    }

    function blockWrapperStyleString(block, theme) {
        var css = $.extend({}, blockTypeTypographyStyles(block, theme), blockAppearanceStyles(block, theme));
        return Object.keys(css).map(function(k) {
            return k.replace(/([A-Z])/g, '-$1').toLowerCase() + ':' + css[k];
        }).join(';');
    }

    function buildResultsTextAppearancePanel(block) {
        var app = getBlockAppearance(block);
        var $panel = $('<div class="results-block-appearance-panel mt-2"></div>');
        $panel.append('<div class="intro-panel-label">{{ translate('Text styling') }}</div>');
        var $row1 = $('<div class="results-sep-row d-flex flex-wrap gap-2 align-items-center mb-2"></div>');
        var $textSel = $('<select class="theme-input-style results-app-text-color"></select>');
        [['inherit', '{{ translate('Inherit') }}'], ['accent', '{{ translate('Accent') }}'], ['text', '{{ translate('Text') }}'], ['custom', '{{ translate('Custom') }}']].forEach(function(o) {
            $textSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $textSel.val(app.text_color);
        $row1.append('<label class="small mb-0">{{ translate('Text color') }}</label>').append($textSel);
        var $textCustom = $('<input type="color" class="results-app-text-color-custom" value="' + (app.text_color_custom || '#c9a84c') + '">');
        if (app.text_color !== 'custom') $textCustom.hide();
        $row1.append($textCustom);

        var $bgSel = $('<select class="theme-input-style results-app-bg-color"></select>');
        [['transparent', '{{ translate('None') }}'], ['accent', '{{ translate('Accent') }}'], ['text', '{{ translate('Text') }}'], ['custom', '{{ translate('Custom') }}']].forEach(function(o) {
            $bgSel.append('<option value="' + o[0] + '">' + o[1] + '</option>');
        });
        $bgSel.val(app.background_color);
        $row1.append('<label class="small mb-0 ml-2">{{ translate('Background') }}</label>').append($bgSel);
        var $bgCustom = $('<input type="color" class="results-app-bg-color-custom" value="' + (app.background_color_custom || '#1a3d2a') + '">');
        if (app.background_color !== 'custom') $bgCustom.hide();
        $row1.append($bgCustom);

        var $row2 = $('<div class="results-sep-row d-flex flex-wrap gap-2 align-items-center"></div>');
        $row2.append('<label class="small mb-0">{{ translate('Pad X') }}</label>');
        $row2.append('<input type="number" class="theme-input-style results-app-padding-x" min="0" max="64" style="width:56px" value="' + app.padding_x + '">');
        $row2.append('<label class="small mb-0 ml-1">{{ translate('Pad Y') }}</label>');
        $row2.append('<input type="number" class="theme-input-style results-app-padding-y" min="0" max="64" style="width:56px" value="' + app.padding_y + '">');
        $row2.append('<label class="small mb-0 ml-1">{{ translate('Radius') }}</label>');
        $row2.append('<input type="number" class="theme-input-style results-app-border-radius" min="0" max="32" style="width:56px" value="' + app.border_radius + '">');

        $panel.append($row1).append($row2);
        return $panel;
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
            if (block.id) $row.attr('data-block-id', block.id);
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
            if (!RESULTS_STRUCTURE_LOCKED && (block.type === 'product_image' || block.type === 'match_badge')) {
                var $rank = $('<div class="mt-2 d-flex gap-2 align-items-center"><label class="small mb-0">{{ translate('Product rank') }}</label><input type="number" class="theme-input-style results-block-rank" min="1" max="20" style="width:70px" value="' + (block.product_rank || 1) + '"></div>');
                $main.append($rank);
            }
            if (!RESULTS_STRUCTURE_LOCKED && block.type === 'product_image') {
                var mode = block.display_mode || 'image';
                var $mode = $('<div class="mt-2 d-flex gap-2 align-items-center"><label class="small mb-0">{{ translate('Display') }}</label><select class="theme-input-style results-block-display-mode"><option value="image">' + '{{ translate('Image') }}' + '</option><option value="swatch">' + '{{ translate('Swatch') }}' + '</option></select></div>');
                $mode.find('select').val(mode);
                $main.append($mode);
            }
            if (block.type === 'hero_image') {
                $main.append('<p class="small text-muted mt-1 mb-0">{{ translate('Upload icon in block media field after save, or use product image block.') }}</p>');
            }
            if (!RESULTS_STRUCTURE_LOCKED && block.type === 'separator') {
                $main.append(buildResultsSeparatorPanel(block));
            }
            if (!RESULTS_STRUCTURE_LOCKED && APPEARANCE_BLOCK_TYPES.indexOf(block.type) !== -1) {
                $main.append(buildResultsTextAppearancePanel(block));
            }
            if (!RESULTS_STRUCTURE_LOCKED) {
                $main.append(buildResultsBlockSpacingPanel(block));
            }
            var $actions = $('<div class="results-block-row__actions"></div>');
            if (RESULTS_STRUCTURE_LOCKED) $actions.addClass('area-disabled');
            $actions.append('<label class="mb-0"><input type="checkbox" class="results-block-enable" ' + (block.enabled !== false ? 'checked' : '') + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '> {{ translate('On') }}</label>');
            var $up = $('<button type="button" class="btn btn-sm btn-outline-secondary results-block-up">&uarr;</button>').prop('disabled', index === 0 || RESULTS_STRUCTURE_LOCKED);
            var $down = $('<button type="button" class="btn btn-sm btn-outline-secondary results-block-down">&darr;</button>').prop('disabled', index === blocks.length - 1 || RESULTS_STRUCTURE_LOCKED);
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
            var $row = $(this);
            var blockId = $row.attr('data-block-id') || '';
            var block = findBlockById(blocks, blockId, i);
            if (!block) return;
            var $ed = $row.find('.results-block-editor');
            if ($ed.length) {
                block.html = $ed.next('.note-editor').length ? ($ed.summernote('code') || '') : ($ed.val() || '');
            }
            if (RESULTS_STRUCTURE_LOCKED) return;
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
            var $appPanel = $row.find('.results-block-appearance-panel');
            if ($appPanel.length) {
                block.appearance = {
                    text_color: $appPanel.find('.results-app-text-color').val() || 'inherit',
                    text_color_custom: $appPanel.find('.results-app-text-color-custom').val() || '#c9a84c',
                    background_color: $appPanel.find('.results-app-bg-color').val() || 'transparent',
                    background_color_custom: $appPanel.find('.results-app-bg-color-custom').val() || '#1a3d2a',
                    padding_x: parseInt($appPanel.find('.results-app-padding-x').val(), 10) || 0,
                    padding_y: parseInt($appPanel.find('.results-app-padding-y').val(), 10) || 0,
                    border_radius: parseInt($appPanel.find('.results-app-border-radius').val(), 10) || 0
                };
            }
            if (!RESULTS_STRUCTURE_LOCKED) {
                block.enabled = $row.find('.results-block-enable').is(':checked');
            }
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
            var $enable = $('<label class="mb-0"><input type="checkbox" class="results-action-enable"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '> {{ translate('On') }}</label>');
            $enable.find('input').prop('checked', action.enabled !== false);
            var $label = $('<input type="text" class="theme-input-style results-action-label flex-grow-1">');
            $label.attr('placeholder', '{{ translate('Label') }}');
            $label.val(action.label || '');
            var $style = $('<select class="theme-input-style results-action-style"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '></select>');
            $style.append('<option value="solid">{{ translate('Solid') }}</option>');
            $style.append('<option value="outline">{{ translate('Outline') }}</option>');
            $style.append('<option value="gradient_glow">{{ translate('Glow') }}</option>');
            $style.val(action.style || 'solid');
            var $type = $('<select class="theme-input-style results-action-type"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '></select>');
            $type.append('<option value="top_product">{{ translate('Top product') }}</option>');
            $type.append('<option value="link">{{ translate('Link') }}</option>');
            $type.append('<option value="share">{{ translate('Share') }}</option>');
            $type.append('<option value="restart">{{ translate('Restart') }}</option>');
            $type.val(action.action || 'link');
            $row1.append($enable).append($label).append($style).append($type);

            var $row2 = $('<div class="d-flex flex-wrap gap-2 align-items-center"></div>');
            if (RESULTS_STRUCTURE_LOCKED) $row2.addClass('area-disabled');
            var $url = $('<input type="text" class="theme-input-style results-action-url"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '>');
            $url.attr('placeholder', '{{ translate('URL (for link action)') }}');
            $url.css('min-width', '140px');
            $url.val((action.action === 'link' || action.action === 'top_product') ? (action.url || '') : '');
            var $urlWrap = $('<span class="results-action-url-wrap"></span>');
            $urlWrap.append($url);
            var $hint = $('<small class="results-action-type-hint text-muted"></small>');
            var $bg = $('<input type="color" class="results-action-bg"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '>');
            $bg.val(action.bg_color === 'transparent' ? '#c9a84c' : (action.bg_color || '#c9a84c'));
            var $text = $('<input type="color" class="results-action-text"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '>');
            $text.val(action.text_color || '#1a3d2a');
            var $border = $('<input type="color" class="results-action-border"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '>');
            $border.val(action.border_color || '#c9a84c');
            var $remove = $('<button type="button" class="btn btn-sm btn-outline-danger results-action-remove"' + (RESULTS_STRUCTURE_LOCKED ? ' disabled' : '') + '>&times;</button>');
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
                enabled: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? existingAction.enabled !== false
                    : $r.find('.results-action-enable').is(':checked'),
                label: label,
                style: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? (existingAction.style || 'solid')
                    : ($r.find('.results-action-style').val() || 'solid'),
                action: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? (existingAction.action || 'link')
                    : actionType,
                url: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? (existingAction.url || '')
                    : (actionTypeUsesUrl(actionType) ? ($r.find('.results-action-url').val() || '') : ''),
                product_rank: (existingAction && existingAction.product_rank) ? existingAction.product_rank : 1,
                bg_color: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? (existingAction.bg_color || '#c9a84c')
                    : ($r.find('.results-action-bg').val() || '#c9a84c'),
                text_color: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? (existingAction.text_color || '#1a3d2a')
                    : ($r.find('.results-action-text').val() || '#1a3d2a'),
                border_color: RESULTS_STRUCTURE_LOCKED && existingAction
                    ? (existingAction.border_color || '#c9a84c')
                    : ($r.find('.results-action-border').val() || '#c9a84c')
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

    function toggleResultsHeroFrameControls() {
        var frameStyle = $('#results-hero-frame').val() || 'none';
        $('.results-hero-frame-controls').toggleClass('d-none', frameStyle === 'none');
        $('.results-hero-frame-corner-controls').toggleClass('d-none', frameStyle !== 'corners');
    }

    function renderCardFrameHtml(theme) {
        var frameStyle = theme.hero_frame || 'none';
        if (frameStyle === 'none') return '';
        var inset = parseInt(theme.hero_frame_inset, 10) || 10;
        var thickness = parseInt(theme.hero_frame_thickness, 10) || 2;
        var cornerSize = parseInt(theme.hero_frame_corner_size, 10) || 18;
        var frameColor = theme.hero_frame_color || theme.accent_color || '#c9a84c';
        var vars = '--results-frame-color:' + frameColor + ';--results-frame-thickness:' + thickness + 'px;--results-frame-inset:' + inset + 'px;';

        if (frameStyle === 'corners') {
            vars += '--results-frame-corner-size:' + cornerSize + 'px;';
            return '<div class="quiz-results-preview__card-frame quiz-results-preview__card-frame--corners" style="' + vars + '">' +
                '<span class="quiz-results-preview__corner quiz-results-preview__corner--tl"></span>' +
                '<span class="quiz-results-preview__corner quiz-results-preview__corner--tr"></span>' +
                '<span class="quiz-results-preview__corner quiz-results-preview__corner--bl"></span>' +
                '<span class="quiz-results-preview__corner quiz-results-preview__corner--br"></span>' +
                '</div>';
        }

        if (frameStyle === 'inset') {
            return '<div class="quiz-results-preview__card-frame quiz-results-preview__card-frame--inset" style="' + vars + '"></div>';
        }

        return '';
    }

    function renderSwatchPreviewHtml(block, theme, previewColor) {
        var swatchSize = Math.min(parseInt(block.size, 10) || 48, 80);
        var glowClass = theme.hero_glow ? ' quiz-results-preview__product-swatch-wrap--glow' : '';
        return '<span class="quiz-results-preview__product-swatch-wrap' + glowClass + '" style="display:block;width:' + swatchSize + 'px;height:' + swatchSize + 'px;margin:8px auto;--results-accent:' + (theme.accent_color || '#c9a84c') + ';">' +
            '<span class="quiz-results-preview__product-swatch-halo" aria-hidden="true"></span>' +
            '<span style="display:block;width:100%;height:100%;border-radius:50%;background:' + previewColor + ';border:2px solid rgba(255,255,255,0.45);position:relative;z-index:1;"></span>' +
            '</span>';
    }

    function renderProductImagePreviewHtml(block, theme, previewColor) {
        var size = Math.min(parseInt(block.size, 10) || 80, 160);
        var outerStyle = 'width:' + size + 'px;height:' + size + 'px;margin:8px auto;position:relative;border-radius:50%;background:linear-gradient(135deg,' + previewColor + ',rgba(255,255,255,0.28));overflow:hidden;';
        return '<div class="quiz-results-preview__product-image" style="' + outerStyle + '"></div>';
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
        if (layout === 'featured_card' || layout === 'featured_and_grid' || layout === 'product_color_card') {
            var frameStyle = theme.hero_frame || 'none';
            var frameColor = theme.hero_frame_color || theme.accent_color || '#c9a84c';
            var cardStyle = 'max-width:' + (theme.card_max_width || 480) + 'px;margin:0 auto;padding:' + (theme.card_padding || 32) + 'px;position:relative;';
            if (theme.card_enabled) {
                cardStyle += 'background:' + (theme.card_background || '#1f4530') + ';';
                cardStyle += 'border-radius:' + (theme.card_border_radius || 0) + 'px;';
                if (theme.card_border_style === 'double') {
                    cardStyle += 'border:3px double ' + (theme.card_border_color || '#c9a84c') + ';';
                } else if (theme.card_border_style === 'single') {
                    cardStyle += 'border:1px solid ' + (theme.card_border_color || '#c9a84c') + ';';
                }
            }
            if (frameStyle !== 'none' && theme.hero_glow) {
                cardStyle += 'filter:drop-shadow(0 0 12px ' + frameColor + ');';
            }
            html += '<div style="text-align:center;' + cardStyle + '">';
            html += '<div class="quiz-results-preview__card-content">';
            var previewColor = getPreviewProductContext().color || '#c9a84c';
            filterBlocksForPreview(blocks).forEach(function(block) {
                if (block.type === 'product_image') {
                    var isSwatch = block.display_mode === 'swatch';
                    if (isSwatch) {
                        html += renderSwatchPreviewHtml(block, theme, previewColor);
                    } else {
                        html += renderProductImagePreviewHtml(block, theme, previewColor);
                    }
                } else if (block.type === 'match_badge') {
                    html += '<span style="display:inline-block;background:#ff5a1f;color:#fff;font-size:11px;padding:4px 10px;border-radius:999px;margin:8px 0">' + MOCK_PRODUCT.match_pct + '% match</span>';
                } else if (block.type === 'hero_image') {
                    html += '<div style="width:' + (theme.hero_size || 80) + 'px;height:' + (theme.hero_size || 80) + 'px;margin:0 auto 12px;background:' + (theme.accent_color || '#c9a84c') + ';border-radius:50%;opacity:0.5"></div>';
                } else if (block.html) {
                    var wrapperStyle = blockWrapperStyleString(block, theme);
                    html += '<div style="margin:8px 0' + (wrapperStyle ? ';' + wrapperStyle : '') + '">' + resolveTokens(block.html) + '</div>';
                } else if (block.type === 'separator') {
                    html += renderSeparatorPreviewHtml(block, theme);
                }
            });
            html += '</div>';
            html += renderCardFrameHtml(theme);
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
        collectProfilesFromDom();
        readThemeFromPanel();
        readProductGridFromPanel();
        if (typeof window.updateQuizResultsPreview === 'function') {
            window.updateQuizResultsPreview();
        }
    };

    function toggleLayoutSections() {
        var mode = $('#results-layout-mode').val() || 'featured_card';
        var showCard = mode !== 'product_grid';
        $('#results-blocks-panel').toggleClass('d-none', !showCard);
        $('#results-card-theme-section').toggleClass('d-none', !showCard);
        $('#results-product-grid-section').toggleClass('d-none', mode === 'featured_card' || mode === 'product_color_card');
        $('#results-product-profiles-panel').toggleClass('d-none', mode !== 'product_color_card');
        $('#results-product-text-style-section').toggleClass('d-none', mode !== 'product_color_card');
    }

    $(function() {
        if (!$('#results-blocks-json').length && !$('#results-actions-json').length && !$('#results-product-profiles-json').length) return;

        repairActionsJsonOnInit();
        renderActionsList();
        seedBlocksFromDataElement();
        if (isProductColorCardLayout()) {
            var parsedBlocks = parseJsonFieldStrict($('#results-blocks-json').val());
            if (parsedBlocks !== null) {
                var initBlocks = upgradeProductColorCardBlocks(parsedBlocks);
                if (JSON.stringify(initBlocks) !== JSON.stringify(parsedBlocks)) {
                    setBlocks(initBlocks);
                }
            } else {
                console.error('Results blocks: could not parse #results-blocks-json on init');
            }
        }
        try {
            renderBlockList();
        } catch (e) {
            console.error('Results block list init failed', e);
        }
        toggleLayoutSections();
        toggleResultsHeroFrameControls();
        window.updateQuizResultsPreview();

        if ($('#results-product-search').length) {
            $('#results-product-search').select2({
                placeholder: '{{ translate('Search products') }}',
                allowClear: true,
                ajax: {
                    url: '{{ route('theme.tlcommerce.quiz.search.products') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { q: params.term || '' };
                    },
                    processResults: function(data) {
                        return { results: data.results || [] };
                    }
                }
            });
        }

        $('#results-add-product-profile').on('click', function() {
            var selected = $('#results-product-search').select2('data')[0];
            if (!selected || !selected.id) return;
            var productId = parseInt(selected.id, 10);
            if ($('#results-product-profiles-body tr[data-product-id="' + productId + '"]').length) return;
            $('#results-product-profiles-body').append(buildProfileRowHtml(productId, selected.text, '#c9a84c', '', '', '', 'Silver BEE'));
            $('#results-product-search').val(null).trigger('change');
            syncResultsBuilder();
        });

        $(document).on('click', '.results-profile-remove', function() {
            $(this).closest('tr').remove();
            syncResultsBuilder();
        });

        $(document).on('change input', '.results-profile-color-input, .results-profile-tagline-input, .results-profile-description-input, .results-profile-quote-input, .results-profile-collection-input', function() {
            syncResultsBuilder();
        });

        $(document).on('change input', '.results-theme-input, .results-grid-input, .results-preview-input', function() {
            if ($(this).attr('id') === 'results-bg-type') {
                $('.results-bg-solid-row').toggleClass('d-none', $(this).val() !== 'solid');
                $('.results-bg-gradient-rows').toggleClass('d-none', $(this).val() !== 'radial_gradient');
            }
            if ($(this).attr('id') === 'results-hero-frame') {
                toggleResultsHeroFrameControls();
            }
            if ($(this).attr('id') === 'results-layout-mode') {
                toggleLayoutSections();
                if ($(this).val() === 'product_color_card') {
                    applyProductColorPresetIfNeeded();
                }
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
                var preset = ($('#results-layout-mode').val() === 'product_color_card')
                    ? DEFAULT_PRODUCT_COLOR_BLOCKS
                    : DEFAULT_BLOCKS;
                setBlocks(JSON.parse(JSON.stringify(preset)));
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

        $(document).on('change input', '.results-block-appearance-panel select, .results-block-appearance-panel input', function() {
            var $panel = $(this).closest('.results-block-appearance-panel');
            if ($panel.length && $(this).hasClass('results-app-text-color')) {
                $panel.find('.results-app-text-color-custom').toggle($(this).val() === 'custom');
            }
            if ($panel.length && $(this).hasClass('results-app-bg-color')) {
                $panel.find('.results-app-bg-color-custom').toggle($(this).val() === 'custom');
            }
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
