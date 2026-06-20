@extends('core::base.layouts.master')
@section('title')
    {{ translate('Quiz Builder') }}
@endsection
@section('custom_css')
    @include('core::base.includes.data_table.css')
@endsection
@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 style="font-size: 30px;">{{ translate('Quiz Builder') }}</h4>
                        <a href="{{ route('theme.tlcommerce.quiz.new') }}" class="btn long btn-orange">
                            {{ translate('Add Quiz') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="table-responsive" style="margin-top: 35px;">
                    <table id="quizTable" class="hoverable text-nowrap border-top2">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Slug') }}</th>
                                <th>{{ translate('Questions') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($quizzes as $key => $quiz)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $quiz->title }}</td>
                                    <td><code>{{ $quiz->slug }}</code></td>
                                    <td>
                                        <a href="{{ route('theme.tlcommerce.quiz.questions', $quiz->id) }}">
                                            {{ $quiz->questions_count }} {{ translate('Questions') }}
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('theme.tlcommerce.quiz.update.status') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $quiz->id }}">
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" onchange="this.form.submit()"
                                                    {{ $quiz->is_active ? 'checked' : '' }}>
                                                <span class="control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="dropdown-button">
                                            <a href="#" class="d-flex align-items-center justify-content-center" data-toggle="dropdown">
                                                <div class="menu-icon mr-0">
                                                    <span></span><span></span><span></span>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('theme.tlcommerce.quiz.edit', $quiz->id) }}">{{ translate('Edit') }}</a>
                                                <a href="{{ route('theme.tlcommerce.quiz.questions', $quiz->id) }}">{{ translate('Manage Questions') }}</a>
                                                <a href="#" class="text-danger delete-quiz" data-id="{{ $quiz->id }}">{{ translate('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">{{ translate('No quizzes found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form id="delete-quiz-form" action="{{ route('theme.tlcommerce.quiz.delete') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="id" id="delete-quiz-id">
    </form>
@endsection

@section('custom_scripts')
    @include('core::base.includes.data_table.script')
    <script>
        (function($) {
            "use strict";
            initDataTable('quizTable');

            $('.delete-quiz').on('click', function(e) {
                e.preventDefault();
                if (confirm('{{ translate('Are you sure?') }}')) {
                    $('#delete-quiz-id').val($(this).data('id'));
                    $('#delete-quiz-form').submit();
                }
            });
        })(jQuery);
    </script>
@endsection
