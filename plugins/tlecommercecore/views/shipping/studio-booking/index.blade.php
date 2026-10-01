@extends('core::base.layouts.master')

@section('title')
    {{ translate('Studio Booking Schedule') }}
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="align-items-center border-bottom2 d-flex flex-wrap gap-10 justify-content-between mb-4 pb-3">
        <h4>{{ translate('Studio Booking Schedule') }}</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- BOOKING TOGGLE --}}
    <div class="card mb-30">
        <div class="card-header bg-white py-3">
            <h4 class="font-18 mb-0">{{ translate('Booking Settings') }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('plugin.tlcommercecore.shipping.studio.booking.settings.update') }}">
                @csrf

                <div class="d-flex align-items-center justify-content-between mb-20">
                    <div>
                        <div class="font-14 bold black">{{ translate('Enable Book Now') }}</div>
                        <small class="text-muted">
                            {{ translate('This controls only the Studio Book Now feature. Deliver Now is not changed.') }}
                        </small>
                    </div>

                    <label class="studio-switch">
                        <input type="checkbox" name="enabled" value="1" {{ $enabled ? 'checked' : '' }}>
                        <span></span>
                    </label>
                </div>

                <button type="submit" class="btn long">{{ translate('Save') }}</button>
            </form>
        </div>
    </div>

    {{-- GENERATE HOURLY SLOTS --}}
    <div class="card mb-30">
        <div class="card-header bg-white py-3">
            <h4 class="font-18 mb-0">{{ translate('Generate Hourly Slots') }}</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('plugin.tlcommercecore.shipping.studio.booking.slots.generate') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="font-14 bold black">{{ translate('Start Date') }}</label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ old('start_date', now()->format('Y-m-d')) }}"
                            min="{{ now()->format('Y-m-d') }}"
                            class="theme-input-style"
                            required
                        >
                    </div>

                    <div class="form-group col-md-3">
                        <label class="font-14 bold black">{{ translate('End Date') }}</label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ old('end_date', now()->format('Y-m-d')) }}"
                            min="{{ now()->format('Y-m-d') }}"
                            class="theme-input-style"
                            required
                        >
                    </div>

                    <div class="form-group col-md-2">
                        <label class="font-14 bold black">{{ translate('Opening Time') }}</label>
                        <input
                            type="time"
                            name="opening_time"
                            value="{{ old('opening_time', '09:00') }}"
                            class="theme-input-style"
                            required
                        >
                    </div>

                    <div class="form-group col-md-2">
                        <label class="font-14 bold black">{{ translate('Closing Time') }}</label>
                        <input
                            type="time"
                            name="closing_time"
                            value="{{ old('closing_time', '18:00') }}"
                            class="theme-input-style"
                            required
                        >
                    </div>

                    <div class="form-group col-md-2">
                        <label class="font-14 bold black">{{ translate('Slot Duration') }}</label>
                        <select name="slot_minutes" class="theme-input-style">
                            <option value="60" selected>
                                60 {{ translate('Minutes / 1 Hour') }}
                            </option>
                        </select>
                    </div>
                </div>

                <small class="text-muted d-block mb-15">
                    {{ translate('Use the same opening and closing time for every date in the selected range. For one date, keep Start Date and End Date the same.') }}
                </small>

                <button type="submit" class="btn long">{{ translate('Generate Hours') }}</button>
            </form>
        </div>
    </div>

    {{-- GENERATED DATES OVERVIEW --}}
    <div class="card mb-30">
        <div class="card-header bg-white py-3">
            <h4 class="font-18 mb-0">{{ translate('Generated Dates') }}</h4>
        </div>
        <div class="card-body">
            @if(isset($overview) && $overview->count())
                <div class="table-responsive">
                    <table class="dh-table">
                        <thead>
                            <tr>
                                <th>{{ translate('Date') }}</th>
                                <th>{{ translate('Total Hours') }}</th>
                                <th>{{ translate('Available') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($overview as $row)
                            @php($rowDate = \Carbon\Carbon::parse($row->schedule_date)->format('Y-m-d'))
                            <tr class="{{ $rowDate === $selectedDate ? 'table-active' : '' }}">
                                <td>{{ \Carbon\Carbon::parse($rowDate)->format('D, M j, Y') }}</td>
                                <td>{{ $row->total_slots }}</td>
                                <td>{{ $row->available_slots }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-outline-primary"
                                       href="{{ route('plugin.tlcommercecore.shipping.studio.booking', ['date' => $rowDate]) }}">
                                        {{ translate('View') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    {{ translate('No studio hours have been generated yet.') }}
                </div>
            @endif
        </div>
    </div>

    {{-- DAILY SLOT LIST --}}
    <div class="card mb-30">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-10">
            <h4 class="font-18 mb-0">
                {{ translate('Hours for') }} {{ \Carbon\Carbon::parse($selectedDate)->format('D, M j, Y') }}
            </h4>

            <form method="GET" action="{{ route('plugin.tlcommercecore.shipping.studio.booking') }}" class="d-flex align-items-center gap-10">
                <input type="date" name="date" value="{{ $selectedDate }}" class="theme-input-style">
                <button type="submit" class="btn btn-sm btn-outline-primary">{{ translate('Show') }}</button>
            </form>
        </div>

        <div class="card-body">
            @if($slots->count())
                <div class="table-responsive">
                    <table class="dh-table">
                        <thead>
                            <tr>
                                <th>{{ translate('Time') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right">{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($slots as $slot)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $slot->start_time)->format('g:i A') }}
                                    -
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $slot->end_time)->format('g:i A') }}
                                </td>

                                <td>
                                    @if($slot->booking_state === 'confirmed')
                                        <span class="badge badge-primary">{{ translate('Booked') }}</span>
                                    @elseif($slot->booking_state === 'held')
                                        <span class="badge badge-warning">{{ translate('Held (checkout in progress)') }}</span>
                                    @elseif($slot->status)
                                        <span class="badge badge-success">{{ translate('Available') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ translate('Disabled') }}</span>
                                    @endif
                                </td>

                                <td class="text-right">
                                    <div class="d-flex justify-content-end align-items-center gap-10">
                                        <form method="POST" action="{{ route('plugin.tlcommercecore.shipping.studio.booking.slots.status') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $slot->id }}">
                                            <input type="hidden" name="status" value="{{ $slot->status ? 0 : 1 }}">
                                            <button
                                                class="btn btn-sm {{ $slot->status ? 'btn-danger' : 'btn-success' }}"
                                                type="submit"
                                            >
                                                {{ $slot->status ? translate('Disable') : translate('Enable') }}
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('plugin.tlcommercecore.shipping.studio.booking.slots.delete') }}"
                                            onsubmit="return confirm('{{ translate('Are you sure you want to delete this studio hour?') }}');"
                                        >
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $slot->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                {{ translate('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    {{ translate('No hourly slots exist for this date yet. Generate them above.') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('custom_css')
<style>
    .studio-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        margin: 0;
    }

    .studio-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .studio-switch span {
        position: absolute;
        inset: 0;
        background: #ccc;
        border-radius: 30px;
        cursor: pointer;
        transition: .2s;
    }

    .studio-switch span:before {
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        left: 3px;
        top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: .2s;
    }

    .studio-switch input:checked + span {
        background: #ff5a00;
    }

    .studio-switch input:checked + span:before {
        transform: translateX(22px);
    }
</style>
@endsection
