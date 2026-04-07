@php
    $isactivateFlashdeal = isActivePluging('flashdeal');
@endphp
@if ($isactivateFlashdeal)
    @if (auth()->user()->can('Manage Flash Deals'))
        <li class="{{ Request::routeIs(['plugin.flashdeal.list']) ? 'active ' : '' }}">
            <a class="pl-2" href="{{ route('plugin.flashdeal.list') }}">{{ translate('Flash Deals') }}</a>
        </li>
    @endif
@endif
