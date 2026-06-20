<script>
(function($) {
    'use strict';

    var QA_FILL_FIELDS = [
        { baseKey: 'background', suffix: 'bg', solidFallback: '#ffffff', edgeFallback: '#050a07' },
        { baseKey: 'border_color', suffix: 'border', solidFallback: '#e2e2e2', edgeFallback: '#050a07' },
        { baseKey: 'text_color', suffix: 'text', solidFallback: '#111111', edgeFallback: '#050a07' },
        { baseKey: 'hover_background', suffix: 'hover-bg', solidFallback: '#f3f4f6', edgeFallback: '#050a07' },
        { baseKey: 'hover_border_color', suffix: 'hover-border', solidFallback: '#d1d5db', edgeFallback: '#050a07' },
        { baseKey: 'active_background', suffix: 'active-bg', solidFallback: '#f8f9fa', edgeFallback: '#050a07' },
        { baseKey: 'active_border_color', suffix: 'active-border', solidFallback: '#333333', edgeFallback: '#050a07' },
        { baseKey: 'active_text_color', suffix: 'active-text', solidFallback: '#111111', edgeFallback: '#050a07' }
    ];

    function hexFromInput(id, fallback) {
        var val = $('#' + id).val();
        return val || fallback;
    }

    function resolveRadialFill(style, baseKey, solidFallback, edgeFallback) {
        var typeKey = baseKey + '_type';
        var centerKey = baseKey + '_gradient_center';
        var edgeKey = baseKey + '_gradient_edge';
        var fillType = style[typeKey] || 'solid';

        if (fillType === 'radial_gradient') {
            var center = style[centerKey] || style[baseKey] || solidFallback;
            var edge = style[edgeKey] || edgeFallback;
            return 'radial-gradient(circle at center, ' + center + ' 0%, ' + edge + ' 100%)';
        }

        return style[baseKey] || solidFallback;
    }

    function isGradientFill(style, baseKey) {
        return (style[baseKey + '_type'] || 'solid') === 'radial_gradient';
    }

    function readFillFromDom(prefix, fieldDef) {
        var baseKey = fieldDef.baseKey;
        var suffix = fieldDef.suffix;
        var type = $('#' + prefix + '-' + suffix + '-type').val() || 'solid';
        var result = {};
        result[baseKey + '_type'] = type;
        result[baseKey] = hexFromInput(prefix + '-' + suffix, fieldDef.solidFallback);
        result[baseKey + '_gradient_center'] = hexFromInput(prefix + '-' + suffix + '-gradient-center', fieldDef.solidFallback);
        result[baseKey + '_gradient_edge'] = hexFromInput(prefix + '-' + suffix + '-gradient-edge', fieldDef.edgeFallback);
        return result;
    }

    function readStyleFromPrefix(prefix) {
        var style = {
            gap: parseInt($('#' + prefix + '-gap').val(), 10) || 8,
            border_width: parseInt($('#' + prefix + '-border-width').val(), 10) || 1,
            padding: parseInt($('#' + prefix + '-padding').val(), 10) || 12,
            radius: parseInt($('#' + prefix + '-radius').val(), 10) || 8,
            show_native_input: $('#' + prefix + '-show-input').is(':checked')
        };

        QA_FILL_FIELDS.forEach(function(fieldDef) {
            $.extend(style, readFillFromDom(prefix, fieldDef));
        });

        if ($('#' + prefix + '-grid-columns').length) {
            style.grid_columns = parseInt($('#' + prefix + '-grid-columns').val(), 10) || 2;
            style.image_size = parseInt($('#' + prefix + '-image-size').val(), 10) || 80;
            style.image_position = $('#' + prefix + '-image-position').val() || 'top';
            var hoverScale = parseFloat($('#' + prefix + '-hover-scale').val()) || 1;
            style.hover_scale = Math.max(1, Math.min(1.2, hoverScale));
        }

        return style;
    }

    function resolveFillCss(style, baseKey, solidFallback, edgeFallback) {
        return resolveRadialFill(style, baseKey, solidFallback, edgeFallback);
    }

    function resolveFillLayer(style, baseKey, solidFallback, edgeFallback) {
        var css = resolveFillCss(style, baseKey, solidFallback, edgeFallback);
        if (isGradientFill(style, baseKey)) {
            return css;
        }
        return 'linear-gradient(' + css + ', ' + css + ')';
    }

    function applyTextFill($el, style, baseKey, solidFallback, edgeFallback) {
        var css = resolveFillCss(style, baseKey, solidFallback, edgeFallback);
        if (isGradientFill(style, baseKey)) {
            $el.css({
                background: css,
                backgroundClip: 'text',
                webkitBackgroundClip: 'text',
                webkitTextFillColor: 'transparent',
                color: 'transparent'
            });
        } else {
            $el.css({
                background: '',
                backgroundClip: '',
                webkitBackgroundClip: '',
                webkitTextFillColor: '',
                color: css
            });
        }
    }

    function applyAnswerItemStyles($item, style, state, layout) {
        var isActive = state === 'active';
        var bgKey = isActive ? 'active_background' : 'background';
        var borderKey = isActive ? 'active_border_color' : 'border_color';
        var textKey = isActive ? 'active_text_color' : 'text_color';
        var bgField = QA_FILL_FIELDS.find(function(f) { return f.baseKey === bgKey; });
        var borderField = QA_FILL_FIELDS.find(function(f) { return f.baseKey === borderKey; });
        var textField = QA_FILL_FIELDS.find(function(f) { return f.baseKey === textKey; });
        var bgLayer = resolveFillLayer(style, bgKey, bgField.solidFallback, bgField.edgeFallback);
        var borderLayer = resolveFillLayer(style, borderKey, borderField.solidFallback, borderField.edgeFallback);
        var borderWidth = (style.border_width || 1) + 'px';
        var radius = (style.radius || 8) + 'px';
        var padding = (style.padding || 12) + 'px';
        var hoverScale = style.hover_scale || 1;
        var itemCss = {
            border: borderWidth + ' solid transparent',
            borderRadius: radius,
            padding: padding,
            backgroundImage: bgLayer + ', ' + borderLayer,
            backgroundOrigin: 'padding-box, border-box',
            backgroundClip: 'padding-box, border-box',
            backgroundColor: 'transparent'
        };

        if (layout === 'grid' && hoverScale > 1 && isActive) {
            itemCss.transform = 'scale(' + hoverScale + ')';
            itemCss.transformOrigin = 'center center';
            itemCss.zIndex = 1;
        } else {
            itemCss.transform = '';
            itemCss.transformOrigin = '';
            itemCss.zIndex = '';
        }

        $item.css(itemCss);

        applyTextFill($item, style, textKey, textField.solidFallback, textField.edgeFallback);
        $item.find('strong, small').each(function() {
            applyTextFill($(this), style, textKey, textField.solidFallback, textField.edgeFallback);
        });
    }

    function toggleFillTypeRows($select) {
        var $field = $select.closest('.qa-fill-color-field');
        if (!$field.length) {
            return;
        }
        var prefix = $field.data('prefix');
        var suffix = $field.data('suffix');
        var type = $select.val() || 'solid';
        $('.' + prefix + '-' + suffix + '-solid-row').toggleClass('d-none', type !== 'solid');
        $('.' + prefix + '-' + suffix + '-gradient-rows').toggleClass('d-none', type !== 'radial_gradient');
    }

    function initFillTypeToggles(containerSelector) {
        $(containerSelector).find('.qa-fill-type-select').each(function() {
            toggleFillTypeRows($(this));
        });
    }

    window.QAStyleUtils = {
        FILL_FIELDS: QA_FILL_FIELDS,
        hexFromInput: hexFromInput,
        resolveRadialFill: resolveRadialFill,
        resolveFillCss: resolveFillCss,
        resolveFillLayer: resolveFillLayer,
        isGradientFill: isGradientFill,
        readFillFromDom: readFillFromDom,
        readStyleFromPrefix: readStyleFromPrefix,
        applyAnswerItemStyles: applyAnswerItemStyles,
        applyTextFill: applyTextFill,
        toggleFillTypeRows: toggleFillTypeRows,
        initFillTypeToggles: initFillTypeToggles
    };

    $(document).on('input', '.color-picker.qa-q-input, .color-picker.qa-def-input', function() {
        $(this).closest('.input-group').find('.color-input').val(this.value).trigger('input');
    });
})(jQuery);
</script>
