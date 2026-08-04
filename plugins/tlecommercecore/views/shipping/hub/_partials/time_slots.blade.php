@php
    $settings = $delivery_schedule_settings ?? [];
    $slots = $delivery_schedule_slots ?? collect();
    $fromDate = $from_date ?? now()->toDateString();
    $toDate = $to_date ?? now()->addDays(30)->toDateString();
    $tablesExist = $tables_exist ?? false;
    $groupedSlots = $slots->groupBy('schedule_date');
@endphp

@if (!$tablesExist)
    <div class="col-12">
        <div class="alert alert-warning">
            {{ translate('Delivery scheduling tables are not available. Please ensure tl_com_delivery_schedule_slots exists in this tenant database.') }}
        </div>
    </div>
@else
    <div class="row">
        {{-- Feature settings --}}
        <div class="col-12 mb-20">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body">
                    <h4 class="mb-20">{{ translate('Delivery Scheduling Settings') }}</h4>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.delivery.schedule.settings.update') }}">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black">{{ translate('Enable delivery time scheduling') }}</label>
                            </div>
                            <div class="col-sm-6">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="enable_delivery_scheduling"
                                        @checked(($settings['enable_delivery_scheduling'] ?? null) == config('settings.general_status.active'))>
                                    <span class="control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black">{{ translate('Require time slot at checkout') }}</label>
                            </div>
                            <div class="col-sm-6">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="delivery_scheduling_required"
                                        @checked(($settings['delivery_scheduling_required'] ?? null) == config('settings.general_status.active'))>
                                    <span class="control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black">{{ translate('Minimum booking notice') }}</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group addon" style="gap: 5px !important;">
                                    <input type="number" name="delivery_scheduling_lead_time" min="0"
                                        value="{{ $settings['delivery_scheduling_lead_time'] ?? 0 }}"
                                        class="theme-input-style" style="width: 75% !important;">
                                    <div class="input-group-append" style="width: 24% !important;">
                                        <select class="theme-input-style" name="delivery_scheduling_lead_time_unit">
                                            <option value="{{ config('tlecommercecore.time_unit.Days') }}"
                                                @selected(($settings['delivery_scheduling_lead_time_unit'] ?? '') == config('tlecommercecore.time_unit.Days'))>
                                                {{ translate('Days') }}</option>
                                            <option value="{{ config('tlecommercecore.time_unit.Hours') }}"
                                                @selected(($settings['delivery_scheduling_lead_time_unit'] ?? config('tlecommercecore.time_unit.Hours')) == config('tlecommercecore.time_unit.Hours'))>
                                                {{ translate('Hours') }}</option>
                                            <option value="{{ config('tlecommercecore.time_unit.Minutes') }}"
                                                @selected(($settings['delivery_scheduling_lead_time_unit'] ?? '') == config('tlecommercecore.time_unit.Minutes'))>
                                                {{ translate('Minutes') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black">{{ translate('Booking horizon') }}</label>
                                <p class="text-muted font-13 mb-0">{{ translate('How many days ahead customers can book a delivery slot.') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group addon" style="gap: 5px !important;">
                                    <input type="number" name="delivery_scheduling_horizon_days" min="1" max="90"
                                        value="{{ $settings['delivery_scheduling_horizon_days'] ?? 30 }}"
                                        class="theme-input-style" style="width: 75% !important;">
                                    <div class="input-group-append" style="width: 24% !important;">
                                        <span class="theme-input-style d-flex align-items-center px-3">{{ translate('Days') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn long btn-orange">{{ translate('Save Settings') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Create slots --}}
        <div class="col-12 mb-20">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <h4 class="mb-0">{{ translate('Create Time Slots') }}</h4>
                        <button type="button" class="btn long btn-orange" id="add-slot-row">
                            {{ translate('Add another time slot') }}
                        </button>
                    </div>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.store') }}"
                        id="delivery-slots-form">
                        @csrf
                        <input type="hidden" name="from_date" value="{{ $fromDate }}">
                        <input type="hidden" name="to_date" value="{{ $toDate }}">
                        <div class="form-row mb-20 align-items-center">
                            <div class="col-md-4">
                                <label class="font-14 bold black">{{ translate('Enable recurrence') }}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="enable_recurrence" id="enable-recurrence-toggle" value="1">
                                    <span class="control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-md-4 mb-10">
                                <label class="font-14 bold black">{{ translate('From date') }}</label>
                                <input type="date" name="schedule_from" id="schedule-from-input" class="theme-input-style"
                                    min="{{ now()->toDateString() }}" required>
                            </div>
                            <div class="col-md-4 mb-10">
                                <label class="font-14 bold black">{{ translate('To date') }}</label>
                                <input type="date" name="schedule_until" id="schedule-until-input" class="theme-input-style"
                                    min="{{ now()->toDateString() }}" required>
                            </div>
                        </div>
                        <p id="date-range-helper" class="text-muted font-13 mb-20">
                            {{ translate('Slots will be created for each day in this range (max 90 days).') }}
                        </p>
                        <div id="recurrence-fields" class="mb-20" style="display: none;">
                            <div class="form-row mb-10">
                                <div class="col-12">
                                    <label class="font-14 bold black d-block mb-10">{{ translate('Repeat on') }}</label>
                                    @php
                                        $weekdays = [
                                            0 => translate('Sun'),
                                            1 => translate('Mon'),
                                            2 => translate('Tue'),
                                            3 => translate('Wed'),
                                            4 => translate('Thu'),
                                            5 => translate('Fri'),
                                            6 => translate('Sat'),
                                        ];
                                    @endphp
                                    @foreach ($weekdays as $dayValue => $dayLabel)
                                        <label class="mr-3 mb-2 d-inline-flex align-items-center">
                                            <input type="checkbox" name="recurrence_weekdays[]" value="{{ $dayValue }}"
                                                class="mr-1 recurrence-weekday-checkbox">
                                            {{ $dayLabel }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <p class="text-muted font-13 mb-0">{{ translate('Slots will be created for each selected weekday within the date range (max 90 days).') }}</p>
                        </div>
                        <div id="delivery-slot-rows">
                            <div class="delivery-slot-row border rounded p-3 mb-15">
                                <div class="form-row">
                                    <div class="col-md-3 mb-10">
                                        <label class="font-14 black">{{ translate('Start time') }}</label>
                                        <input type="time" name="slots[0][start_time]" class="theme-input-style" required>
                                    </div>
                                    <div class="col-md-3 mb-10">
                                        <label class="font-14 black">{{ translate('End time') }}</label>
                                        <input type="time" name="slots[0][end_time]" class="theme-input-style" required>
                                    </div>
                                    <div class="col-md-3 mb-10">
                                        <label class="font-14 black">{{ translate('Label') }} ({{ translate('optional') }})</label>
                                        <input type="text" name="slots[0][label]" class="theme-input-style"
                                            placeholder="{{ translate('Morning') }}">
                                    </div>
                                    <div class="col-md-2 mb-10">
                                        <label class="font-14 black">{{ translate('Max orders') }}</label>
                                        <input type="number" name="slots[0][max_orders]" class="theme-input-style" min="1"
                                            placeholder="{{ translate('Unlimited') }}">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end mb-10">
                                        <button type="button" class="btn btn-danger remove-slot-row" disabled>&times;</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn long btn-orange">{{ translate('Create Time Slots') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Existing slots --}}
        <div class="col-12">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-20 flex-wrap">
                        <h4 class="mb-0">{{ translate('Scheduled Time Slots') }}</h4>
                        <form method="GET" action="{{ route('plugin.tlcommercecore.shipping.hub') }}"
                            class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                            <input type="hidden" name="tab" value="time_slots">
                            <input type="date" name="from_date" class="theme-input-style" value="{{ $fromDate }}">
                            <input type="date" name="to_date" class="theme-input-style" value="{{ $toDate }}">
                            <button type="submit" class="btn long">{{ translate('Filter') }}</button>
                        </form>
                    </div>

                    @if ($slots->isEmpty())
                        <p class="text-muted mb-0">{{ translate('No delivery time slots found for this date range.') }}</p>
                    @else
                        @php
                            $shownRecurrenceGroups = [];
                        @endphp
                        @foreach ($groupedSlots as $date => $dateSlots)
                            <div class="d-flex justify-content-between align-items-center mt-20 mb-10 flex-wrap" style="gap: 10px;">
                                <h5 class="mb-0">{{ \Carbon\Carbon::parse($date)->format('l, M j, Y') }}</h5>
                                <div class="dropdown-button">
                                    <a href="#" class="btn long btn-sm" data-toggle="dropdown">
                                        {{ translate('Day actions') }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <form method="POST"
                                            action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.disable.date') }}"
                                            class="d-inline"
                                            onsubmit="return confirm('{{ translate('Disable all time slots on this date?') }}');">
                                            @csrf
                                            <input type="hidden" name="schedule_date" value="{{ $date }}">
                                            <input type="hidden" name="from_date" value="{{ $fromDate }}">
                                            <input type="hidden" name="to_date" value="{{ $toDate }}">
                                            <button type="submit"
                                                class="btn-link border-0 bg-transparent p-0 dropdown-item">{{ translate('Disable all slots') }}</button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.delete.unbooked.date') }}"
                                            class="d-inline"
                                            onsubmit="return confirm('{{ translate('Delete all unbooked time slots on this date?') }}');">
                                            @csrf
                                            <input type="hidden" name="schedule_date" value="{{ $date }}">
                                            <input type="hidden" name="from_date" value="{{ $fromDate }}">
                                            <input type="hidden" name="to_date" value="{{ $toDate }}">
                                            <button type="submit"
                                                class="btn-link border-0 bg-transparent p-0 dropdown-item">{{ translate('Delete unbooked slots') }}</button>
                                        </form>
                                        <a href="#" class="dropdown-item copy-slots-to-date"
                                            data-source-date="{{ $date }}">{{ translate('Copy to another date') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-20">
                                <table class="dh-table">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Label') }}</th>
                                            <th>{{ translate('Start') }}</th>
                                            <th>{{ translate('End') }}</th>
                                            <th>{{ translate('Capacity') }}</th>
                                            <th>{{ translate('Status') }}</th>
                                            <th class="text-right">{{ translate('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dateSlots as $slot)
                                            <tr>
                                            <td>
                                                {{ $slot['label'] ?: '—' }}
                                                @if (!empty($slot['recurrence_group_id']))
                                                    <span class="badge badge-info ml-1">{{ translate('Recurring') }}</span>
                                                @endif
                                                @if (!empty($slot['is_full']))
                                                    <span class="badge badge-danger ml-1">{{ translate('Full') }}</span>
                                                @endif
                                            </td>
                                                <td>{{ $slot['start_time'] }}</td>
                                                <td>{{ $slot['end_time'] }}</td>
                                                <td>
                                                    @if ($slot['max_orders'] !== null)
                                                        {{ $slot['booked_count'] }}/{{ $slot['max_orders'] }}
                                                        @if ($slot['remaining'] !== null)
                                                            <span class="text-muted font-13">({{ $slot['remaining'] }} {{ translate('left') }})</span>
                                                        @endif
                                                    @else
                                                        {{ $slot['booked_count'] }} {{ translate('booked') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.status') }}"
                                                        class="d-inline slot-status-form">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $slot['id'] }}">
                                                        <input type="hidden" name="from_date" value="{{ $fromDate }}">
                                                        <input type="hidden" name="to_date" value="{{ $toDate }}">
                                                        <label class="switch glow primary medium">
                                                            <input type="checkbox" class="slot-status-toggle"
                                                                @checked($slot['status'] == config('settings.general_status.active'))>
                                                            <span class="control"></span>
                                                        </label>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="dropdown-button">
                                                        <a href="#" class="d-flex align-items-center justify-content-end"
                                                            data-toggle="dropdown">
                                                            <div class="menu-icon mr-0">
                                                                <span></span>
                                                                <span></span>
                                                                <span></span>
                                                            </div>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="btn-link edit-delivery-slot"
                                                                data-id="{{ $slot['id'] }}"
                                                                data-schedule-date="{{ $slot['schedule_date'] }}"
                                                                data-start-time="{{ $slot['start_time'] }}"
                                                                data-end-time="{{ $slot['end_time'] }}"
                                                                data-label="{{ $slot['label'] ?? '' }}"
                                                                data-max-orders="{{ $slot['max_orders'] ?? '' }}"
                                                                data-booked-count="{{ $slot['booked_count'] }}">{{ translate('Edit') }}</a>
                                                            @if (!empty($slot['recurrence_group_id']) && !in_array($slot['recurrence_group_id'], $shownRecurrenceGroups))
                                                                @php $shownRecurrenceGroups[] = $slot['recurrence_group_id']; @endphp
                                                                <form method="POST"
                                                                    action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.delete.recurrence') }}"
                                                                    class="d-inline"
                                                                    onsubmit="return confirm('{{ translate('Delete all future unbooked slots in this recurring series? Booked slots will be skipped.') }}');">
                                                                    @csrf
                                                                    <input type="hidden" name="recurrence_group_id"
                                                                        value="{{ $slot['recurrence_group_id'] }}">
                                                                    <input type="hidden" name="from_date" value="{{ $fromDate }}">
                                                                    <input type="hidden" name="to_date" value="{{ $toDate }}">
                                                                    <button type="submit"
                                                                        class="btn-link border-0 bg-transparent p-0">{{ translate('Delete recurring series') }}</button>
                                                                </form>
                                                            @endif
                                                            @if (empty($slot['has_bookings']))
                                                            <form method="POST"
                                                                action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.delete') }}"
                                                                class="d-inline"
                                                                onsubmit="return confirm('{{ translate('Delete this time slot?') }}');">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $slot['id'] }}">
                                                                <input type="hidden" name="from_date" value="{{ $fromDate }}">
                                                                <input type="hidden" name="to_date" value="{{ $toDate }}">
                                                                <button type="submit"
                                                                    class="btn-link border-0 bg-transparent p-0">{{ translate('Delete') }}</button>
                                                            </form>
                                                            @else
                                                                <span class="text-muted font-13 d-block px-3 py-1">{{ translate('Has bookings — disable instead') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Edit modal --}}
    <div class="modal fade" id="edit-delivery-slot-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Edit Time Slot') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.update') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-slot-id">
                        <input type="hidden" name="from_date" value="{{ $fromDate }}">
                        <input type="hidden" name="to_date" value="{{ $toDate }}">
                        <div id="edit-slot-booking-warning" class="alert alert-warning mb-15" style="display: none;">
                            {{ translate('This slot has existing bookings. Changes apply to future orders only; booked orders keep their original delivery window.') }}
                        </div>
                        <div class="form-row mb-15">
                            <div class="col-md-6">
                                <label class="font-14 black">{{ translate('Date') }}</label>
                                <input type="date" name="schedule_date" id="edit-schedule-date" class="theme-input-style"
                                    min="{{ now()->toDateString() }}" required>
                            </div>
                        </div>
                        <div class="form-row mb-15">
                            <div class="col-md-6">
                                <label class="font-14 black">{{ translate('Start time') }}</label>
                                <input type="time" name="start_time" id="edit-start-time" class="theme-input-style" required>
                            </div>
                            <div class="col-md-6">
                                <label class="font-14 black">{{ translate('End time') }}</label>
                                <input type="time" name="end_time" id="edit-end-time" class="theme-input-style" required>
                            </div>
                        </div>
                        <div class="form-row mb-15">
                            <div class="col-md-6">
                                <label class="font-14 black">{{ translate('Label') }}</label>
                                <input type="text" name="label" id="edit-label" class="theme-input-style">
                            </div>
                            <div class="col-md-6">
                                <label class="font-14 black">{{ translate('Max orders') }}</label>
                                <input type="number" name="max_orders" id="edit-max-orders" class="theme-input-style" min="1"
                                    placeholder="{{ translate('Unlimited') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn long" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn long btn-orange">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Copy slots modal --}}
    <div class="modal fade" id="copy-slots-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Copy Time Slots to Another Date') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('plugin.tlcommercecore.delivery.schedule.slots.copy.date') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="source_date" id="copy-source-date">
                        <input type="hidden" name="from_date" value="{{ $fromDate }}">
                        <input type="hidden" name="to_date" value="{{ $toDate }}">
                        <div class="form-group mb-15">
                            <label class="font-14 black">{{ translate('Copy from') }}</label>
                            <input type="text" id="copy-source-date-display" class="theme-input-style" readonly>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-14 black">{{ translate('Copy to date') }}</label>
                            <input type="date" name="target_date" id="copy-target-date" class="theme-input-style"
                                min="{{ now()->toDateString() }}" required>
                        </div>
                        <p class="text-muted font-13 mt-15 mb-0">{{ translate('Overlapping slots on the target date will be skipped.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn long" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn long btn-orange">{{ translate('Copy Slots') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
