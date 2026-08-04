<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugin\TlcommerceCore\Repositories\DeliveryScheduleRepository;

class DeliveryScheduleController extends Controller
{
    protected DeliveryScheduleRepository $delivery_schedule_repository;

    public function __construct(DeliveryScheduleRepository $delivery_schedule_repository)
    {
        $this->delivery_schedule_repository = $delivery_schedule_repository;
    }

    /**
     * Save delivery scheduling feature settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'delivery_scheduling_lead_time' => 'nullable|integer|min:0',
            'delivery_scheduling_lead_time_unit' => 'nullable|in:Days,Hours,Minutes',
            'delivery_scheduling_horizon_days' => 'nullable|integer|min:1|max:90',
        ]);

        if ($this->delivery_schedule_repository->updateSettings($request)) {
            toastNotification('success', translate('Delivery scheduling settings updated successfully'));
        } else {
            toastNotification('error', translate('Failed to update delivery scheduling settings'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', ['tab' => 'time_slots']);
    }

    /**
     * Bulk create time slots for a date range (or single day when from = until).
     */
    public function storeSlots(Request $request)
    {
        $baseRules = [
            'schedule_from' => 'required|date|after_or_equal:today',
            'schedule_until' => 'required|date|after_or_equal:schedule_from',
            'slots' => 'required|array|min:1',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time' => 'required|date_format:H:i',
            'slots.*.label' => 'nullable|string|max:100',
            'slots.*.max_orders' => 'nullable|integer|min:1',
        ];

        if ($request->has('enable_recurrence')) {
            $request->validate(array_merge($baseRules, [
                'recurrence_weekdays' => 'required|array|min:1',
                'recurrence_weekdays.*' => 'integer|between:0,6',
            ]));

            $result = $this->delivery_schedule_repository->bulkStoreRecurringSlots(
                $request->input('schedule_from'),
                $request->input('schedule_until'),
                $request->input('recurrence_weekdays'),
                $request->input('slots')
            );
        } else {
            $request->validate($baseRules);

            $result = $this->delivery_schedule_repository->bulkStoreSlotsForDateRange(
                $request->input('schedule_from'),
                $request->input('schedule_until'),
                $request->input('slots')
            );
        }

        if ($result['success']) {
            $message = translate('Delivery time slots created successfully');
            if (!empty($result['created_count'])) {
                $message .= ' (' . $result['created_count'] . ' ' . translate('created') . ')';
            }
            if (!empty($result['skipped_count'])) {
                $message .= ' — ' . $result['skipped_count'] . ' ' . translate('skipped due to overlap');
            }
            toastNotification('success', $message);
        } else {
            toastNotification('error', $result['message'] ?? translate('Failed to create delivery time slots'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Update a single time slot.
     */
    public function updateSlot(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1',
            'schedule_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'label' => 'nullable|string|max:100',
            'max_orders' => 'nullable|integer|min:1',
        ]);

        $result = $this->delivery_schedule_repository->updateSlot(
            (int) $request->input('id'),
            $request->only(['schedule_date', 'start_time', 'end_time', 'label', 'max_orders'])
        );

        if ($result['success']) {
            toastNotification('success', translate('Delivery time slot updated successfully'));
        } else {
            toastNotification('error', $result['message'] ?? translate('Failed to update delivery time slot'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Delete a time slot.
     */
    public function deleteSlot(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        $result = $this->delivery_schedule_repository->deleteSlot((int) $request->input('id'));

        if ($result['success']) {
            toastNotification('success', translate('Delivery time slot deleted successfully'));
        } else {
            toastNotification('error', $result['message'] ?? translate('Failed to delete delivery time slot'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Delete all future slots in a recurring series.
     */
    public function deleteRecurrenceGroup(Request $request)
    {
        $request->validate([
            'recurrence_group_id' => 'required|string|max:36',
        ]);

        $result = $this->delivery_schedule_repository->deleteRecurrenceGroup(
            $request->input('recurrence_group_id')
        );

        if ($result['success']) {
            $message = translate('Recurring time slots deleted successfully');
            if (!empty($result['deleted_count'])) {
                $message .= ' (' . $result['deleted_count'] . ' ' . translate('deleted') . ')';
            }
            if (!empty($result['skipped_count'])) {
                $message .= ' — ' . $result['skipped_count'] . ' ' . translate('skipped due to existing bookings');
            }
            toastNotification('success', $message);
        } else {
            toastNotification('error', $result['message'] ?? translate('Failed to delete recurring time slots'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Toggle slot active status.
     */
    public function updateSlotStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|min:1',
        ]);

        if ($this->delivery_schedule_repository->updateSlotStatus((int) $request->input('id'))) {
            toastNotification('success', translate('Delivery time slot status updated successfully'));
        } else {
            toastNotification('error', translate('Failed to update delivery time slot status'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Disable all slots on a specific date.
     */
    public function disableSlotsForDate(Request $request)
    {
        $request->validate([
            'schedule_date' => 'required|date',
        ]);

        $count = $this->delivery_schedule_repository->disableSlotsForDate(
            $request->input('schedule_date')
        );

        if ($count > 0) {
            toastNotification('success', translate('Time slots disabled successfully') . ' (' . $count . ')');
        } else {
            toastNotification('error', translate('No active time slots found for this date'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Delete all unbooked slots on a specific date.
     */
    public function deleteUnbookedSlotsForDate(Request $request)
    {
        $request->validate([
            'schedule_date' => 'required|date',
        ]);

        $result = $this->delivery_schedule_repository->deleteUnbookedSlotsForDate(
            $request->input('schedule_date')
        );

        if ($result['success']) {
            $message = translate('Unbooked time slots deleted successfully');
            if (!empty($result['deleted_count'])) {
                $message .= ' (' . $result['deleted_count'] . ' ' . translate('deleted') . ')';
            }
            if (!empty($result['skipped_count'])) {
                $message .= ' — ' . $result['skipped_count'] . ' ' . translate('skipped due to existing bookings');
            }
            toastNotification('success', $message);
        } else {
            toastNotification('error', $result['message'] ?? translate('Failed to delete time slots'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }

    /**
     * Copy all slots from one date to another.
     */
    public function copySlotsToDate(Request $request)
    {
        $request->validate([
            'source_date' => 'required|date',
            'target_date' => 'required|date|after_or_equal:today',
        ]);

        $result = $this->delivery_schedule_repository->copySlotsToDate(
            $request->input('source_date'),
            $request->input('target_date')
        );

        if ($result['success']) {
            $message = translate('Time slots copied successfully');
            if (!empty($result['created_count'])) {
                $message .= ' (' . $result['created_count'] . ' ' . translate('created') . ')';
            }
            if (!empty($result['skipped_count'])) {
                $message .= ' — ' . $result['skipped_count'] . ' ' . translate('skipped due to overlap');
            }
            toastNotification('success', $message);
        } else {
            toastNotification('error', $result['message'] ?? translate('Failed to copy time slots'));
        }

        return redirect()->route('plugin.tlcommercecore.shipping.hub', [
            'tab' => 'time_slots',
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ]);
    }
}
