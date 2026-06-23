@php
    $lang = $lang ?? getDefaultLang();
    $languages = $languages ?? \Core\Models\Language::where('status', config('settings.general_status.active'))
        ->select('id', 'name', 'code', 'native_name')
        ->get();
    $isDefaultLang = $lang == getDefaultLang();
@endphp

<div class="mb-3">
    <p class="alert alert-info mb-3">
        {{ translate('You are editing') }}
        <strong>"{{ getLanguageNameByCode($lang) }}"</strong>
        {{ translate('version') }}
    </p>

    <ul class="nav nav-tabs nav-fill border-light border-0">
        @foreach ($languages as $language)
            <li class="nav-item">
                <a class="nav-link @if ($language->code == $lang) active border-0 @else bg-light @endif py-3"
                    href="{{ route($tabRoute, array_merge($tabRouteParams ?? [], ['lang' => $language->code])) }}">
                    <img src="{{ asset('flags/') . '/' . $language->code . '.png' }}" width="20px" alt="">
                    <span>{{ $language->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<input type="hidden" name="lang" value="{{ $lang }}">
