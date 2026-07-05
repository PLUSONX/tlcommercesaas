@extends('core::base.layouts.master')
@section('title')
    {{ $quiz ? translate('Edit Quiz') : translate('New Quiz') }}
@endsection
@section('custom_css')
    <link href="{{ asset('backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <style>
        .color-picker { cursor: pointer; }
    </style>
@endsection
@section('main_content')
    @php
        use Theme\TLCommerce\Services\QuizLayoutConfigTranslation;
        $lang = $lang ?? getDefaultLang();
        $isDefaultLang = $lang == getDefaultLang();
        $layoutConfigForForm = isset($quiz)
            ? QuizLayoutConfigTranslation::translatedLayoutForAdmin($quiz, $lang)
            : [];
        $intro = $layoutConfigForForm['intro'] ?? [];
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-10">
                    <h4 style="font-size: 30px;" class="mb-0">{{ $quiz ? translate('Edit Quiz') : translate('New Quiz') }}</h4>
                    @if ($quiz)
                        <a href="{{ route('theme.tlcommerce.quiz.questions', ['id' => $quiz->id, 'lang' => $lang]) }}" class="btn long">
                            {{ translate('Manage Questions') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-2" style="border-radius: 12px !important;">
                <div class="form-element py-30">
                    <form action="{{ $quiz ? route('theme.tlcommerce.quiz.update') : route('theme.tlcommerce.quiz.store') }}" method="POST" id="quiz-form">
                        @csrf
                        @if ($quiz)
                            <input type="hidden" name="id" value="{{ $quiz->id }}">
                            @include('theme/tlcommerce::backend.quiz.partials.language_tabs', [
                                'tabRoute' => 'theme.tlcommerce.quiz.edit',
                                'tabRouteParams' => ['id' => $quiz->id],
                            ])
                        @endif

                        <h5 class="mb-3">{{ translate('General') }}</h5>

                        <div class="form-row mb-20">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Title') }}</label></div>
                            <div class="col-md-12">
                                <input type="text" name="title" id="quiz-title-input" class="theme-input-style quiz-preview-input"
                                    value="{{ old('title', isset($quiz) ? $quiz->translation('title', $lang) : '') }}" placeholder="{{ translate('Quiz title') }}" required>
                                <small id="intro-custom-stack-title-note" class="text-muted d-none">{{ translate('Display title is edited in Content blocks below; this field sets the quiz name for admin and lists.') }}</small>
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Slug') }}</label></div>
                            <div class="col-md-12">
                                <input type="text" name="slug" class="theme-input-style" value="{{ old('slug', $quiz->slug ?? '') }}"
                                    placeholder="{{ translate('quiz-url-slug') }}">
                                <small class="text-muted">{{ translate('Used in share URL: /quiz/{slug}') }}</small>
                            </div>
                        </div>

                        @if ($quiz)
                            <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                                <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Share URL') }}</label></div>
                                <div class="col-md-12">
                                    @php $shareUrl = url('/quiz/' . $quiz->slug); @endphp
                                    <div class="input-group">
                                        <input type="text" class="theme-input-style" id="quiz-share-url" readonly value="{{ $shareUrl }}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn long" id="copy-share-url">{{ translate('Copy') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">
                        <h5 class="mb-3">{{ translate('Start Page') }}</h5>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Skip intro page') }}</label></div>
                            <div class="col-md-12">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="skip_intro" value="1" class="quiz-preview-input"
                                        {{ old('skip_intro', isset($quiz) ? ($quiz->layout_config['skip_intro'] ?? false) : false) ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                                <span class="ml-2">{{ translate('Go directly to questions') }}</span>
                            </div>
                        </div>

                        <div id="intro-global-copy-fields">
                        <div class="form-row mb-20">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Subtitle') }}</label></div>
                            <div class="col-md-12">
                                <input type="text" name="intro_subtitle" id="intro-subtitle-input" class="theme-input-style quiz-preview-input"
                                    value="{{ old('intro_subtitle', $intro['subtitle'] ?? '') }}" placeholder="{{ translate('Short line under title') }}">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Intro body') }}</label></div>
                            <div class="col-md-12">
                                <textarea name="description" id="quiz-description-input" class="theme-input-style quiz-preview-input" rows="6">{!! old('description', isset($quiz) ? $quiz->translation('description', $lang) : '') !!}</textarea>
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('CTA button text') }}</label></div>
                            <div class="col-md-12">
                                <input type="text" name="intro_cta_text" id="intro-cta-input" class="theme-input-style quiz-preview-input"
                                    value="{{ old('intro_cta_text', $intro['cta_text'] ?? '') }}" placeholder="{{ translate('Start Quiz') }}">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Secondary link text') }}</label></div>
                            <div class="col-md-12">
                                <input type="text" name="intro_secondary_link_text" id="intro-secondary-text-input" class="theme-input-style quiz-preview-input"
                                    value="{{ old('intro_secondary_link_text', $intro['secondary_link_text'] ?? '') }}" placeholder="{{ translate('Browse products') }}">
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Secondary link URL') }}</label></div>
                            <div class="col-md-12">
                                <input type="text" name="intro_secondary_link_url" id="intro-secondary-url-input" class="theme-input-style quiz-preview-input"
                                    value="{{ old('intro_secondary_link_url', $intro['secondary_link_url'] ?? '') }}" placeholder="/products">
                            </div>
                        </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Layout preset') }}</label></div>
                            <div class="col-md-12">
                                <select name="intro_layout" id="intro-layout-input" class="theme-input-style quiz-preview-input">
                                    @foreach (['minimal' => 'Minimal', 'hero_centered' => 'Hero centered', 'hero_image_left' => 'Hero image left', 'hero_square_top' => 'Hero square (above)', 'hero_square_bottom' => 'Hero square (below)', 'full_bleed' => 'Full bleed overlay', 'custom_stack' => 'Custom stack (flexible)'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('intro_layout', $intro['layout'] ?? 'minimal') === $val ? 'selected' : '' }}>{{ translate($label) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Text alignment') }}</label></div>
                            <div class="col-md-12">
                                <select name="intro_alignment" id="intro-alignment-input" class="theme-input-style quiz-preview-input">
                                    @foreach (['left', 'center', 'right'] as $align)
                                        <option value="{{ $align }}" {{ old('intro_alignment', $intro['alignment'] ?? 'center') === $align ? 'selected' : '' }}>{{ ucfirst($align) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Show question count') }}</label></div>
                            <div class="col-md-12">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="intro_show_question_count" value="1" id="intro-show-count-input" class="quiz-preview-input"
                                        {{ old('intro_show_question_count', array_key_exists('show_question_count', $intro) ? $intro['show_question_count'] : true) ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Show estimated time') }}</label></div>
                            <div class="col-md-12 d-flex align-items-center flex-wrap gap-10">
                                <label class="switch glow primary medium mb-0">
                                    <input type="checkbox" name="intro_show_estimated_time" value="1" id="intro-show-time-input" class="quiz-preview-input"
                                        {{ old('intro_show_estimated_time', $intro['show_estimated_time'] ?? false) ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                                <input type="number" name="intro_estimated_minutes" id="intro-minutes-input" class="theme-input-style quiz-preview-input" style="width: 100px;"
                                    min="1" max="60" value="{{ old('intro_estimated_minutes', $intro['estimated_minutes'] ?? 2) }}">
                                <span>{{ translate('minutes') }}</span>
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black mb-0">{{ translate('Hero image (desktop)') }}</label>
                            </div>
                            <div class="col-md-12">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'intro_hero_image_desktop',
                                    'data' => old('intro_hero_image_desktop', $intro['hero_image_desktop'] ?? ''),
                                ])
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black mb-0">{{ translate('Hero image (mobile)') }}</label>
                            </div>
                            <div class="col-md-12">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'intro_hero_image_mobile',
                                    'data' => old('intro_hero_image_mobile', $intro['hero_image_mobile'] ?? ''),
                                ])
                            </div>
                        </div>

                        @include('theme/tlcommerce::backend.quiz.partials.intro_block_builder', ['isDefaultLang' => $isDefaultLang])

                        <div id="intro-legacy-color-fields" @if (!$isDefaultLang) class="area-disabled" @endif>
                        @php
                            $colorFields = [
                                'intro_background_color' => ['label' => 'Background color', 'default' => '#ffffff', 'id' => 'intro-bg-input', 'key' => 'background_color'],
                                'intro_text_color' => ['label' => 'Text color', 'default' => '#111111', 'id' => 'intro-text-input', 'key' => 'text_color'],
                                'intro_button_color' => ['label' => 'Button color', 'default' => '#ff5a1f', 'id' => 'intro-btn-input', 'key' => 'button_color'],
                                'intro_button_text_color' => ['label' => 'Button text color', 'default' => '#ffffff', 'id' => 'intro-btn-text-input', 'key' => 'button_text_color'],
                                'intro_overlay_color' => ['label' => 'Overlay color', 'default' => '#000000', 'id' => 'intro-overlay-input', 'key' => 'overlay_color'],
                            ];
                        @endphp

                        @foreach ($colorFields as $fieldName => $meta)
                            @php $colorVal = old($fieldName, $intro[$meta['key']] ?? $meta['default']); @endphp
                            <div class="form-row mb-20">
                                <div class="col-sm-4"><label class="font-14 bold black">{{ translate($meta['label']) }}</label></div>
                                <div class="col-md-12">
                                    <div class="input-group addon">
                                        <input type="text" name="{{ $fieldName }}" id="{{ $meta['id'] }}"
                                            class="color-input form-control style--two quiz-preview-input quiz-color-input"
                                            value="{{ $colorVal }}">
                                        <div class="input-group-append">
                                            <input type="color" class="input-group-text theme-input-style2 color-picker quiz-preview-input"
                                                value="{{ $colorVal }}"
                                                oninput="document.getElementById('{{ $meta['id'] }}').value = this.value; updateQuizIntroPreview();">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="form-row mb-20 intro-legacy-only">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Overlay opacity') }} (%)</label></div>
                            <div class="col-md-12">
                                <input type="range" name="intro_overlay_opacity" id="intro-overlay-opacity-input" class="quiz-preview-input"
                                    min="0" max="100" value="{{ old('intro_overlay_opacity', $intro['overlay_opacity'] ?? 40) }}">
                            </div>
                        </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">{{ translate('Quiz flow') }}</h5>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Step Style') }}</label></div>
                            <div class="col-md-12">
                                @php $stepStyle = old('step_style', isset($quiz) ? ($quiz->layout_config['step_style'] ?? 'wizard') : 'wizard'); @endphp
                                <select name="step_style" class="theme-input-style">
                                    <option value="wizard" {{ $stepStyle === 'wizard' ? 'selected' : '' }}>{{ translate('Wizard') }}</option>
                                    <option value="scroll" {{ $stepStyle === 'scroll' ? 'selected' : '' }}>{{ translate('Scroll') }}</option>
                                    <option value="cards" {{ $stepStyle === 'cards' ? 'selected' : '' }}>{{ translate('Cards') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Results to show') }}</label></div>
                            <div class="col-md-12">
                                <input type="number" name="top_n" class="theme-input-style" min="1" max="20"
                                    value="{{ old('top_n', isset($quiz) ? ($quiz->layout_config['top_n'] ?? 3) : 3) }}">
                            </div>
                        </div>

                        <div @if (!$isDefaultLang) class="area-disabled" @endif>
                        @include('theme/tlcommerce::backend.quiz.partials.questions_theme_panel')
                        </div>

                        @include('theme/tlcommerce::backend.quiz.partials.results_theme_panel')

                        <div class="form-row mb-20 @if (!$isDefaultLang) area-disabled @endif">
                            <div class="col-sm-4"><label class="font-14 bold black">{{ translate('Status') }}</label></div>
                            <div class="col-md-12">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', isset($quiz) ? $quiz->is_active : true) ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                                <span class="ml-2">{{ translate('Active') }}</span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="submit" class="btn long btn-orange">{{ translate('Save') }}</button>
                                <a href="{{ route('theme.tlcommerce.quiz.list') }}" class="btn long btn-danger ml-2">{{ translate('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 @if (!$isDefaultLang) area-disabled @endif">
            @include('theme/tlcommerce::backend.quiz.partials.intro_preview')
        </div>
    </div>

    @include('core::base.media.partial.media_modal')
@endsection

@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/summernote/summernote-lite.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    @include('theme/tlcommerce::backend.quiz.partials.intro_builder_scripts')
    @include('theme/tlcommerce::backend.quiz.partials.questions_theme_scripts')
    @include('theme/tlcommerce::backend.quiz.partials.results_builder_scripts')
@endsection
