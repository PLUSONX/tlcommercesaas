@php
    use Theme\TLCommerce\Http\Resources\QuizIntroConfig;
    $introTheme = old('intro_theme_json') ? json_decode(old('intro_theme_json'), true) : ($intro['theme'] ?? null);
    $introBlocks = old('intro_blocks_json') ? json_decode(old('intro_blocks_json'), true) : ($intro['blocks'] ?? null);
    $legacyForTheme = [
        'background_color' => old('intro_background_color', $intro['background_color'] ?? '#ffffff'),
        'text_color' => old('intro_text_color', $intro['text_color'] ?? '#111111'),
        'button_color' => old('intro_button_color', $intro['button_color'] ?? '#ff5a1f'),
        'button_text_color' => old('intro_button_text_color', $intro['button_text_color'] ?? '#ffffff'),
        'alignment' => old('intro_alignment', $intro['alignment'] ?? 'center'),
    ];
    $themeDefaults = QuizIntroConfig::defaultTheme($legacyForTheme);
    $theme = is_array($introTheme) ? array_merge($themeDefaults, $introTheme) : $themeDefaults;
    $blocksDefaults = QuizIntroConfig::defaultBlocks();
    $blocks = is_array($introBlocks) && count($introBlocks) ? $introBlocks : $blocksDefaults;
    $currentLayout = old('intro_layout', $intro['layout'] ?? 'minimal');
@endphp

<input type="hidden" name="intro_blocks_json" id="intro-blocks-json" value='@json($blocks)'>
<input type="hidden" name="intro_theme_json" id="intro-theme-json" value='@json($theme)'>

<div id="intro-custom-stack-panel" class="{{ $currentLayout === 'custom_stack' ? '' : 'd-none' }}">
    <hr class="my-4">
    <h5 class="mb-3">{{ translate('Intro styling') }}</h5>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Background type') }}</label></div>
        <div class="col-md-12">
            <select id="intro-theme-bg-type" class="theme-input-style intro-theme-input">
                <option value="solid" {{ ($theme['background_type'] ?? 'solid') === 'solid' ? 'selected' : '' }}>{{ translate('Solid color') }}</option>
                <option value="radial_gradient" {{ ($theme['background_type'] ?? '') === 'radial_gradient' ? 'selected' : '' }}>{{ translate('Radial gradient') }}</option>
            </select>
        </div>
    </div>

    <div class="form-row mb-20 intro-theme-solid-row {{ ($theme['background_type'] ?? 'solid') === 'solid' ? '' : 'd-none' }}">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Background color') }}</label></div>
        <div class="col-md-12">
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'intro-theme-bg-color',
                'value' => $theme['background_color'] ?? '#ffffff',
                'class' => 'intro-theme-input',
            ])
        </div>
    </div>

    <div class="intro-theme-gradient-rows {{ ($theme['background_type'] ?? 'solid') === 'radial_gradient' ? '' : 'd-none' }}">
        <div class="form-row mb-20">
            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Gradient center color') }}</label></div>
            <div class="col-md-12">
                @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                    'id' => 'intro-theme-bg-center',
                    'value' => $theme['background_gradient_center'] ?? '#1a3d2a',
                    'class' => 'intro-theme-input',
                ])
            </div>
        </div>
        <div class="form-row mb-20">
            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Gradient edge color') }}</label></div>
            <div class="col-md-12">
                @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                    'id' => 'intro-theme-bg-edge',
                    'value' => $theme['background_gradient_edge'] ?? '#050a07',
                    'class' => 'intro-theme-input',
                ])
            </div>
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Accent color') }}</label></div>
        <div class="col-md-12">
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'intro-theme-accent',
                'value' => $theme['accent_color'] ?? '#111111',
                'class' => 'intro-theme-input',
            ])
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Text color') }}</label></div>
        <div class="col-md-12">
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'intro-theme-text',
                'value' => $theme['text_color'] ?? '#111111',
                'class' => 'intro-theme-input',
            ])
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Hero image size') }} (px)</label></div>
        <div class="col-md-12">
            <input type="number" id="intro-theme-hero-size" class="theme-input-style intro-theme-input" min="80" max="320"
                value="{{ $theme['hero_size'] ?? 140 }}">
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Hero frame') }}</label></div>
        <div class="col-md-12">
            <select id="intro-theme-hero-frame" class="theme-input-style intro-theme-input">
                <option value="none" {{ ($theme['hero_frame'] ?? 'none') === 'none' ? 'selected' : '' }}>{{ translate('None') }}</option>
                <option value="corners" {{ ($theme['hero_frame'] ?? '') === 'corners' ? 'selected' : '' }}>{{ translate('Corner brackets') }}</option>
            </select>
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Hero glow') }}</label></div>
        <div class="col-md-12">
            <label class="switch glow primary medium">
                <input type="checkbox" id="intro-theme-hero-glow" class="intro-theme-input" value="1"
                    {{ !empty($theme['hero_glow']) ? 'checked' : '' }}>
                <span class="control"></span>
            </label>
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Tagline font') }}</label></div>
        <div class="col-md-12">
            <select id="intro-theme-tagline-font" class="theme-input-style intro-theme-input">
                <option value="default" {{ ($theme['tagline_font'] ?? 'default') === 'default' ? 'selected' : '' }}>{{ translate('Default') }}</option>
                <option value="serif_caps" {{ ($theme['tagline_font'] ?? '') === 'serif_caps' ? 'selected' : '' }}>{{ translate('Serif caps') }}</option>
            </select>
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Body font') }}</label></div>
        <div class="col-md-12">
            <select id="intro-theme-body-font" class="theme-input-style intro-theme-input">
                <option value="default" {{ ($theme['body_font'] ?? 'default') === 'default' ? 'selected' : '' }}>{{ translate('Default') }}</option>
                <option value="script" {{ ($theme['body_font'] ?? '') === 'script' ? 'selected' : '' }}>{{ translate('Script') }}</option>
            </select>
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Button style') }}</label></div>
        <div class="col-md-12">
            <select id="intro-theme-button-style" class="theme-input-style intro-theme-input">
                <option value="solid" {{ ($theme['button_style'] ?? 'solid') === 'solid' ? 'selected' : '' }}>{{ translate('Solid') }}</option>
                <option value="gradient_glow" {{ ($theme['button_style'] ?? '') === 'gradient_glow' ? 'selected' : '' }}>{{ translate('Gradient glow') }}</option>
            </select>
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Button color') }}</label></div>
        <div class="col-md-12">
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'intro-theme-btn-color',
                'value' => $theme['button_color'] ?? '#ff5a1f',
                'class' => 'intro-theme-input',
            ])
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Button text color') }}</label></div>
        <div class="col-md-12">
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'intro-theme-btn-text',
                'value' => $theme['button_text_color'] ?? '#ffffff',
                'class' => 'intro-theme-input',
            ])
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Min height') }} (px)</label></div>
        <div class="col-md-12">
            <input type="number" id="intro-theme-min-height" class="theme-input-style intro-theme-input" min="200" max="900"
                value="{{ $theme['min_height'] ?? 400 }}">
        </div>
    </div>

    <div class="form-row mb-20">
        <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Content max width') }} (px)</label></div>
        <div class="col-md-12">
            <input type="number" id="intro-theme-max-width" class="theme-input-style intro-theme-input" min="280" max="900"
                value="{{ $theme['content_max_width'] ?? 560 }}">
        </div>
    </div>

    <hr class="my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ translate('Content blocks') }}</h5>
        <button type="button" class="btn btn-sm btn-outline-dark" id="intro-reset-blocks-btn">{{ translate('Reset block order') }}</button>
    </div>
    <p class="text-muted small mb-3">{{ translate('Enable, reorder, and edit blocks. Use {count} in taglines for question count.') }}</p>

    <div id="intro-blocks-list" class="mb-3"></div>
</div>

<style>
    .intro-block-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        margin-bottom: 8px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fafafa;
    }
    .intro-block-row__main { flex: 1; min-width: 0; }
    .intro-block-row__type { font-weight: 600; font-size: 13px; }
    .intro-block-row__hint { font-size: 12px; color: #6b7280; margin-top: 4px; }
    .intro-block-row__actions { display: flex; flex-direction: column; gap: 4px; }
    .intro-block-row__actions button { padding: 2px 8px; font-size: 12px; }
    .intro-block-editor { width: 100%; min-height: 80px; }
    .intro-block-row .note-editor { margin-top: 8px; }
    .intro-block-row .note-editor .note-toolbar {
        flex-wrap: wrap;
        padding: 4px 6px 0;
    }
    .intro-block-row .note-editor .note-toolbar .note-btn-group {
        margin-bottom: 4px;
    }
    .intro-block-separator-panel .theme-input-style { max-width: 120px; font-size: 12px; }
    .intro-sep-row .small { color: #6b7280; }
    .intro-panel-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: 6px; }
    .intro-block-spacing-panel,
    .intro-block-appearance-panel { padding: 8px 10px; background: #f3f4f6; border-radius: 6px; border: 1px solid #e5e7eb; }
    .intro-mixture-part .btn { padding: 2px 6px; font-size: 11px; }
    .intro-sep-mixture-wrap { padding: 8px; background: #fff; border: 1px dashed #d1d5db; border-radius: 6px; }
</style>
