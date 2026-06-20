<script>

(function($) {

    'use strict';



    if (!window.QAStyleUtils) {

        return;

    }



    var readStyleFromPrefix = window.QAStyleUtils.readStyleFromPrefix;

    var applyAnswerItemStyles = window.QAStyleUtils.applyAnswerItemStyles;

    var toggleFillTypeRows = window.QAStyleUtils.toggleFillTypeRows;

    var initFillTypeToggles = window.QAStyleUtils.initFillTypeToggles;



    function readAnswerDefaults() {

        return {

            list: readStyleFromPrefix('qa-def-list'),

            grid: readStyleFromPrefix('qa-def-grid')

        };

    }



    function syncAnswerDefaultsJson() {

        $('#answer-defaults-json').val(JSON.stringify(readAnswerDefaults()));

    }



    function activeDefaultsTab() {

        return $('#qa-def-tab-grid').hasClass('active') ? 'grid' : 'list';

    }



    function applyPreviewStyle(style, layout) {

        var $wrap = $('#qa-def-preview-answers');

        $wrap.removeClass('qa-def-preview-answers--list qa-def-preview-answers--grid');

        $wrap.addClass('qa-def-preview-answers--' + layout);

        $wrap.css('gap', (style.gap || 8) + 'px');



        if (layout === 'grid') {

            $wrap.css('gridTemplateColumns', 'repeat(' + (style.grid_columns || 2) + ', 1fr)');

        } else {

            $wrap.css('gridTemplateColumns', '');

        }



        $wrap.find('.qa-def-preview-item').each(function(i) {

            applyAnswerItemStyles($(this), style, i === 0 ? 'active' : 'default', layout);

        });

    }



    function updateAnswerDefaultsPreview() {

        var layout = activeDefaultsTab();

        var defaults = readAnswerDefaults();

        applyPreviewStyle(defaults[layout], layout);

        syncAnswerDefaultsJson();

    }



    window.readAnswerDefaultsFromPanel = readAnswerDefaults;

    window.syncAnswerDefaultsJson = syncAnswerDefaultsJson;



    $(document).on('change input', '.qa-def-input', updateAnswerDefaultsPreview);

    $(document).on('change', '.qa-def-input.qa-fill-type-select', function() {

        toggleFillTypeRows($(this));

    });

    $('a[data-toggle="tab"][href="#qa-def-panel-list"], a[data-toggle="tab"][href="#qa-def-panel-grid"]').on('shown.bs.tab', updateAnswerDefaultsPreview);

    $('#answer-defaults-form').on('submit', syncAnswerDefaultsJson);



    $(document).ready(function() {

        initFillTypeToggles('#answer-defaults-form');

        updateAnswerDefaultsPreview();

    });

})(jQuery);

</script>

