<style>
    .note-editable .note-video-clip,
    .note-editable video.note-video-clip {
        display: block;
        max-width: 100%;
        min-height: 200px;
    }
</style>

<script>
    (function($) {
        "use strict";

        var IFRAME_ALLOW =
            'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';

        window.SUMMERNOTE_CONTENT_FILTER_REGEX =
            /<\/*(?:applet|b(?:ase|gsound|link)|embed|(?<![a-z])frameset(?=[\s>/])|(?<![a-z])frame(?=[\s>/])|ilayer|l(?:ayer|ink)|meta|object|script|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi;

        function buildEmbedIframe(src) {
            return $('<iframe>')
                .attr('frameborder', 0)
                .attr('src', src)
                .attr('width', '640')
                .attr('height', '360')
                .attr('allow', IFRAME_ALLOW)
                .attr('allowfullscreen', true)
                .attr('contenteditable', false);
        }

        window.createSummernoteVideoNode = function(url) {
            url = (url || '').trim();
            if (!url) {
                return null;
            }

            var $video = null;
            var ytMatch = url.match(
                /(?:youtube\.com\/(?:[^?]+\?.*v=|watch\?.*v=|embed\/|shorts\/|live\/|v\/)|youtu\.be\/)([\w-]{11})/
            );
            var vimMatch = url.match(
                /(?:vimeo\.com\/(?:channels\/[^/]+\/|groups\/[^/]+\/videos\/|video\/)?|player\.vimeo\.com\/video\/)(\d+)/
            );
            var dmMatch = url.match(/dailymotion\.com\/(?:video|embed\/video)\/([a-zA-Z0-9]+)/);
            var mp4Match = url.match(/\.(mp4|m4v|webm|ogg)(\?.*)?$/i);

            if (ytMatch && ytMatch[1]) {
                $video = buildEmbedIframe('https://www.youtube.com/embed/' + ytMatch[1]);
            } else if (vimMatch && vimMatch[1]) {
                $video = buildEmbedIframe('https://player.vimeo.com/video/' + vimMatch[1]);
            } else if (dmMatch && dmMatch[1]) {
                $video = buildEmbedIframe('https://www.dailymotion.com/embed/video/' + dmMatch[1]);
            } else if (mp4Match) {
                $video = $('<video controls>')
                    .attr('src', url)
                    .attr('width', '640')
                    .attr('height', '360')
                    .attr('contenteditable', false);
            }

            if (!$video) {
                return null;
            }

            $video.addClass('note-video-clip');
            return $video[0];
        };

        function patchSummernoteVideoDialog($note) {
            var context = $note.data('summernote');
            var videoDialog = context && context.modules && context.modules.videoDialog;
            if (!videoDialog) {
                return;
            }

            videoDialog.createVideoNode = function(url) {
                var node = window.createSummernoteVideoNode(url);
                if (!node && url && typeof toastr !== 'undefined') {
                    toastr.error(
                        'Unsupported video URL. Use YouTube, Vimeo, Dailymotion, or a direct .mp4 link.',
                        'Error!'
                    );
                }
                return node;
            };
        }

        window.getSummernoteContentEditorOptions = function(extraOptions) {
            extraOptions = extraOptions || {};
            var extraCallbacks = extraOptions.callbacks || {};
            delete extraOptions.callbacks;

            var userOnInit = extraCallbacks.onInit;
            delete extraCallbacks.onInit;

            var options = $.extend(true, {
                tabsize: 2,
                height: 200,
                codeviewIframeFilter: true,
                codeviewFilter: true,
                codeviewFilterRegex: SUMMERNOTE_CONTENT_FILTER_REGEX,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "codeview", "help"]],
                ],
                placeholder: 'Content',
            }, extraOptions);

            options.callbacks = $.extend({}, options.callbacks, extraCallbacks);
            options.callbacks.onInit = function() {
                var $note = $(this);
                patchSummernoteVideoDialog($note);

                var $form = $note.closest('form');
                if ($form.length) {
                    $form.off('submit.summernoteSync').on('submit.summernoteSync', function() {
                        if ($note.data('summernote')) {
                            $note.val($note.summernote('code'));
                        }
                    });
                }

                if (typeof userOnInit === 'function') {
                    userOnInit.apply(this, arguments);
                }
            };

            return options;
        };
    })(jQuery);
</script>
