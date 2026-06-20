@php
    use Theme\TLCommerce\Http\Resources\QuizQuestionAnswersConfig;
    use Theme\TLCommerce\Http\Resources\QuizQuestionsConfig;
    $questionsConfig = QuizQuestionsConfig::normalize($quiz->layout_config['questions'] ?? null);
    $answersConfig = QuizQuestionAnswersConfig::normalize(
        is_array($question->layout_config['answers'] ?? null) ? $question->layout_config['answers'] : null
    );
    $resolvedLayout = $answersConfig['layout'] ?? 'list';
    $customStyle = $answersConfig['use_quiz_defaults'] ? [] : $answersConfig;
    if (!in_array($question->question_type, ['radio', 'checkbox'], true)) {
        $showLayoutPanel = false;
    } else {
        $showLayoutPanel = true;
    }
@endphp

@if ($showLayoutPanel)
<div class="card mb-20">
    <div class="card-body">
        <h5 class="mb-2">{{ translate('Answer presentation') }}</h5>
        <p class="text-muted small mb-3">{{ translate('Choose list or grid layout and optionally override the quiz-wide default styling for this question.') }}</p>

        <form action="{{ route('theme.tlcommerce.quiz.question.answer.layout', $question->id) }}" method="POST" id="question-answer-layout-form">
            @csrf
            <input type="hidden" name="answer_layout_json" id="question-answer-layout-json" value='@json($answersConfig)'>

            <div class="form-row mb-15">
                <div class="col-md-4">
                    <label class="small">{{ translate('Layout') }}</label>
                    <select id="qa-q-layout" class="theme-input-style qa-q-input w-100">
                        <option value="list" {{ $resolvedLayout === 'list' ? 'selected' : '' }}>{{ translate('List') }}</option>
                        <option value="grid" {{ $resolvedLayout === 'grid' ? 'selected' : '' }}>{{ translate('Grid') }}</option>
                    </select>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <label class="mb-0">
                        <input type="checkbox" id="qa-q-use-defaults" class="qa-q-input" {{ ($answersConfig['use_quiz_defaults'] ?? true) ? 'checked' : '' }}>
                        {{ translate('Use quiz default styling') }}
                    </label>
                </div>
            </div>

            <div class="qa-q-custom-fields {{ ($answersConfig['use_quiz_defaults'] ?? true) ? 'is-disabled' : '' }}">
                <div id="qa-q-style-list" class="{{ $resolvedLayout === 'grid' ? 'd-none' : '' }}">
                    @include('theme/tlcommerce::backend.quiz.partials.answer_style_fields', [
                        'prefix' => 'qa-q-list',
                        'fieldClass' => 'qa-q-input',
                        'layout' => 'list',
                        'style' => $resolvedLayout === 'list' ? $customStyle : ($questionsConfig['answers_defaults']['list'] ?? []),
                    ])
                </div>
                <div id="qa-q-style-grid" class="{{ $resolvedLayout === 'list' ? 'd-none' : '' }}">
                    @include('theme/tlcommerce::backend.quiz.partials.answer_style_fields', [
                        'prefix' => 'qa-q-grid',
                        'fieldClass' => 'qa-q-input',
                        'layout' => 'grid',
                        'style' => $resolvedLayout === 'grid' ? $customStyle : ($questionsConfig['answers_defaults']['grid'] ?? []),
                    ])
                </div>
            </div>

            <div class="qa-q-preview-wrap mb-3 p-3 border rounded" id="qa-q-preview">
                <p class="small text-muted mb-2">{{ translate('Preview') }}</p>
                <div class="qa-q-preview-answers qa-q-preview-answers--list" id="qa-q-preview-answers">
                    <div class="qa-q-preview-item border">
                        <span class="qa-q-preview-thumb d-none"></span>
                        <strong>{{ translate('Option A') }}</strong>
                        <small class="d-block text-muted">{{ translate('Sample description') }}</small>
                    </div>
                    <div class="qa-q-preview-item border">
                        <span class="qa-q-preview-thumb d-none"></span>
                        <strong>{{ translate('Option B') }}</strong>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn long btn-orange">{{ translate('Save answer styling') }}</button>
        </form>
    </div>
</div>

<style>
    .qa-q-custom-fields.is-disabled { opacity: 0.5; pointer-events: none; }
    .qa-q-preview-wrap { background: #f9fafb; }
    .qa-q-preview-answers { display: flex; flex-direction: column; gap: 8px; }
    .qa-q-preview-answers--grid { display: grid; grid-template-columns: repeat(2, 1fr); }
    .qa-q-preview-item { padding: 12px; border-width: 1px; border-style: solid; }
    .qa-q-preview-thumb { display: block; width: 48px; height: 48px; background: #e5e7eb; border-radius: 6px; margin-bottom: 6px; }
    .qa-q-preview-answers--image-left .qa-q-preview-item { display: flex; align-items: center; gap: 8px; }
    .qa-q-preview-answers--image-left .qa-q-preview-thumb { margin-bottom: 0; }
</style>
@else
    <div class="alert alert-info mb-20">{{ translate('List and grid styling is available for radio and checkbox questions.') }}</div>
@endif

<script type="application/json" id="qa-quiz-defaults-json">@json($questionsConfig['answers_defaults'] ?? [])</script>
