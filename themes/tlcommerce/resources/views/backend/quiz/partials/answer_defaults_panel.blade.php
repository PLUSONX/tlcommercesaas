@php
    use Theme\TLCommerce\Http\Resources\QuizQuestionsConfig;
    $questionsConfig = QuizQuestionsConfig::normalize(isset($quiz) ? ($quiz->layout_config['questions'] ?? null) : null);
    $answerDefaults = $questionsConfig['answers_defaults'];
@endphp

<div class="card mb-20">
    <div class="card-body">
        <h5 class="mb-2">{{ translate('Default answer styling') }}</h5>
        <p class="text-muted small mb-3">{{ translate('These defaults apply to all radio and checkbox questions unless a question uses custom styling on its Answers page.') }}</p>

        <form action="{{ route('theme.tlcommerce.quiz.answer.defaults', $quiz->id) }}" method="POST" id="answer-defaults-form">
            @csrf
            <input type="hidden" name="answer_defaults_json" id="answer-defaults-json" value='@json($answerDefaults)'>

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="qa-def-tab-list" data-toggle="tab" href="#qa-def-panel-list" role="tab">{{ translate('List') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="qa-def-tab-grid" data-toggle="tab" href="#qa-def-panel-grid" role="tab">{{ translate('Grid') }}</a>
                </li>
            </ul>

            <div class="tab-content mb-3">
                <div class="tab-pane fade show active" id="qa-def-panel-list" role="tabpanel">
                    @include('theme/tlcommerce::backend.quiz.partials.answer_style_fields', [
                        'prefix' => 'qa-def-list',
                        'fieldClass' => 'qa-def-input',
                        'layout' => 'list',
                        'style' => $answerDefaults['list'] ?? [],
                    ])
                </div>
                <div class="tab-pane fade" id="qa-def-panel-grid" role="tabpanel">
                    @include('theme/tlcommerce::backend.quiz.partials.answer_style_fields', [
                        'prefix' => 'qa-def-grid',
                        'fieldClass' => 'qa-def-input',
                        'layout' => 'grid',
                        'style' => $answerDefaults['grid'] ?? [],
                    ])
                </div>
            </div>

            <div class="qa-def-preview-wrap mb-3 p-3 border rounded" id="qa-def-preview">
                <p class="small text-muted mb-2">{{ translate('Preview') }}</p>
                <div class="qa-def-preview-answers qa-def-preview-answers--list" id="qa-def-preview-answers">
                    <div class="qa-def-preview-item border">{{ translate('Answer option A') }}</div>
                    <div class="qa-def-preview-item border">{{ translate('Answer option B') }}</div>
                </div>
            </div>

            <button type="submit" class="btn long btn-orange">{{ translate('Save default styling') }}</button>
        </form>
    </div>
</div>

<style>
    .qa-def-preview-wrap { background: #f9fafb; }
    .qa-def-preview-answers { display: flex; flex-direction: column; gap: 8px; }
    .qa-def-preview-answers--grid { display: grid; grid-template-columns: repeat(2, 1fr); }
    .qa-def-preview-item { padding: 12px; border-width: 1px; border-style: solid; cursor: default; }
</style>
