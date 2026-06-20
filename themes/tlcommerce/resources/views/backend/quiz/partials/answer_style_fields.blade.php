@php

    $style = $style ?? [];

    $fieldClass = $fieldClass ?? 'qa-style-input';

    $prefix = $prefix ?? 'qa-style';

    $layout = $layout ?? 'list';

@endphp



<div class="form-row mb-15">

    <div class="col-md-4">

        <label class="small">{{ translate('Gap') }} (px)</label>

        <input type="number" id="{{ $prefix }}-gap" class="theme-input-style {{ $fieldClass }} w-100" min="0" max="32" value="{{ $style['gap'] ?? 8 }}">

    </div>

    <div class="col-md-4">

        <label class="small">{{ translate('Border width') }} (px)</label>

        <input type="number" id="{{ $prefix }}-border-width" class="theme-input-style {{ $fieldClass }} w-100" min="0" max="4" value="{{ $style['border_width'] ?? 1 }}">

    </div>

    <div class="col-md-4">

        <label class="small">{{ translate('Padding') }} (px)</label>

        <input type="number" id="{{ $prefix }}-padding" class="theme-input-style {{ $fieldClass }} w-100" min="0" max="64" value="{{ $style['padding'] ?? 12 }}">

    </div>

</div>

<div class="form-row mb-15">

    <div class="col-md-4">

        <label class="small">{{ translate('Radius') }} (px)</label>

        <input type="number" id="{{ $prefix }}-radius" class="theme-input-style {{ $fieldClass }} w-100" min="0" max="32" value="{{ $style['radius'] ?? 8 }}">

    </div>

    <div class="col-md-8 d-flex align-items-end">

        <label class="mb-0">

            <input type="checkbox" id="{{ $prefix }}-show-input" class="{{ $fieldClass }}" {{ ($style['show_native_input'] ?? true) ? 'checked' : '' }}>

            {{ translate('Show native radio/checkbox inputs') }}

        </label>

    </div>

</div>

<div class="form-row mb-15">

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'background',

        'inputSuffix' => 'bg',

        'label' => translate('Background'),

        'style' => $style,

        'defaultSolid' => '#ffffff',

    ])

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'border_color',

        'inputSuffix' => 'border',

        'label' => translate('Border color'),

        'style' => $style,

        'defaultSolid' => '#e2e2e2',

    ])

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'text_color',

        'inputSuffix' => 'text',

        'label' => translate('Text color'),

        'style' => $style,

        'defaultSolid' => '#111111',

    ])

</div>

<div class="form-row mb-15">

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'hover_background',

        'inputSuffix' => 'hover-bg',

        'label' => translate('Hover background'),

        'style' => $style,

        'defaultSolid' => '#f3f4f6',

    ])

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'hover_border_color',

        'inputSuffix' => 'hover-border',

        'label' => translate('Hover border'),

        'style' => $style,

        'defaultSolid' => '#d1d5db',

    ])

</div>

<div class="form-row mb-15">

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'active_background',

        'inputSuffix' => 'active-bg',

        'label' => translate('Selected background'),

        'style' => $style,

        'defaultSolid' => '#f8f9fa',

    ])

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'active_border_color',

        'inputSuffix' => 'active-border',

        'label' => translate('Selected border'),

        'style' => $style,

        'defaultSolid' => '#333333',

    ])

    @include('theme/tlcommerce::backend.quiz.partials.fill_color_field', [

        'prefix' => $prefix,

        'fieldClass' => $fieldClass,

        'baseKey' => 'active_text_color',

        'inputSuffix' => 'active-text',

        'label' => translate('Selected text'),

        'style' => $style,

        'defaultSolid' => '#111111',

    ])

</div>



@if ($layout === 'grid')

    <div class="form-row mb-15">

        <div class="col-md-4">

            <label class="small">{{ translate('Grid columns') }}</label>

            <input type="number" id="{{ $prefix }}-grid-columns" class="theme-input-style {{ $fieldClass }} w-100" min="2" max="4" value="{{ $style['grid_columns'] ?? 2 }}">

        </div>

        <div class="col-md-4">

            <label class="small">{{ translate('Image size') }} (px)</label>

            <input type="number" id="{{ $prefix }}-image-size" class="theme-input-style {{ $fieldClass }} w-100" min="40" max="160" value="{{ $style['image_size'] ?? 80 }}">

        </div>

        <div class="col-md-4">

            <label class="small">{{ translate('Image position') }}</label>

            <select id="{{ $prefix }}-image-position" class="theme-input-style {{ $fieldClass }} w-100">

                <option value="top" {{ ($style['image_position'] ?? 'top') === 'top' ? 'selected' : '' }}>{{ translate('Top (stacked)') }}</option>

                <option value="left" {{ ($style['image_position'] ?? '') === 'left' ? 'selected' : '' }}>{{ translate('Left (inline)') }}</option>

            </select>

        </div>

    </div>
    <div class="form-row mb-15">
        <div class="col-md-4">
            <label class="small">{{ translate('Hover / selected scale') }}</label>
            <input type="number" id="{{ $prefix }}-hover-scale" class="theme-input-style {{ $fieldClass }} w-100" min="1" max="1.2" step="0.01" value="{{ $style['hover_scale'] ?? 1 }}">
            <small class="text-muted">{{ translate('1 = no scale, 1.05 = 5% larger') }}</small>
        </div>
    </div>

@endif

