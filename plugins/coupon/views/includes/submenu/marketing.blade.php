@php
    $isactivateCoupon = isActivePluging('coupon');
@endphp
@if ($isactivateCoupon)
    @if (auth()->user()->can('Manage Coupons'))
        <li class="{{ Request::routeIs(['plugin.tlcommercecore.marketing.coupon.list']) ? 'active ' : '' }}">
            <a class="pl-2" href="{{ route('plugin.tlcommercecore.marketing.coupon.list') }}">{{ translate('Coupons') }}</a>
        </li>
    @endif
@endif
