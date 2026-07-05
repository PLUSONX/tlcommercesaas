@php
    $intro = isset($quiz) ? ($quiz->layout_config['intro'] ?? []) : [];
    $introLayout = old('intro_layout', $intro['layout'] ?? 'minimal');
    $introAlignment = old('intro_alignment', $intro['alignment'] ?? 'center');
    $introSubtitle = old('intro_subtitle', $intro['subtitle'] ?? '');
    $introCta = old('intro_cta_text', $intro['cta_text'] ?? '');
    $introSecondaryText = old('intro_secondary_link_text', $intro['secondary_link_text'] ?? '');
    $introSecondaryUrl = old('intro_secondary_link_url', $intro['secondary_link_url'] ?? '');
    $introHeroDesktop = old('intro_hero_image_desktop', $intro['hero_image_desktop'] ?? '');
    $introBg = old('intro_background_color', $intro['background_color'] ?? '#ffffff');
    $introText = old('intro_text_color', $intro['text_color'] ?? '#111111');
    $introBtn = old('intro_button_color', $intro['button_color'] ?? '#ff5a1f');
    $introBtnText = old('intro_button_text_color', $intro['button_text_color'] ?? '#ffffff');
    $introOverlay = old('intro_overlay_color', $intro['overlay_color'] ?? '#000000');
    $introOverlayOpacity = old('intro_overlay_opacity', $intro['overlay_opacity'] ?? 40);
    $previewQuestionCount = isset($quiz) ? $quiz->questions->count() : 0;
    $previewHeroDesktop = $introHeroDesktop ? preg_replace('#^/public#', '', getFilePath($introHeroDesktop, false) ?: '') : '';
    $showSquareTop = $introLayout === 'hero_square_top' && $previewHeroDesktop;
    $showSquareBottom = $introLayout === 'hero_square_bottom' && $previewHeroDesktop;
    $isCustomStack = $introLayout === 'custom_stack';
@endphp

<div class="card mb-3" id="quiz-preview-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">{{ translate('Preview') }}</h5>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary active" id="preview-tab-intro">{{ translate('Start page') }}</button>
                <button type="button" class="btn btn-outline-secondary" id="preview-tab-questions">{{ translate('Questions') }}</button>
                <button type="button" class="btn btn-outline-secondary" id="preview-tab-results">{{ translate('Results') }}</button>
            </div>
        </div>

        <div id="preview-intro-panel">
        <h6 class="text-muted small mb-2">{{ translate('Start Page Preview') }}</h6>

        <div id="preview-legacy-wrap" class="{{ $isCustomStack ? 'd-none' : '' }}">
            <div
                id="quiz-intro-preview"
                class="quiz-admin-preview quiz-admin-preview--{{ $introLayout }} text-{{ $introAlignment }}"
                data-layout="{{ $introLayout }}"
                style="--quiz-bg: {{ $introBg }}; --quiz-text: {{ $introText }}; --quiz-btn: {{ $introBtn }}; --quiz-btn-text: {{ $introBtnText }}; --quiz-overlay: {{ $introOverlay }}; --quiz-overlay-opacity: {{ ((int) $introOverlayOpacity) / 100 }};"
            >
                <div class="quiz-admin-preview__hero" id="preview-hero-wrap" style="{{ $previewHeroDesktop ? 'background-image: url(' . $previewHeroDesktop . ')' : '' }}">
                    <div class="quiz-admin-preview__overlay"></div>
                    <div class="quiz-admin-preview__square-image quiz-admin-preview__square-image--top" id="preview-square-top" style="{{ $showSquareTop ? '' : 'display:none' }}">
                        <img id="preview-square-img-top" src="{{ $showSquareTop ? $previewHeroDesktop : '' }}" alt="">
                    </div>
                    <div class="quiz-admin-preview__content text-{{ $introAlignment }}" id="preview-content">
                        <h4 id="preview-title">{{ old('title', isset($quiz) ? $quiz->title : translate('Quiz Title')) }}</h4>
                        <p class="preview-subtitle mb-2" id="preview-subtitle" style="{{ $introSubtitle ? '' : 'display:none' }}">{{ $introSubtitle }}</p>
                        <div class="preview-description mb-3" id="preview-description">{!! old('description', isset($quiz) ? $quiz->description : '') !!}</div>
                        <p class="preview-meta small mb-3" id="preview-meta"></p>
                        <button type="button" class="btn btn-sm preview-cta" id="preview-cta">{{ $introCta ?: translate('Start Quiz') }}</button>
                        <div class="mt-2" id="preview-secondary-wrap" style="{{ $introSecondaryText && $introSecondaryUrl ? '' : 'display:none' }}">
                            <a href="#" class="preview-secondary" id="preview-secondary">{{ $introSecondaryText }}</a>
                        </div>
                    </div>
                    <div class="quiz-admin-preview__square-image quiz-admin-preview__square-image--bottom" id="preview-square-bottom" style="{{ $showSquareBottom ? '' : 'display:none' }}">
                        <img id="preview-square-img-bottom" src="{{ $showSquareBottom ? $previewHeroDesktop : '' }}" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div id="preview-stack-wrap" class="{{ $isCustomStack ? '' : 'd-none' }}">
            <div id="quiz-stack-preview" class="quiz-stack-preview text-{{ $introAlignment }}"></div>
        </div>
        </div>

        <div id="preview-questions-panel" class="d-none">
            <div id="quiz-questions-preview" class="qq-admin-preview">
                <div class="qq-preview-inner mx-auto">
                    <div class="qq-preview-card border">
                        <div class="qq-preview-progress-wrap mb-3">
                            <small class="qq-preview-progress-label d-block mb-1">{{ translate('Question') }} 1 / 3</small>
                            <div class="qq-preview-progress-track rounded overflow-hidden">
                                <div class="qq-preview-progress-bar h-100"></div>
                            </div>
                        </div>
                        <div class="qq-preview-question-wrap">
                            <h6 class="qq-preview-question mb-0">{{ translate('Sample question text') }} <span class="qq-preview-required">*</span></h6>
                        </div>
                        <p class="small text-muted mb-3">{{ translate('Answer styling is configured on the Questions and Answers pages.') }}</p>
                        <div class="d-flex justify-content-between gap-2">
                            <button type="button" class="btn btn-sm btn-outline-dark qq-preview-btn-back">{{ translate('Back') }}</button>
                            <button type="button" class="btn btn-sm qq-preview-btn-next">{{ translate('Next') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="preview-results-panel" class="d-none">
            <h6 class="text-muted small mb-2">{{ translate('Results Page Preview') }}</h6>
            <div id="quiz-results-preview"></div>
        </div>

        <small class="text-muted d-block mt-2">{{ translate('Preview updates as you edit the form') }}</small>
    </div>
</div>

<style>
    .quiz-admin-preview {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: var(--quiz-bg, #fff);
        color: var(--quiz-text, #111);
    }
    .quiz-admin-preview__hero {
        position: relative;
        min-height: 220px;
        background-size: cover;
        background-position: center;
        background-color: var(--quiz-bg, #fff);
    }
    .quiz-admin-preview--minimal .quiz-admin-preview__hero {
        min-height: auto;
        background-image: none !important;
    }
    .quiz-admin-preview--hero_image_left .quiz-admin-preview__hero {
        display: flex;
        flex-direction: row;
        min-height: 200px;
    }
    .quiz-admin-preview--hero_image_left .quiz-admin-preview__hero::before {
        content: '';
        flex: 0 0 40%;
        background-size: cover;
        background-position: center;
        background-color: #eee;
    }
    .quiz-admin-preview--hero_image_left .quiz-admin-preview__content {
        flex: 1;
        position: relative;
        z-index: 1;
    }
    .quiz-admin-preview--hero_square_top .quiz-admin-preview__hero,
    .quiz-admin-preview--hero_square_bottom .quiz-admin-preview__hero {
        display: flex;
        flex-direction: column;
        min-height: auto;
        padding: 20px;
        background-image: none !important;
    }
    .quiz-admin-preview--hero_square_top.text-left .quiz-admin-preview__hero,
    .quiz-admin-preview--hero_square_bottom.text-left .quiz-admin-preview__hero { align-items: flex-start; }
    .quiz-admin-preview--hero_square_top.text-center .quiz-admin-preview__hero,
    .quiz-admin-preview--hero_square_bottom.text-center .quiz-admin-preview__hero { align-items: center; }
    .quiz-admin-preview--hero_square_top.text-right .quiz-admin-preview__hero,
    .quiz-admin-preview--hero_square_bottom.text-right .quiz-admin-preview__hero { align-items: flex-end; }
    .quiz-admin-preview--hero_square_top .quiz-admin-preview__content,
    .quiz-admin-preview--hero_square_bottom .quiz-admin-preview__content { padding: 0; width: 100%; }
    .quiz-admin-preview__square-image {
        width: 140px;
        height: 140px;
        flex-shrink: 0;
        border-radius: 12px;
        overflow: hidden;
    }
    .quiz-admin-preview__square-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .quiz-admin-preview__square-image--top { margin-bottom: 16px; }
    .quiz-admin-preview__square-image--bottom { margin-top: 16px; }
    .quiz-admin-preview--full_bleed .quiz-admin-preview__overlay { display: block; }
    .quiz-admin-preview__overlay {
        display: none;
        position: absolute;
        inset: 0;
        background: var(--quiz-overlay, #000);
        opacity: var(--quiz-overlay-opacity, 0.4);
    }
    .quiz-admin-preview--hero_centered .quiz-admin-preview__content,
    .quiz-admin-preview--full_bleed .quiz-admin-preview__content {
        position: relative;
        z-index: 2;
        padding: 24px;
        max-width: 520px;
        margin: 0 auto;
    }
    .quiz-admin-preview--hero_centered .quiz-admin-preview__hero,
    .quiz-admin-preview--full_bleed .quiz-admin-preview__hero {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 260px;
    }
    .quiz-admin-preview__content { padding: 20px; }
    .quiz-admin-preview .preview-cta {
        background: var(--quiz-btn, #ff5a1f);
        color: var(--quiz-btn-text, #fff);
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
    }
    .quiz-admin-preview .preview-secondary {
        color: var(--quiz-text, #111);
        text-decoration: underline;
        font-size: 13px;
    }

    .quiz-stack-preview {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        min-height: 280px;
    }
    .quiz-stack-preview__inner {
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    .quiz-stack-preview.text-center .quiz-stack-preview__inner { align-items: center; }
    .quiz-stack-preview.text-left .quiz-stack-preview__inner { align-items: flex-start; }
    .quiz-stack-preview.text-right .quiz-stack-preview__inner { align-items: flex-end; }
    .quiz-stack-preview__hero {
        margin-bottom: 16px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .quiz-stack-preview__hero img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .quiz-stack-preview__hero--glow { filter: drop-shadow(0 0 10px rgba(201, 168, 76, 0.5)); }
    .quiz-stack-preview__hero-frame {
        width: 100%;
        height: 100%;
        padding: 6px;
        box-sizing: border-box;
        position: relative;
    }
    .quiz-stack-preview__hero-frame::before,
    .quiz-stack-preview__hero-frame::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border-color: var(--stack-accent, #c9a84c);
        border-style: solid;
    }
    .quiz-stack-preview__hero-frame::before { top: 0; left: 0; border-width: 2px 0 0 2px; }
    .quiz-stack-preview__hero-frame::after { bottom: 0; right: 0; border-width: 0 2px 2px 0; }
    .quiz-stack-preview__title { font-size: 1.25rem; font-weight: 600; margin-bottom: 8px; width: 100%; }
    .quiz-stack-preview__subtitle { font-size: 1rem; margin-bottom: 8px; width: 100%; opacity: 0.9; }
    .quiz-stack-preview__title h1,
    .quiz-stack-preview__title h2,
    .quiz-stack-preview__title h3,
    .quiz-stack-preview__title h4,
    .quiz-stack-preview__subtitle h1,
    .quiz-stack-preview__subtitle h2,
    .quiz-stack-preview__subtitle h3,
    .quiz-stack-preview__subtitle h4,
    .quiz-stack-preview__body h1,
    .quiz-stack-preview__body h2,
    .quiz-stack-preview__body h3,
    .quiz-stack-preview__body h4 {
        margin-top: 0;
        margin-bottom: 0.35em;
        line-height: 1.25;
    }
    .quiz-stack-preview__title ul,
    .quiz-stack-preview__title ol,
    .quiz-stack-preview__subtitle ul,
    .quiz-stack-preview__subtitle ol,
    .quiz-stack-preview__body ul,
    .quiz-stack-preview__body ol {
        margin-bottom: 0.5em;
        padding-left: 1.25em;
    }
    .quiz-stack-preview__title blockquote,
    .quiz-stack-preview__subtitle blockquote {
        margin: 0 0 0.5em;
        padding-left: 0.75em;
        border-left: 3px solid var(--stack-accent, #111);
        opacity: 0.9;
    }
    .quiz-stack-preview__body blockquote {
        margin: 0 0 0.5em;
        padding-left: 0.75em;
        border-left: 3px solid var(--stack-accent, #111);
        opacity: 0.9;
        line-height: 1.35;
        max-width: none;
    }
    .quiz-stack-preview__body blockquote p {
        line-height: 1.35;
        font-size: inherit;
        font-style: inherit;
        text-decoration: none;
        margin-top: 0;
        margin-bottom: 0.35em !important;
    }
    .quiz-stack-preview__body blockquote p:last-child {
        margin-bottom: 0 !important;
    }
    .quiz-stack-preview__title a,
    .quiz-stack-preview__subtitle a,
    .quiz-stack-preview__body a {
        color: inherit;
        text-decoration: underline;
    }
    .quiz-stack-preview__tagline {
        font-size: 0.7rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 8px;
        width: 100%;
    }
    .quiz-stack-preview__tagline--serif { font-family: Georgia, serif; }
    .quiz-stack-preview__body { margin-bottom: 12px; width: 100%; font-size: 0.95rem; }
    .quiz-stack-preview__body--script { font-family: cursive; font-size: 1.25rem; }
    .quiz-stack-preview__sep-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--stack-accent, #111);
        margin: 6px 0 12px;
    }
    .quiz-stack-preview__sep-line {
        width: 36px;
        height: 1px;
        background: var(--stack-accent, #111);
        opacity: 0.5;
        margin: 6px 0 12px;
    }
    .quiz-stack-preview__mixture {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        width: 100%;
    }
    .quiz-stack-preview__mix-gap { flex-shrink: 0; }
    .quiz-stack-preview__meta { font-size: 0.75rem; opacity: 0.85; margin-bottom: 12px; width: 100%; }
    .quiz-stack-preview__cta {
        border: 1px solid var(--stack-btn, #ff5a1f);
        background: var(--stack-btn, #ff5a1f);
        color: var(--stack-btn-text, #fff);
        padding: 8px 24px;
        border-radius: 4px;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .quiz-stack-preview__cta--glow {
        box-shadow: 0 0 12px color-mix(in srgb, var(--stack-btn) 50%, transparent);
    }
    .quiz-stack-preview__secondary {
        font-size: 0.75rem;
        text-decoration: underline;
        margin-top: 8px;
    }
    .qq-admin-preview {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        padding: 16px 12px;
        min-height: 280px;
    }
    .qq-preview-inner { width: 100%; }
    .qq-preview-card { border-width: 1px; border-style: solid; }
    .qq-preview-answers { display: flex; flex-direction: column; gap: 8px; }
    .qq-preview-answers--grid { display: grid; grid-template-columns: repeat(2, 1fr); }
    .qq-preview-answers--pills { flex-direction: row; flex-wrap: wrap; }
    .qq-preview-answers--pills .qq-preview-answer { width: auto; flex: 0 1 auto; }
    .qq-preview-answer { cursor: default; border-width: 1px; border-style: solid; }
    .qq-preview-answer--image { text-align: center; }
    .qq-preview-image-thumb {
        display: block;
        width: 48px;
        height: 48px;
        background: #e5e7eb;
        border-radius: 6px;
        margin-bottom: 6px;
    }
    .qq-preview-answers--image-left .qq-preview-answer--image { flex-direction: row !important; text-align: left; }
    .qq-preview-answers--image-left .qq-preview-image-thumb { margin-bottom: 0; margin-right: 8px; }
    .qq-preview-progress-track { width: 100%; background: #e9ecef; }
    .qq-preview-progress-bar { background: #ff5a1f; transition: width 0.2s; }
</style>
