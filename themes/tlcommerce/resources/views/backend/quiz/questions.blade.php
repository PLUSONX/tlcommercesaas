@extends('core::base.layouts.master')
@section('title')
    {{ translate('Quiz Questions') }}
@endsection
@section('custom_css')
    <style>
        .ui-state-default { cursor: move; }
    </style>
@endsection
@section('main_content')
    @php
        $lang = $lang ?? getDefaultLang();
        $isDefaultLang = $lang == getDefaultLang();
    @endphp
    <div class="border-bottom2 pb-3 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-10">
        <div>
            <h4 class="mb-1">{{ $quiz->translation('title', $lang) }}</h4>
            <p class="text-muted mb-0">{{ translate('Manage questions for this quiz') }}</p>
        </div>
        <div class="d-flex flex-wrap gap-10">
            <a href="{{ route('theme.tlcommerce.quiz.edit', ['id' => $quiz->id, 'lang' => $lang]) }}" class="btn long">{{ translate('Edit Quiz') }}</a>
            <a href="{{ route('theme.tlcommerce.quiz.list') }}" class="btn long btn-danger">{{ translate('Back') }}</a>
        </div>
    </div>

    @include('theme/tlcommerce::backend.quiz.partials.language_tabs', [
        'tabRoute' => 'theme.tlcommerce.quiz.questions',
        'tabRouteParams' => ['id' => $quiz->id],
    ])

    <div @if (!$isDefaultLang) class="area-disabled" @endif>
        @include('theme/tlcommerce::backend.quiz.partials.answer_defaults_panel')
    </div>

    @if ($isDefaultLang)
    <div class="card mb-20">
        <div class="card-body">
            <h5 class="mb-3">{{ translate('Add Question') }}</h5>
            <form action="{{ route('theme.tlcommerce.quiz.questions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label class="font-14 bold black">{{ translate('Question') }}</label>
                        <textarea name="question_text" class="theme-input-style" rows="2" required placeholder="{{ translate('Question text') }}"></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="font-14 bold black">{{ translate('Type') }}</label>
                        <select name="question_type" class="theme-input-style" required>
                            <option value="radio">{{ translate('Radio') }}</option>
                            <option value="checkbox">{{ translate('Checkbox') }}</option>
                            <option value="dropdown">{{ translate('Dropdown') }}</option>
                            <option value="image_select">{{ translate('Image Select') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <label class="mr-3">
                            <input type="checkbox" name="is_required" value="1" checked>
                            {{ translate('Required') }}
                        </label>
                        <button type="submit" class="btn long btn-orange">{{ translate('Add') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if ($quiz->questions->count())
        <div id="sortable-questions">
            @foreach ($quiz->questions as $question)
                <div class="card mb-20 ui-state-default" data-id="{{ $question->id }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-10">
                            <div class="flex-grow-1">
                                <h5>@if ($isDefaultLang)<i class="icofont-drag mr-1"></i>@endif{{ $question->translation('question_text', $lang) }}</h5>
                                <small class="text-muted">
                                    {{ translate('Type') }}: {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                    · {{ $question->answers->count() }} {{ translate('answers') }}
                                    @if ($question->is_required) · {{ translate('Required') }} @endif
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-10">
                                <a href="{{ route('theme.tlcommerce.quiz.answers', ['id' => $question->id, 'lang' => $lang]) }}" class="btn long btn-sm">
                                    {{ translate('Answers') }}
                                </a>
                                <button type="button" class="btn long btn-sm edit-question-btn"
                                    data-id="{{ $question->id }}"
                                    data-text="{{ e($question->translation('question_text', $lang)) }}"
                                    data-type="{{ $question->question_type }}"
                                    data-required="{{ $question->is_required ? '1' : '0' }}">
                                    {{ translate('Edit') }}
                                </button>
                                @if ($isDefaultLang)
                                <form action="{{ route('theme.tlcommerce.quiz.questions.delete') }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ translate('Are you sure?') }}')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $question->id }}">
                                    <button type="submit" class="btn long btn-sm btn-danger">{{ translate('Delete') }}</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="alert alert-info text-center">{{ translate('No questions yet. Add your first question above.') }}</p>
    @endif

    <div class="modal fade" id="editQuestionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('theme.tlcommerce.quiz.questions.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit-question-id">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Edit Question') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ translate('Question') }}</label>
                            <textarea name="question_text" id="edit-question-text" class="theme-input-style" rows="3" required></textarea>
                        </div>
                        <div class="form-group @if (!$isDefaultLang) area-disabled @endif">
                            <label>{{ translate('Type') }}</label>
                            <select name="question_type" id="edit-question-type" class="theme-input-style" required>
                                <option value="radio">{{ translate('Radio') }}</option>
                                <option value="checkbox">{{ translate('Checkbox') }}</option>
                                <option value="dropdown">{{ translate('Dropdown') }}</option>
                                <option value="image_select">{{ translate('Image Select') }}</option>
                            </select>
                        </div>
                        <label class="@if (!$isDefaultLang) area-disabled @endif">
                            <input type="checkbox" name="is_required" id="edit-question-required" value="1">
                            {{ translate('Required') }}
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn long btn-danger" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn long btn-orange">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            @if ($isDefaultLang)
            $('#sortable-questions').sortable({
                update: function() {
                    var order = [];
                    $('#sortable-questions .card').each(function() {
                        order.push($(this).data('id'));
                    });
                    $.post('{{ route('theme.tlcommerce.quiz.questions.reorder') }}', {
                        _token: '{{ csrf_token() }}',
                        order: order
                    });
                }
            });
            @endif

            $('.edit-question-btn').on('click', function() {
                $('#edit-question-id').val($(this).data('id'));
                $('#edit-question-text').val($(this).data('text'));
                $('#edit-question-type').val($(this).data('type'));
                $('#edit-question-required').prop('checked', $(this).data('required') == '1');
                $('#editQuestionModal').modal('show');
            });
        })(jQuery);
    </script>
    @include('theme/tlcommerce::backend.quiz.partials.answer_style_utils')
    @include('theme/tlcommerce::backend.quiz.partials.answer_defaults_scripts')
@endsection
