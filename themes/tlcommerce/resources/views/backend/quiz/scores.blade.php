@extends('core::base.layouts.master')
@section('title')
    {{ translate('Product Scores') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
@endsection
@section('main_content')
    <div class="border-bottom2 pb-3 mb-4">
        <h4 class="mb-1">{{ translate('Product Scores') }}</h4>
        <p class="text-muted mb-2">{{ translate('Answer') }}: <strong>{{ $answer->answer_text }}</strong></p>
        <p class="text-muted mb-2">{{ translate('Question') }}: {{ $question->question_text }}</p>
        <a href="{{ route('theme.tlcommerce.quiz.answers', $question->id) }}" class="btn long">{{ translate('Back to Answers') }}</a>
    </div>

    <div class="card mb-20">
        <div class="card-body">
            <form action="{{ route('theme.tlcommerce.quiz.scores.save') }}" method="POST" id="scores-form">
                @csrf
                <input type="hidden" name="answer_id" value="{{ $answer->id }}">

                <div class="form-row mb-20 align-items-end">
                    <div class="col-md-8">
                        <label class="font-14 bold black">{{ translate('Add Product') }}</label>
                        <select id="product-search" class="theme-input-style w-100"></select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn long btn-orange" id="add-product-row">{{ translate('Add to list') }}</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="hoverable text-nowrap border-top2" id="scores-table">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>{{ translate('Product') }}</th>
                                <th style="width: 180px;">{{ translate('Score') }}</th>
                                <th style="width: 100px;">{{ translate('Remove') }}</th>
                            </tr>
                        </thead>
                        <tbody id="scores-body">
                            @foreach ($answer->productScores as $index => $scoreRow)
                                @if ($scoreRow->product)
                                    <tr data-product-id="{{ $scoreRow->product_id }}">
                                        <td>
                                            {{ $scoreRow->product->translation('name', getLocale()) }}
                                            <input type="hidden" name="scores[{{ $index }}][product_id]" value="{{ $scoreRow->product_id }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="theme-input-style"
                                                name="scores[{{ $index }}][score]" value="{{ $scoreRow->score }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn long btn-sm btn-danger remove-score-row">{{ translate('Remove') }}</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-20">
                    <button type="submit" class="btn long btn-orange">{{ translate('Save Scores') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            var rowIndex = {{ max($answer->productScores->count(), 0) }};

            $('#product-search').select2({
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
                        return { results: data.results };
                    }
                }
            });

            $('#add-product-row').on('click', function() {
                var selected = $('#product-search').select2('data')[0];
                if (!selected || !selected.id) {
                    return;
                }
                if ($('#scores-body tr[data-product-id="' + selected.id + '"]').length) {
                    return;
                }

                var row = '<tr data-product-id="' + selected.id + '">' +
                    '<td>' + selected.text +
                    '<input type="hidden" name="scores[' + rowIndex + '][product_id]" value="' + selected.id + '"></td>' +
                    '<td><input type="number" step="0.01" min="0" class="theme-input-style" name="scores[' + rowIndex + '][score]" value="0"></td>' +
                    '<td><button type="button" class="btn long btn-sm btn-danger remove-score-row">{{ translate('Remove') }}</button></td>' +
                    '</tr>';

                $('#scores-body').append(row);
                rowIndex++;
                $('#product-search').val(null).trigger('change');
            });

            $(document).on('click', '.remove-score-row', function() {
                $(this).closest('tr').remove();
            });
        })(jQuery);
    </script>
@endsection
