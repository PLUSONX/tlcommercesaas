<div class="input-group addon">
    <input type="text" id="{{ $id }}" class="color-input form-control style--two {{ $class ?? '' }}"
        value="{{ $value }}">
    <div class="input-group-append">
        <input type="color" class="input-group-text theme-input-style2 color-picker {{ $class ?? '' }}"
            value="{{ $value }}"
            oninput="document.getElementById('{{ $id }}').value = this.value; if (typeof window.syncQuizIntroBuilder === 'function') window.syncQuizIntroBuilder();">
    </div>
</div>
