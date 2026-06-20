@php
    $style = $style ?? [];
    $fieldClass = $fieldClass ?? 'qa-style-input';
    $prefix = $prefix ?? 'qa-style';
    $baseKey = $baseKey ?? 'background';
    $inputSuffix = $inputSuffix ?? 'bg';
    $label = $label ?? translate('Color');
    $defaultSolid = $defaultSolid ?? '#ffffff';
    $defaultEdge = $defaultEdge ?? '#050a07';
    $typeKey = $baseKey . '_type';
    $centerKey = $baseKey . '_gradient_center';
    $edgeKey = $baseKey . '_gradient_edge';
    $fillType = $style[$typeKey] ?? 'solid';
@endphp

<div class="col-md-4 mb-15 qa-fill-color-field" data-prefix="{{ $prefix }}" data-suffix="{{ $inputSuffix }}">
    <label class="small d-block">{{ $label }}</label>
    <select id="{{ $prefix }}-{{ $inputSuffix }}-type" class="theme-input-style {{ $fieldClass }} w-100 mb-2 qa-fill-type-select">
        <option value="solid" {{ $fillType === 'solid' ? 'selected' : '' }}>{{ translate('Solid') }}</option>
        <option value="radial_gradient" {{ $fillType === 'radial_gradient' ? 'selected' : '' }}>{{ translate('Radial gradient') }}</option>
    </select>
    <div class="{{ $prefix }}-{{ $inputSuffix }}-solid-row {{ $fillType === 'solid' ? '' : 'd-none' }}">
        @include('theme/tlcommerce::backend.quiz.partials.color_input', [
            'id' => $prefix . '-' . $inputSuffix,
            'value' => $style[$baseKey] ?? $defaultSolid,
            'class' => $fieldClass,
        ])
    </div>
    <div class="{{ $prefix }}-{{ $inputSuffix }}-gradient-rows {{ $fillType === 'radial_gradient' ? '' : 'd-none' }}">
        <div class="mb-2">
            <label class="small text-muted">{{ translate('Gradient center') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => $prefix . '-' . $inputSuffix . '-gradient-center',
                'value' => $style[$centerKey] ?? $defaultSolid,
                'class' => $fieldClass,
            ])
        </div>
        <div>
            <label class="small text-muted">{{ translate('Gradient edge') }}</label>
            @include('theme/tlcommerce::backend.quiz.partials.color_input', [
                'id' => $prefix . '-' . $inputSuffix . '-gradient-edge',
                'value' => $style[$edgeKey] ?? $defaultEdge,
                'class' => $fieldClass,
            ])
        </div>
    </div>
</div>
