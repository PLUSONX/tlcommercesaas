<script>
(function($) {
    'use strict';

    var QQ_DEFAULTS = {
        background_type: 'solid',
        background_color: '#f7f8fa',
        background_gradient_center: '#ffffff',
        background_gradient_edge: '#050a07',
        fill_viewport: true,
        content_max_width: 640,
        alignment: 'left',
        card_enabled: true,
        card_background: '#ffffff',
        card_border_color: '#e5e7eb',
        card_border_radius: 12,
        card_padding: 24,
        card_shadow: true,
        question_color: '#111111',
        required_color: '#dc3545',
        progress_track_color: '#e9ecef',
        progress_bar_color: '#ff5a1f',
        progress_height: 6,
        progress_label_color: '#6b7280',
        show_progress: true,
        show_back_button: true,
        show_next_button: true,
        btn_back_label: 'Back',
        btn_next_label: 'Next',
        btn_submit_label: 'See Results',
        btn_back_color: '#111111',
        btn_back_text_color: '#111111',
        btn_next_color: '#ff5a1f',
        btn_next_text_color: '#ffffff',
        btn_border_radius: 4,
        error_color: '#dc3545'
    };

    function hexFromInput(id, fallback) {
        var val = $('#' + id).val();
        return val || fallback;
    }

    function getQuestionsTheme() {
        try {
            return $.extend({}, QQ_DEFAULTS, JSON.parse($('#questions-theme-json').val() || '{}'));
        } catch (e) {
            return $.extend({}, QQ_DEFAULTS);
        }
    }

    function setQuestionsTheme(theme) {
        $('#questions-theme-json').val(JSON.stringify(theme));
    }

    function readQuestionsThemeFromPanel() {
        var theme = getQuestionsTheme();
        theme.background_type = $('#qq-bg-type').val() || 'solid';
        theme.background_color = hexFromInput('qq-bg-color', '#f7f8fa');
        theme.background_gradient_center = hexFromInput('qq-bg-center', '#ffffff');
        theme.background_gradient_edge = hexFromInput('qq-bg-edge', '#050a07');
        theme.fill_viewport = $('#qq-fill-viewport').is(':checked');
        theme.content_max_width = parseInt($('#qq-max-width').val(), 10) || 640;
        theme.alignment = $('#qq-alignment').val() || 'left';
        theme.card_enabled = $('#qq-card-enabled').is(':checked');
        theme.card_background = hexFromInput('qq-card-bg', '#ffffff');
        theme.card_border_color = hexFromInput('qq-card-border', '#e5e7eb');
        theme.card_border_radius = parseInt($('#qq-card-radius').val(), 10) || 12;
        theme.card_padding = parseInt($('#qq-card-padding').val(), 10) || 24;
        theme.card_shadow = $('#qq-card-shadow').is(':checked');
        theme.question_color = hexFromInput('qq-question-color', '#111111');
        theme.required_color = hexFromInput('qq-required-color', '#dc3545');
        theme.progress_track_color = hexFromInput('qq-progress-track', '#e9ecef');
        theme.progress_bar_color = hexFromInput('qq-progress-bar', '#ff5a1f');
        theme.progress_height = parseInt($('#qq-progress-height').val(), 10) || 6;
        theme.progress_label_color = hexFromInput('qq-progress-label', '#6b7280');
        theme.show_progress = $('#qq-show-progress').is(':checked');
        theme.show_back_button = $('#qq-show-back-btn').is(':checked');
        theme.show_next_button = $('#qq-show-next-btn').is(':checked');
        theme.btn_back_label = ($('#qq-btn-back-label').val() || 'Back').trim().substring(0, 40);
        theme.btn_next_label = ($('#qq-btn-next-label').val() || 'Next').trim().substring(0, 40);
        theme.btn_submit_label = ($('#qq-btn-submit-label').val() || 'See Results').trim().substring(0, 40);
        theme.btn_back_color = hexFromInput('qq-btn-back', '#111111');
        theme.btn_back_text_color = hexFromInput('qq-btn-back-text', '#111111');
        theme.btn_next_color = hexFromInput('qq-btn-next', '#ff5a1f');
        theme.btn_next_text_color = hexFromInput('qq-btn-next-text', '#ffffff');
        theme.btn_border_radius = parseInt($('#qq-btn-radius').val(), 10) || 4;
        theme.error_color = hexFromInput('qq-error-color', '#dc3545');
        setQuestionsTheme(theme);
        return theme;
    }

    function pageBackground(theme) {
        if (theme.background_type === 'radial_gradient') {
            var center = theme.background_gradient_center || '#ffffff';
            var edge = theme.background_gradient_edge || '#050a07';
            return 'radial-gradient(circle at center, ' + center + ' 0%, ' + edge + ' 100%)';
        }
        return theme.background_color || '#f7f8fa';
    }

    function updateQuestionsPreview() {
        var theme = readQuestionsThemeFromPanel();
        var $root = $('#quiz-questions-preview');
        if (!$root.length) return;

        var bg = pageBackground(theme);
        var minH = theme.fill_viewport ? '280px' : 'auto';
        $root.css({
            background: bg,
            minHeight: minH,
            color: theme.question_color,
            textAlign: theme.alignment
        });

        var $inner = $root.find('.qq-preview-inner');
        $inner.css({ maxWidth: (theme.content_max_width || 640) + 'px' });

        var $card = $root.find('.qq-preview-card');
        if (theme.card_enabled) {
            $card.css({
                background: theme.card_background,
                borderColor: theme.card_border_color,
                borderStyle: 'solid',
                borderWidth: '1px',
                borderRadius: (theme.card_border_radius || 12) + 'px',
                padding: (theme.card_padding || 24) + 'px',
                boxShadow: theme.card_shadow ? '0 4px 24px rgba(0,0,0,0.08)' : 'none'
            });
        } else {
            $card.css({
                background: 'transparent',
                borderColor: 'transparent',
                borderStyle: 'none',
                borderWidth: 0,
                borderRadius: 0,
                padding: 0,
                boxShadow: 'none'
            });
        }

        $root.find('.qq-preview-question').css('color', theme.question_color);
        $root.find('.qq-preview-required').css('color', theme.required_color);

        var $progressWrap = $root.find('.qq-preview-progress-wrap');
        if (theme.show_progress) {
            $progressWrap.show();
            $root.find('.qq-preview-progress-label').css('color', theme.progress_label_color);
            $root.find('.qq-preview-progress-track').css({
                background: theme.progress_track_color,
                height: (theme.progress_height || 6) + 'px'
            });
            $root.find('.qq-preview-progress-bar').css({
                background: theme.progress_bar_color,
                width: '33%'
            });
        } else {
            $progressWrap.hide();
        }

        if (theme.show_back_button) {
            $root.find('.qq-preview-btn-back').show().text(theme.btn_back_label || 'Back').css({
                color: theme.btn_back_text_color,
                borderColor: theme.btn_back_color,
                borderRadius: (theme.btn_border_radius || 4) + 'px'
            });
        } else {
            $root.find('.qq-preview-btn-back').hide();
        }

        if (theme.show_next_button) {
            $root.find('.qq-preview-btn-next').show().text(theme.btn_next_label || 'Next').css({
                background: theme.btn_next_color,
                color: theme.btn_next_text_color,
                borderColor: theme.btn_next_color,
                borderRadius: (theme.btn_border_radius || 4) + 'px'
            });
        } else {
            $root.find('.qq-preview-btn-next').hide();
        }
    }

    function toggleQqBackgroundFields() {
        var type = $('#qq-bg-type').val() || 'solid';
        $('.qq-bg-solid-row').toggleClass('d-none', type !== 'solid');
        $('.qq-bg-gradient-rows').toggleClass('d-none', type !== 'radial_gradient');
    }

    function toggleQqCardFields() {
        var enabled = $('#qq-card-enabled').is(':checked');
        $('.qq-card-fields').toggleClass('is-disabled', !enabled);
    }

    window.flushQuestionsThemeState = function() {
        readQuestionsThemeFromPanel();
    };

    window.updateQuestionsPreview = updateQuestionsPreview;

    $(document).on('change input', '.qq-theme-input', function() {
        toggleQqBackgroundFields();
        toggleQqCardFields();
        updateQuestionsPreview();
    });

    $('#qq-bg-type').on('change', toggleQqBackgroundFields);
    $('#qq-card-enabled').on('change', toggleQqCardFields);

    $('#quiz-form').on('submit', function() {
        flushQuestionsThemeState();
    });

    $(document).ready(function() {
        toggleQqBackgroundFields();
        toggleQqCardFields();
        updateQuestionsPreview();
    });

    $('#preview-tab-intro').on('click', function() {
        if (typeof window.showPreviewTab === 'function') {
            window.showPreviewTab('intro');
            return;
        }
        $('#preview-tab-intro').addClass('active');
        $('#preview-tab-questions, #preview-tab-results').removeClass('active');
        $('#preview-intro-panel').removeClass('d-none');
        $('#preview-questions-panel, #preview-results-panel').addClass('d-none');
    });

    $('#preview-tab-questions').on('click', function() {
        if (typeof window.showPreviewTab === 'function') {
            window.showPreviewTab('questions');
            return;
        }
        $('#preview-tab-questions').addClass('active');
        $('#preview-tab-intro, #preview-tab-results').removeClass('active');
        $('#preview-questions-panel').removeClass('d-none');
        $('#preview-intro-panel, #preview-results-panel').addClass('d-none');
        updateQuestionsPreview();
    });
})(jQuery);
</script>
