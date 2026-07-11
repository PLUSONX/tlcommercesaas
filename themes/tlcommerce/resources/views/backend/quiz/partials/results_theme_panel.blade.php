@php
    use Theme\TLCommerce\Http\Resources\QuizResultsConfig;
    $isDefaultLang = $isDefaultLang ?? true;
    $resultsRaw = isset($layoutConfigForForm)
        ? ($layoutConfigForForm['results'] ?? null)
        : (isset($quiz) ? ($quiz->layout_config['results'] ?? null) : null);
    $hasResults = is_array($resultsRaw);
    $resultsThemeRaw = old('results_theme_json') ? json_decode(old('results_theme_json'), true) : ($hasResults ? ($resultsRaw['theme'] ?? null) : null);
    $resultsTheme = array_merge(QuizResultsConfig::defaultTheme(), is_array($resultsThemeRaw) ? $resultsThemeRaw : []);
    $layoutMode = old('results_layout_mode', $hasResults ? ($resultsRaw['layout_mode'] ?? 'featured_card') : 'featured_card');
    $showProductTextStyle = $layoutMode === 'product_color_card';
    $productGridRaw = old('results_product_grid_json') ? json_decode(old('results_product_grid_json'), true) : ($hasResults ? ($resultsRaw['product_grid'] ?? null) : null);
    $productGrid = array_merge(QuizResultsConfig::defaultProductGrid(), is_array($productGridRaw) ? $productGridRaw : []);
@endphp

<input type="hidden" name="results_theme_json" id="results-theme-json" value='@json($resultsTheme)'>
<input type="hidden" name="results_product_grid_json" id="results-product-grid-json" value='@json($productGrid)'>

<hr class="my-4">
<h5 class="mb-3">{{ translate('Results page') }}</h5>
<p class="text-muted small mb-3">{{ translate('Customize the quiz results step: featured result card, optional product grid, and action buttons.') }}</p>

<div @if (!$isDefaultLang) class="area-disabled" @endif>
<div class="form-row mb-20">
    <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Layout mode') }}</label></div>
    <div class="col-md-12">
        <select name="results_layout_mode" id="results-layout-mode" class="theme-input-style results-preview-input">
            <option value="featured_card" {{ $layoutMode === 'featured_card' ? 'selected' : '' }}>{{ translate('Featured result card only') }}</option>
            <option value="product_color_card" {{ $layoutMode === 'product_color_card' ? 'selected' : '' }}>{{ translate('Product color card') }}</option>
            <option value="product_grid" {{ $layoutMode === 'product_grid' ? 'selected' : '' }}>{{ translate('Product grid only') }}</option>
            <option value="featured_and_grid" {{ $layoutMode === 'featured_and_grid' ? 'selected' : '' }}>{{ translate('Featured card + product grid') }}</option>
        </select>
    </div>
</div>
</div>

@include('theme/tlcommerce::backend.quiz.partials.results_product_profiles', [
    'isDefaultLang' => $isDefaultLang ?? true,
])

<div @if (!$isDefaultLang) class="area-disabled" @endif>

<div id="results-product-text-style-section" class="results-theme-section mb-3 {{ $showProductTextStyle ? '' : 'd-none' }}">
    <div class="intro-panel-label">{{ translate('Product text styling') }}</div>
    <p class="text-muted small mb-3">{{ translate('Typography for tagline and description blocks on the product color card.') }}</p>

    <div class="form-row mb-15">
        <div class="col-12"><strong class="small">{{ translate('Tagline') }}</strong></div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-3">
            <label class="small">{{ translate('Font') }}</label>
            <select id="results-tagline-font" class="theme-input-style results-theme-input w-100">
                <option value="default" {{ ($resultsTheme['tagline_font'] ?? 'default') === 'default' ? 'selected' : '' }}>{{ translate('Default') }}</option>
                <option value="serif_caps" {{ ($resultsTheme['tagline_font'] ?? '') === 'serif_caps' ? 'selected' : '' }}>{{ translate('Serif caps') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="small">{{ translate('Color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'results-tagline-color',
                'value' => $resultsTheme['tagline_color'] ?? '#ffffff',
                'class' => 'results-theme-input',
            ])
        </div>
        <div class="col-md-3">
            <label class="small">{{ translate('Font size') }} (px)</label>
            <input type="number" id="results-tagline-font-size" class="theme-input-style results-theme-input w-100" min="8" max="32"
                value="{{ $resultsTheme['tagline_font_size'] ?? 11 }}">
        </div>
        <div class="col-md-3">
            <label class="small">{{ translate('Letter spacing') }} (em)</label>
            <input type="number" id="results-tagline-letter-spacing" class="theme-input-style results-theme-input w-100" min="0" max="0.5" step="0.01"
                value="{{ $resultsTheme['tagline_letter_spacing'] ?? 0.15 }}">
        </div>
    </div>

    <div class="form-row mb-15">
        <div class="col-12"><strong class="small">{{ translate('Description') }}</strong></div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Font') }}</label>
            <select id="results-description-font" class="theme-input-style results-theme-input w-100">
                <option value="default" {{ ($resultsTheme['body_font'] ?? 'default') === 'default' ? 'selected' : '' }}>{{ translate('Default') }}</option>
                <option value="script" {{ ($resultsTheme['body_font'] ?? '') === 'script' ? 'selected' : '' }}>{{ translate('Script') }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => 'results-description-color',
                'value' => $resultsTheme['description_color'] ?? '#ffffff',
                'class' => 'results-theme-input',
            ])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Font size') }} (px)</label>
            <input type="number" id="results-description-font-size" class="theme-input-style results-theme-input w-100" min="10" max="32"
                value="{{ $resultsTheme['description_font_size'] ?? 15 }}">
        </div>
    </div>
</div>

<div class="results-theme-section mb-3">
    <div class="intro-panel-label">{{ translate('Page') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Background type') }}</label>
            <select id="results-bg-type" class="theme-input-style results-theme-input w-100">
                <option value="solid" {{ ($resultsTheme['background_type'] ?? 'solid') === 'solid' ? 'selected' : '' }}>{{ translate('Solid') }}</option>
                <option value="radial_gradient" {{ ($resultsTheme['background_type'] ?? '') === 'radial_gradient' ? 'selected' : '' }}>{{ translate('Radial gradient') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="small">{{ translate('Alignment') }}</label>
            <select id="results-alignment" class="theme-input-style results-theme-input w-100">
                @foreach (['left', 'center', 'right'] as $align)
                    <option value="{{ $align }}" {{ ($resultsTheme['alignment'] ?? 'center') === $align ? 'selected' : '' }}>{{ ucfirst($align) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row mb-15 results-bg-solid-row {{ ($resultsTheme['background_type'] ?? 'solid') === 'solid' ? '' : 'd-none' }}">
        <div class="col-md-12">
            <label class="small">{{ translate('Background color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-bg-color', 'value' => $resultsTheme['background_color'] ?? '#1a3d2a', 'class' => 'results-theme-input'])
        </div>
    </div>
    <div class="results-bg-gradient-rows {{ ($resultsTheme['background_type'] ?? 'solid') === 'radial_gradient' ? '' : 'd-none' }}">
        <div class="form-row mb-15">
            <div class="col-md-6">
                <label class="small">{{ translate('Gradient center') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-bg-center', 'value' => $resultsTheme['background_gradient_center'] ?? '#1a3d2a', 'class' => 'results-theme-input'])
            </div>
            <div class="col-md-6">
                <label class="small">{{ translate('Gradient edge') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-bg-edge', 'value' => $resultsTheme['background_gradient_edge'] ?? '#050a07', 'class' => 'results-theme-input'])
            </div>
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Text color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-text-color', 'value' => $resultsTheme['text_color'] ?? '#ffffff', 'class' => 'results-theme-input'])
        </div>
        <div class="col-md-4">
            <label class="small">{{ translate('Accent color') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-accent-color', 'value' => $resultsTheme['accent_color'] ?? '#c9a84c', 'class' => 'results-theme-input'])
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <label class="mb-0">
                <input type="checkbox" id="results-fill-viewport" class="results-theme-input" {{ !empty($resultsTheme['fill_viewport']) ? 'checked' : '' }}>
                {{ translate('Fill viewport') }}
            </label>
        </div>
    </div>
</div>

<div class="results-theme-section mb-3" id="results-card-theme-section">
    <div class="intro-panel-label">{{ translate('Result card') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-12">
            <label class="mb-0">
                <input type="checkbox" id="results-card-enabled" class="results-theme-input" {{ ($resultsTheme['card_enabled'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show result card wrapper') }}
            </label>
        </div>
    </div>
    <div class="results-card-fields">
        <div class="form-row mb-15">
            <div class="col-md-4">
                <label class="small">{{ translate('Card background') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-card-bg', 'value' => $resultsTheme['card_background'] ?? '#1f4530', 'class' => 'results-theme-input'])
            </div>
            <div class="col-md-4">
                <label class="small">{{ translate('Border style') }}</label>
                <select id="results-card-border-style" class="theme-input-style results-theme-input w-100">
                    @foreach (['none' => 'None', 'single' => 'Single', 'double' => 'Double'] as $val => $label)
                        <option value="{{ $val }}" {{ ($resultsTheme['card_border_style'] ?? 'double') === $val ? 'selected' : '' }}>{{ translate($label) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="small">{{ translate('Border color') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-card-border-color', 'value' => $resultsTheme['card_border_color'] ?? '#c9a84c', 'class' => 'results-theme-input'])
            </div>
        </div>
        <div class="form-row mb-15">
            <div class="col-md-4">
                <label class="small">{{ translate('Card padding') }} (px)</label>
                <input type="number" id="results-card-padding" class="theme-input-style results-theme-input w-100" min="0" max="64" value="{{ $resultsTheme['card_padding'] ?? 32 }}">
            </div>
            <div class="col-md-4">
                <label class="small">{{ translate('Card max width') }} (px)</label>
                <input type="number" id="results-card-max-width" class="theme-input-style results-theme-input w-100" min="280" max="900" value="{{ $resultsTheme['card_max_width'] ?? 480 }}">
            </div>
            <div class="col-md-4">
                <label class="small">{{ translate('Hero / icon size') }} (px)</label>
                <input type="number" id="results-hero-size" class="theme-input-style results-theme-input w-100" min="40" max="320" value="{{ $resultsTheme['hero_size'] ?? 80 }}">
            </div>
        </div>
        <div class="form-row mb-15">
            <div class="col-12">
                <strong class="small">{{ translate('Card frame') }}</strong>
            </div>
        </div>
        <div class="form-row mb-15">
            <div class="col-md-4">
                <label class="small">{{ translate('Frame style') }}</label>
                <select id="results-hero-frame" class="theme-input-style results-theme-input w-100">
                    <option value="none" {{ ($resultsTheme['hero_frame'] ?? 'none') === 'none' ? 'selected' : '' }}>{{ translate('None') }}</option>
                    <option value="corners" {{ ($resultsTheme['hero_frame'] ?? '') === 'corners' ? 'selected' : '' }}>{{ translate('Corner brackets') }}</option>
                    <option value="inset" {{ ($resultsTheme['hero_frame'] ?? '') === 'inset' ? 'selected' : '' }}>{{ translate('Inset border') }}</option>
                </select>
            </div>
            <div class="col-md-4 results-hero-frame-controls {{ ($resultsTheme['hero_frame'] ?? 'none') === 'none' ? 'd-none' : '' }}">
                <label class="small">{{ translate('Frame color') }}</label>
                @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-hero-frame-color', 'value' => $resultsTheme['hero_frame_color'] ?? '#c9a84c', 'class' => 'results-theme-input'])
            </div>
            <div class="col-md-4 d-flex align-items-end results-hero-frame-controls {{ ($resultsTheme['hero_frame'] ?? 'none') === 'none' ? 'd-none' : '' }}">
                <label class="mb-0">
                    <input type="checkbox" id="results-hero-glow" class="results-theme-input" {{ !empty($resultsTheme['hero_glow']) ? 'checked' : '' }}>
                    {{ translate('Glow') }}
                </label>
            </div>
        </div>
        <div class="form-row mb-15 results-hero-frame-controls {{ ($resultsTheme['hero_frame'] ?? 'none') === 'none' ? 'd-none' : '' }}">
            <div class="col-md-4">
                <label class="small">{{ translate('Thickness') }} (px)</label>
                <input type="number" id="results-hero-frame-thickness" class="theme-input-style results-theme-input w-100" min="1" max="6"
                    value="{{ $resultsTheme['hero_frame_thickness'] ?? 2 }}">
            </div>
            <div class="col-md-4">
                <label class="small">{{ translate('Inset') }} (px)</label>
                <input type="number" id="results-hero-frame-inset" class="theme-input-style results-theme-input w-100" min="4" max="32"
                    value="{{ $resultsTheme['hero_frame_inset'] ?? 10 }}">
            </div>
            <div class="col-md-4 results-hero-frame-corner-controls {{ ($resultsTheme['hero_frame'] ?? 'none') !== 'corners' ? 'd-none' : '' }}">
                <label class="small">{{ translate('Bracket size') }} (px)</label>
                <input type="number" id="results-hero-frame-corner-size" class="theme-input-style results-theme-input w-100" min="8" max="64"
                    value="{{ $resultsTheme['hero_frame_corner_size'] ?? 18 }}">
            </div>
        </div>
    </div>
</div>

<div class="results-theme-section mb-3" id="results-product-grid-section">
    <div class="intro-panel-label">{{ translate('Product grid') }}</div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="mb-0">
                <input type="checkbox" id="results-grid-show-heading" class="results-grid-input" {{ ($productGrid['show_heading'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show heading') }}
            </label>
        </div>
        <div class="col-md-6">
            <label class="mb-0">
                <input type="checkbox" id="results-grid-show-badge" class="results-grid-input" {{ ($productGrid['show_match_badge'] ?? true) ? 'checked' : '' }}>
                {{ translate('Show match badge') }}
            </label>
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-12">
            <label class="small">{{ translate('Heading') }}</label>
            <input type="text" id="results-grid-heading" class="theme-input-style results-grid-input w-100" value="{{ $productGrid['heading'] ?? 'Your recommendations' }}">
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-12">
            <label class="small">{{ translate('Subheading') }}</label>
            <input type="text" id="results-grid-subheading" class="theme-input-style results-grid-input w-100" value="{{ $productGrid['subheading'] ?? '' }}">
        </div>
    </div>
    <div class="form-row mb-15">
        <div class="col-md-6">
            <label class="small">{{ translate('Match badge background') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-badge-bg', 'value' => $productGrid['match_badge_bg'] ?? '#ff5a1f', 'class' => 'results-grid-input'])
        </div>
        <div class="col-md-6">
            <label class="small">{{ translate('Match badge text') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', ['id' => 'results-badge-text', 'value' => $productGrid['match_badge_text'] ?? '#ffffff', 'class' => 'results-grid-input'])
        </div>
    </div>
</div>

</div>

@include('theme/tlcommerce::backend.quiz.partials.results_block_builder', [
    'isDefaultLang' => $isDefaultLang,
])
@include('theme/tlcommerce::backend.quiz.partials.results_actions_builder', [
    'isDefaultLang' => $isDefaultLang,
])
