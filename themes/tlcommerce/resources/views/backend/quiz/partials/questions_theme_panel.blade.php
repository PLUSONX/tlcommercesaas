@php
    use Theme\TLCommerce\Http\Resources\QuizQuestionsConfig;
    $questionsThemeRaw = old('questions_theme_json') ? json_decode(old('questions_theme_json'), true) : (isset($quiz) ? ($quiz->layout_config['questions']['theme'] ?? null) : null);
    $qqTheme = array_merge(QuizQuestionsConfig::defaultTheme(), is_array($questionsThemeRaw) ? $questionsThemeRaw : []);
@endphp

<input type="hidden" name="questions_theme_json" id="questions-theme-json" value='@json($qqTheme)'>

<hr class="my-4">
<h5 class="mb-3">{{ translate('Questions appearance') }}</h5>
<p class="text-muted small mb-3">{{ translate('Style the question step page: background, card, progress bar, and buttons. Answer list/grid styling is managed on the Questions and Answers pages.') }}</p>

<div class="qq-theme-section mb-3">
    <div class="intro-panel-label">{{ translate('Page') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Background type') }}</label>
            <select id="qq-bg-type" class="theme-input-style qq-theme-input w-100">
                <option value="solid" {{ ($qqTheme['background_type'] ?? 'solid') === 'solid' ? 'selected' : '' }}>{{ translate('Solid') }}</option>
                <option value="radial_gradient" {{ ($qqTheme['background_type'] ?? '') === 'radial_gradient' ? 'selected' : '' }}>{{ translate('Radial gradient') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="small">{{ translate('Alignment') }}</label>
            <select id="qq-alignment" class="theme-input-style qq-theme-input w-100">
                <option value="left" {{ ($qqTheme['alignment'] ?? 'left') === 'left' ? 'selected' : '' }}>{{ translate('Left') }}</option>
                <option value="center" {{ ($qqTheme['alignment'] ?? '') === 'center' ? 'selected' : '' }}>{{ translate('Center') }}</option>
                <option value="right" {{ ($qqTheme['alignment'] ?? '') === 'right' ? 'selected' : '' }}>{{ translate('Right') }}</option>
            </select>
        </div>
    </div>
    <div class="form-row mb-15 qq-bg-solid-row {{ ($qqTheme['background_type'] ?? 'solid') === 'solid' ? '' : 'd-none' }}">
        <div class="col-md-12">
            <label class="small">{{ translate('Background color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-bg-color', 'value' => $qqTheme['background_color'] ?? '#f7f8fa', 'class' => 'qq-theme-input'])
        </div>
    </div>
    <div class="qq-bg-gradient-rows {{ ($qqTheme['background_type'] ?? 'solid') === 'radial_gradient' ? '' : 'd-none' }}">
        <div class="form-row mb-15">
            <div class="col-md-6">
                <label class="small">{{ translate('Gradient center') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-bg-center', 'value' => $qqTheme['background_gradient_center'] ?? '#ffffff', 'class' => 'qq-theme-input'])
            </div>
            <div class="col-md-6">
                <label class="small">{{ translate('Gradient edge') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-bg-edge', 'value' => $qqTheme['background_gradient_edge'] ?? '#050a07', 'class' => 'qq-theme-input'])
            </div>
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Content max width') }} (px)</label>
            <input type="number" id="qq-max-width" class="theme-input-style qq-theme-input w-100" min="400" max="900" value="{{ $qqTheme['content_max_width'] ?? 640 }}">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <label class="mb-0">
                <input type="checkbox" id="qq-fill-viewport" class="qq-theme-input" {{ !empty($qqTheme['fill_viewport']) ? 'checked' : '' }}>
                {{ translate('Fill viewport height') }}
            </label>
        </div>
    </div>
</div>

<div class="qq-theme-section mb-3">
    <div class="intro-panel-label">{{ translate('Question card') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-12">
            <label class="mb-0">
                <input type="checkbox" id="qq-card-enabled" class="qq-theme-input" {{ ($qqTheme['card_enabled'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show question card') }}
            </label>
            <small class="text-muted d-block mt-1">{{ translate('When off, questions appear directly on the page background without a card wrapper.') }}</small>
        </div>
    </div>
    <div class="qq-card-fields">
        <div class="form-row mb-15">
            <div class="col-md-6">
                <label class="small">{{ translate('Background') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-card-bg', 'value' => $qqTheme['card_background'] ?? '#ffffff', 'class' => 'qq-theme-input'])
            </div>
            <div class="col-md-6">
                <label class="small">{{ translate('Border color') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-card-border', 'value' => $qqTheme['card_border_color'] ?? '#e5e7eb', 'class' => 'qq-theme-input'])
            </div>
        </div>
        <div class="form-row mb-15">
            <div class="col-md-4">
                <label class="small">{{ translate('Border radius') }} (px)</label>
                <input type="number" id="qq-card-radius" class="theme-input-style qq-theme-input w-100" min="0" max="32" value="{{ $qqTheme['card_border_radius'] ?? 12 }}">
            </div>
            <div class="col-md-4">
                <label class="small">{{ translate('Padding') }} (px)</label>
                <input type="number" id="qq-card-padding" class="theme-input-style qq-theme-input w-100" min="0" max="64" value="{{ $qqTheme['card_padding'] ?? 24 }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <label class="mb-0">
                    <input type="checkbox" id="qq-card-shadow" class="qq-theme-input" {{ !empty($qqTheme['card_shadow']) ? 'checked' : '' }}>
                    {{ translate('Drop shadow') }}
                </label>
            </div>
        </div>
    </div>
</div>

<div class="qq-theme-section mb-3">
    <div class="intro-panel-label">{{ translate('Question text') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Text color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-question-color', 'value' => $qqTheme['question_color'] ?? '#111111', 'class' => 'qq-theme-input'])
        </div>
        <div class="col-md-6">
            <label class="small">{{ translate('Required marker') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-required-color', 'value' => $qqTheme['required_color'] ?? '#dc3545', 'class' => 'qq-theme-input'])
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Background') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-question-bg', 'value' => $qqTheme['question_background'] ?? 'transparent', 'class' => 'qq-theme-input'])
            <small class="text-muted">{{ translate('Use transparent for no fill') }}</small>
        </div>
        <div class="col-md-6">
            <label class="small">{{ translate('Question text alignment') }}</label>
            <select id="qq-question-align" class="theme-input-style qq-theme-input w-100">
                <option value="" {{ ($qqTheme['question_alignment'] ?? '') === '' ? 'selected' : '' }}>{{ translate('Use page alignment') }}</option>
                <option value="left" {{ ($qqTheme['question_alignment'] ?? '') === 'left' ? 'selected' : '' }}>{{ translate('Left') }}</option>
                <option value="center" {{ ($qqTheme['question_alignment'] ?? '') === 'center' ? 'selected' : '' }}>{{ translate('Center') }}</option>
                <option value="right" {{ ($qqTheme['question_alignment'] ?? '') === 'right' ? 'selected' : '' }}>{{ translate('Right') }}</option>
            </select>
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Padding') }} (px)</label>
            <input type="number" id="qq-question-padding" class="theme-input-style qq-theme-input w-100" min="0" max="64" value="{{ $qqTheme['question_padding'] ?? 0 }}">
        </div>
        <div class="col-md-6">
            <label class="small">{{ translate('Margin') }} (px)</label>
            <input type="number" id="qq-question-margin" class="theme-input-style qq-theme-input w-100" min="0" max="64" value="{{ $qqTheme['question_margin'] ?? 16 }}">
        </div>
    </div>
</div>

<div class="qq-theme-section mb-3">
    <div class="intro-panel-label">{{ translate('Progress bar') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-12">
            <label class="mb-0">
                <input type="checkbox" id="qq-show-progress" class="qq-theme-input" {{ ($qqTheme['show_progress'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show progress bar') }}
            </label>
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Track color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-progress-track', 'value' => $qqTheme['progress_track_color'] ?? '#e9ecef', 'class' => 'qq-theme-input'])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Bar color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-progress-bar', 'value' => $qqTheme['progress_bar_color'] ?? '#ff5a1f', 'class' => 'qq-theme-input'])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Label color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-progress-label', 'value' => $qqTheme['progress_label_color'] ?? '#6b7280', 'class' => 'qq-theme-input'])
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Counter background') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-progress-label-bg', 'value' => $qqTheme['progress_label_background'] ?? 'transparent', 'class' => 'qq-theme-input'])
            <small class="text-muted">{{ translate('Use transparent for no fill') }}</small>
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Counter alignment') }}</label>
            <select id="qq-progress-label-align" class="theme-input-style qq-theme-input w-100">
                <option value="left" {{ ($qqTheme['progress_label_alignment'] ?? 'left') === 'left' ? 'selected' : '' }}>{{ translate('Left') }}</option>
                <option value="center" {{ ($qqTheme['progress_label_alignment'] ?? '') === 'center' ? 'selected' : '' }}>{{ translate('Center') }}</option>
                <option value="right" {{ ($qqTheme['progress_label_alignment'] ?? '') === 'right' ? 'selected' : '' }}>{{ translate('Right') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Height') }} (px)</label>
            <input type="number" id="qq-progress-height" class="theme-input-style qq-theme-input w-100" min="4" max="16" value="{{ $qqTheme['progress_height'] ?? 6 }}">
        </div>
    </div>
</div>

<div class="qq-theme-section mb-3">
    <div class="intro-panel-label">{{ translate('Navigation buttons') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="mb-0">
                <input type="checkbox" id="qq-show-back-btn" class="qq-theme-input" {{ ($qqTheme['show_back_button'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show Back button') }}
            </label>
        </div>
        <div class="col-md-6">
            <label class="mb-0">
                <input type="checkbox" id="qq-show-next-btn" class="qq-theme-input" {{ ($qqTheme['show_next_button'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show Next button') }}
            </label>
            <small class="text-muted d-block mt-1">{{ translate('When off, advancing happens automatically after an answer is selected.') }}</small>
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Back label') }}</label>
            <input type="text" id="qq-btn-back-label" class="theme-input-style qq-theme-input w-100" maxlength="40" value="{{ $qqTheme['btn_back_label'] ?? translate('Back') }}">
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Next label') }}</label>
            <input type="text" id="qq-btn-next-label" class="theme-input-style qq-theme-input w-100" maxlength="40" value="{{ $qqTheme['btn_next_label'] ?? translate('Next') }}">
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Submit label') }}</label>
            <input type="text" id="qq-btn-submit-label" class="theme-input-style qq-theme-input w-100" maxlength="40" value="{{ $qqTheme['btn_submit_label'] ?? translate('See Results') }}">
        </div>
    </div>
    <div class="intro-panel-label small mb-2">{{ translate('Button styling') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Back border color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-btn-back', 'value' => $qqTheme['btn_back_color'] ?? '#111111', 'class' => 'qq-theme-input'])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Back text color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-btn-back-text', 'value' => $qqTheme['btn_back_text_color'] ?? '#111111', 'class' => 'qq-theme-input'])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Next background') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-btn-next', 'value' => $qqTheme['btn_next_color'] ?? '#ff5a1f', 'class' => 'qq-theme-input'])
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Next text color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-btn-next-text', 'value' => $qqTheme['btn_next_text_color'] ?? '#ffffff', 'class' => 'qq-theme-input'])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Button radius') }} (px)</label>
            <input type="number" id="qq-btn-radius" class="theme-input-style qq-theme-input w-100" min="0" max="32" value="{{ $qqTheme['btn_border_radius'] ?? 4 }}">
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Error message color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'qq-error-color', 'value' => $qqTheme['error_color'] ?? '#dc3545', 'class' => 'qq-theme-input'])
        </div>
    </div>
</div>

<style>
    .qq-theme-section { padding: 10px 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; }
    .qq-theme-section .mb-15 { margin-bottom: 12px; }
    .qq-card-fields.is-disabled { opacity: 0.5; pointer-events: none; }
</style>
