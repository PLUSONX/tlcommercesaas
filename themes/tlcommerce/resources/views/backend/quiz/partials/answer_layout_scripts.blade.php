<script>

(function($) {

    'use strict';



    if (!$('#question-answer-layout-form').length || !window.QAStyleUtils) {

        return;

    }



    var readStyleFromPrefix = window.QAStyleUtils.readStyleFromPrefix;

    var applyAnswerItemStyles = window.QAStyleUtils.applyAnswerItemStyles;

    var toggleFillTypeRows = window.QAStyleUtils.toggleFillTypeRows;

    var initFillTypeToggles = window.QAStyleUtils.initFillTypeToggles;



    function getQuizDefaults() {

        try {

            return JSON.parse($('#qa-quiz-defaults-json').text() || '{}');

        } catch (e) {

            return {};

        }

    }



    function readQuestionAnswerLayout() {

        var layout = $('#qa-q-layout').val() || 'list';

        var useDefaults = $('#qa-q-use-defaults').is(':checked');

        var payload = {

            use_quiz_defaults: useDefaults,

            layout: layout

        };



        if (!useDefaults) {

            var custom = readStyleFromPrefix(layout === 'grid' ? 'qa-q-grid' : 'qa-q-list');

            $.extend(payload, custom);

        }



        return payload;

    }



    function resolvedPreviewStyle() {

        var layout = $('#qa-q-layout').val() || 'list';

        var useDefaults = $('#qa-q-use-defaults').is(':checked');

        var defaults = getQuizDefaults();

        var base = (defaults[layout] || {});



        if (useDefaults) {

            return base;

        }



        return $.extend({}, base, readStyleFromPrefix(layout === 'grid' ? 'qa-q-grid' : 'qa-q-list'));

    }



    function syncQuestionAnswerLayoutJson() {

        $('#question-answer-layout-json').val(JSON.stringify(readQuestionAnswerLayout()));

    }



    function toggleCustomFields() {

        var useDefaults = $('#qa-q-use-defaults').is(':checked');

        $('.qa-q-custom-fields').toggleClass('is-disabled', useDefaults);

    }



    function toggleLayoutPanels() {

        var layout = $('#qa-q-layout').val() || 'list';

        $('#qa-q-style-list').toggleClass('d-none', layout !== 'list');

        $('#qa-q-style-grid').toggleClass('d-none', layout !== 'grid');

    }



    function updateQuestionAnswerPreview() {

        var layout = $('#qa-q-layout').val() || 'list';

        var style = resolvedPreviewStyle();

        var $wrap = $('#qa-q-preview-answers');



        $wrap.removeClass('qa-q-preview-answers--list qa-q-preview-answers--grid qa-q-preview-answers--image-left');

        $wrap.addClass('qa-q-preview-answers--' + layout);

        if (layout === 'grid' && style.image_position === 'left') {

            $wrap.addClass('qa-q-preview-answers--image-left');

        }



        $wrap.css('gap', (style.gap || 8) + 'px');

        if (layout === 'grid') {

            $wrap.css('gridTemplateColumns', 'repeat(' + (style.grid_columns || 2) + ', 1fr)');

            $('.qa-q-preview-thumb').removeClass('d-none').css({

                width: (style.image_size || 80) + 'px',

                height: (style.image_size || 80) + 'px'

            });

        } else {

            $wrap.css('gridTemplateColumns', '');

            $('.qa-q-preview-thumb').addClass('d-none');

        }



        $wrap.find('.qa-q-preview-item').each(function(i) {

            applyAnswerItemStyles($(this), style, i === 0 ? 'active' : 'default', layout);

        });



        syncQuestionAnswerLayoutJson();

    }



    $(document).on('change input', '.qa-q-input', function() {

        toggleCustomFields();

        toggleLayoutPanels();

        updateQuestionAnswerPreview();

    });



    $(document).on('change', '.qa-q-input.qa-fill-type-select', function() {

        toggleFillTypeRows($(this));

    });



    $('#question-answer-layout-form').on('submit', syncQuestionAnswerLayoutJson);



    $(document).ready(function() {

        initFillTypeToggles('#question-answer-layout-form');

        toggleCustomFields();

        toggleLayoutPanels();

        updateQuestionAnswerPreview();

    });

})(jQuery);

</script>

