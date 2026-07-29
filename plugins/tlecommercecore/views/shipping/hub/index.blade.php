@extends('core::base.layouts.master')
@section('title')
    {{ translate('Carriers & Locations') }}
@endsection
@section('main_content')
    @php
        $active_tab = $active_tab ?? 'locations';
        $location_tab = $location_tab ?? 'countries';
        $canCarriers = $can_carriers ?? false;
        $canLocations = $can_locations ?? false;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Carriers & Locations') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-20">
            <ul class="nav nav-tabs" id="shippingHubTab" role="tablist">
                @if ($canCarriers)
                    <li class="nav-item">
                        <a class="nav-link {{ $active_tab === 'carriers' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.shipping.hub', ['tab' => 'carriers']) }}">
                            {{ translate('Carriers') }}
                        </a>
                    </li>
                @endif
                @if ($canLocations)
                    <li class="nav-item">
                        <a class="nav-link {{ $active_tab === 'locations' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.shipping.hub', ['tab' => 'locations', 'location' => 'countries']) }}">
                            {{ translate('Locations') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        @if ($active_tab === 'locations' && $canLocations)
            <div class="col-12 mb-20">
                <ul class="nav nav-tabs" id="locationsSubTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $location_tab === 'countries' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.shipping.hub', ['tab' => 'locations', 'location' => 'countries']) }}">
                            {{ translate('Countries') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $location_tab === 'states' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.shipping.hub', ['tab' => 'locations', 'location' => 'states']) }}">
                            {{ translate('States') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $location_tab === 'cities' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.shipping.hub', ['tab' => 'locations', 'location' => 'cities']) }}">
                            {{ translate('Cities') }}
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <div class="col-12">
            @if ($active_tab === 'carriers' && $canCarriers)
                @include('plugin/tlecommercecore::shipping.hub._partials.carriers')
            @elseif ($active_tab === 'locations' && $canLocations)
                @if ($location_tab === 'states')
                    @include('plugin/tlecommercecore::shipping.hub._partials.states')
                @elseif ($location_tab === 'cities')
                    @include('plugin/tlecommercecore::shipping.hub._partials.cities')
                @else
                    @include('plugin/tlecommercecore::shipping.hub._partials.countries')
                @endif
            @endif
        </div>
    </div>
@endsection

@section('custom_scripts')
    @php
        $active_tab = $active_tab ?? 'locations';
        $location_tab = $location_tab ?? 'countries';
        $canCarriers = $can_carriers ?? false;
        $canLocations = $can_locations ?? false;
    @endphp
    @if ($active_tab === 'carriers' && $canCarriers)
        @include('plugin/tlecommercecore::shipping.hub._partials.carriers-scripts')
    @elseif ($active_tab === 'locations' && $canLocations)
        @if ($location_tab === 'states')
            @include('plugin/tlecommercecore::shipping.hub._partials.states-scripts')
        @elseif ($location_tab === 'cities')
            @include('plugin/tlecommercecore::shipping.hub._partials.cities-scripts')
        @else
            @include('plugin/tlecommercecore::shipping.hub._partials.countries-scripts')
        @endif
    @endif
@endsection

@include('plugin/tlecommercecore::shipping.hub._partials.styles')
