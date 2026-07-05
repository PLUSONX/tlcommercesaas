@extends('core::base.layouts.master')
@section('title')
    {{ translate('Quiz Answers') }}
@endsection
@section('custom_css')
    <link href="{{ asset('backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
@endsection
@section('main_content')
    @php
        $lang = $lang ?? getDefaultLang();
        $isDefaultLang = $lang == getDefaultLang();
        $answerDescriptions = [];
        foreach ($question->answers as $answerItem) {
            $answerDescriptions[$answerItem->id] = $answerItem->translation('answer_description', $lang) ?? '';
        }
    @endphp
    <div class="border-bottom2 pb-3 mb-4">
        <h4 class="mb-1">{{ translate('Answers') }}</h4>
        <p class="text-muted mb-2">{{ $question->translation('question_text', $lang) }}</p>
        <div class="d-flex flex-wrap gap-10">
            <a href="{{ route('theme.tlcommerce.quiz.questions', ['id' => $quiz->id, 'lang' => $lang]) }}" class="btn long">{{ translate('Back to Questions') }}</a>
        </div>
    </div>

    @include('theme/tlcommerce::backend.quiz.partials.language_tabs', [
        'tabRoute' => 'theme.tlcommerce.quiz.answers',
        'tabRouteParams' => ['id' => $question->id],
    ])

    <div @if (!$isDefaultLang) class="area-disabled" @endif>
        @include('theme/tlcommerce::backend.quiz.partials.answer_layout_panel')
    </div>

    @if ($isDefaultLang)
    <div class="card mb-20">
        <div class="card-body">
            <h5 class="mb-3">{{ translate('Add Answer') }}</h5>
            <form action="{{ route('theme.tlcommerce.quiz.answers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label class="font-14 bold black">{{ translate('Answer Text') }}</label>
                        <input type="text" name="answer_text" class="theme-input-style" required maxlength="500"
                            placeholder="{{ translate('Answer label') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-14 bold black">{{ translate('Description') }}</label>
                        <textarea name="answer_description" id="add-answer-description" class="theme-input-style answer-description-editor" rows="2" maxlength="2000"
                            placeholder="{{ translate('Optional description shown under the answer title') }}"></textarea>
                        <small class="text-muted d-block mt-1">{{ translate('Use Enter or the paragraph tool for a new line') }}</small>
                    </div>
                </div>
                <div class="form-row align-items-end">
                    <div class="col-md-10 mb-3">
                        <label class="font-14 bold black">{{ translate('Answer Image') }}</label>
                        @include('core::base.includes.media.media_input', [
                            'input' => 'answer_image',
                            'data' => '',
                        ])
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn long btn-orange w-100">{{ translate('Add') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card mb-2" style="border-radius: 12px !important;">
        <div class="table-responsive">
            <table class="hoverable text-nowrap border-top2">
                <thead style="background: #F3F4F6;">
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Answer') }}</th>
                        <th>{{ translate('Description') }}</th>
                        <th>{{ translate('Image') }}</th>
                        <th>{{ translate('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($question->answers as $key => $answer)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $answer->translation('answer_text', $lang) }}</td>
                            <td>{{ $answer->translation('answer_description', $lang) ? \Illuminate\Support\Str::limit($answer->translation('answer_description', $lang), 60) : '—' }}</td>
                            <td>
                                @if ($answer->answer_image)
                                    @php
                                        $imgSrc = str_starts_with($answer->answer_image, 'http')
                                            ? $answer->answer_image
                                            : preg_replace('#^/public#', '', getFilePath($answer->answer_image, false) ?: '');
                                    @endphp
                                    <img src="{{ $imgSrc }}" class="img-45" alt="">
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($isDefaultLang)
                                <a href="{{ route('theme.tlcommerce.quiz.scores', $answer->id) }}" class="btn long btn-sm">
                                    {{ translate('Product Scores') }}
                                </a>
                                @endif
                                <button type="button" class="btn long btn-sm edit-answer-btn"
                                    data-id="{{ $answer->id }}"
                                    data-text="{{ e($answer->translation('answer_text', $lang)) }}"
                                    data-image="{{ e($answer->answer_image) }}">
                                    {{ translate('Edit') }}
                                </button>
                                @if ($isDefaultLang)
                                <form action="{{ route('theme.tlcommerce.quiz.answers.delete') }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ translate('Are you sure?') }}')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $answer->id }}">
                                    <button type="submit" class="btn long btn-sm btn-danger">{{ translate('Delete') }}</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">{{ translate('No answers yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editAnswerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('theme.tlcommerce.quiz.answers.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit-answer-id">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Edit Answer') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ translate('Answer Text') }}</label>
                            <input type="text" name="answer_text" id="edit-answer-text" class="theme-input-style" required maxlength="500">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Description') }}</label>
                            <textarea name="answer_description" id="edit-answer-description" class="theme-input-style answer-description-editor" rows="3" maxlength="2000"></textarea>
                            <small class="text-muted d-block mt-1">{{ translate('Use Enter or the paragraph tool for a new line') }}</small>
                        </div>
                        <div class="form-group mb-0 @if (!$isDefaultLang) area-disabled @endif">
                            <label>{{ translate('Answer Image') }}</label>
                            @include('core::base.includes.media.media_input', [
                                'input' => 'edit_answer_image',
                                'data' => '',
                            ])
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn long btn-danger" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn long btn-orange">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="application/json" id="answer-descriptions-json">@json($answerDescriptions)</script>
@endsection

@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/summernote/summernote-lite.js') }}"></script>
    @include('core::base.media.partial.media_modal')
    @include('theme/tlcommerce::backend.quiz.partials.answer_style_utils')
    @include('theme/tlcommerce::backend.quiz.partials.answer_layout_scripts')
    <script>
        (function($) {
            "use strict";

            var answerDescriptions = {};
            try {
                answerDescriptions = JSON.parse($('#answer-descriptions-json').text() || '{}');
            } catch (e) {
                answerDescriptions = {};
            }

            var summernoteOpts = {
                height: 100,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['codeview']]
                ]
            };

            function initAnswerDescriptionEditor($el) {
                if (!$el.length || $el.next('.note-editor').length) return;
                $el.summernote(summernoteOpts);
                if ($el.val()) {
                    $el.summernote('code', $el.val());
                }
            }

            function syncAnswerDescriptionEditor($el) {
                if (!$el.length) return;
                if ($el.next('.note-editor').length) {
                    $el.val($el.summernote('code') || '');
                }
            }

            function setAnswerDescriptionEditor($el, html) {
                if (!$el.length) return;
                if ($el.next('.note-editor').length) {
                    $el.summernote('code', html || '');
                } else {
                    $el.val(html || '');
                }
            }

            if ($('#add-answer-description').length) {
                initAnswerDescriptionEditor($('#add-answer-description'));
            }

            var pendingEditDescription = '';

            $('#editAnswerModal').on('shown.bs.modal', function() {
                var $editDesc = $('#edit-answer-description');
                initAnswerDescriptionEditor($editDesc);
                setAnswerDescriptionEditor($editDesc, pendingEditDescription);
            });

            $('form').on('submit', function() {
                $(this).find('.answer-description-editor').each(function() {
                    syncAnswerDescriptionEditor($(this));
                });
            });

            $('.edit-answer-btn').on('click', function() {
                var id = $(this).data('id');
                $('#edit-answer-id').val(id);
                $('#edit-answer-text').val($(this).data('text'));
                pendingEditDescription = answerDescriptions[id] || '';
                var imageVal = $(this).data('image') || '';
                $('#edit_answer_image_id').val(imageVal);
                if (typeof filtermedia === 'function') {
                    filtermedia();
                }
                $('#editAnswerModal').modal('show');
            });
        })(jQuery);
    </script>
@endsection
