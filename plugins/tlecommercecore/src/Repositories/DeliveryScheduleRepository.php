<?php

namespace Plugin\TlcommerceCore\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Plugin\TlcommerceCore\Models\DeliveryScheduleSlot;
use Plugin\TlcommerceCore\Models\EcommerceConfig;

class DeliveryScheduleRepository
{
    private const SLOTS_TABLE = 'tl_com_delivery_schedule_slots';

    private const ORDER_SCHEDULES_TABLE = 'tl_com_order_delivery_schedules';

    public function tablesExist(): bool
    {
        return $this->slotsTableExists();
    }

    public function slotsTableExists(): bool
    {
        return Schema::hasTable(self::SLOTS_TABLE);
    }

    public function orderSchedulesTableExists(): bool
    {
        return Schema::hasTable(self::ORDER_SCHEDULES_TABLE);
    }

    public function schedulingTablesExist(): bool
    {
        return $this->slotsTableExists() && $this->orderSchedulesTableExists();
    }

    public function bookingHorizonDays(): int
    {
        $days = (int) (SettingsRepository::getEcommerceSetting('delivery_scheduling_horizon_days') ?: 30);

        return max(1, min(90, $days));
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return [
            'enable_delivery_scheduling' => SettingsRepository::getEcommerceSetting('enable_delivery_scheduling'),
            'delivery_scheduling_lead_time' => SettingsRepository::getEcommerceSetting('delivery_scheduling_lead_time'),
            'delivery_scheduling_lead_time_unit' => SettingsRepository::getEcommerceSetting('delivery_scheduling_lead_time_unit') ?: config('tlecommercecore.time_unit.Hours'),
            'delivery_scheduling_required' => SettingsRepository::getEcommerceSetting('delivery_scheduling_required'),
            'delivery_scheduling_horizon_days' => $this->bookingHorizonDays(),
        ];
    }

    /**
     * @param \Illuminate\Http\Request|array $request
     */
    public function updateSettings($request): bool
    {
        try {
            DB::beginTransaction();

            $settings = [
                'enable_delivery_scheduling' => $request->has('enable_delivery_scheduling')
                    ? config('settings.general_status.active')
                    : config('settings.general_status.in_active'),
                'delivery_scheduling_lead_time' => $request->input('delivery_scheduling_lead_time', 0),
                'delivery_scheduling_lead_time_unit' => $request->input(
                    'delivery_scheduling_lead_time_unit',
                    config('tlecommercecore.time_unit.Hours')
                ),
                'delivery_scheduling_required' => $request->has('delivery_scheduling_required')
                    ? config('settings.general_status.active')
                    : config('settings.general_status.in_active'),
                'delivery_scheduling_horizon_days' => max(1, min(90, (int) $request->input('delivery_scheduling_horizon_days', 30))),
            ];

            foreach ($settings as $key => $value) {
                $config = EcommerceConfig::firstOrCreate(['key_name' => $key]);
                $config->key_value = $value;
                $config->save();
            }

            SettingsRepository::resetEcommerceCache();
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        } catch (\Error $e) {
            DB::rollBack();

            return false;
        }
    }

    /**
     * @return array{dates: array<int, array<string, mixed>>}
     */
    public function listAvailableSlotsForCheckout(): array
    {
        if (!$this->tablesExist()) {
            return ['dates' => []];
        }

        if (SettingsRepository::getEcommerceSetting('enable_delivery_scheduling') != config('settings.general_status.active')) {
            return ['dates' => []];
        }

        $timezone = getGeneralSetting('timezone') ?: config('app.timezone');
        $now = Carbon::now($timezone);
        $leadDeadline = $this->leadTimeDeadline($now);

        $from = $now->toDateString();
        $to = $now->copy()->addDays($this->bookingHorizonDays())->toDateString();

        $grouped = [];

        DeliveryScheduleSlot::query()
            ->where('status', config('settings.general_status.active'))
            ->whereBetween('schedule_date', [$from, $to])
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()
            ->each(function (DeliveryScheduleSlot $slot) use (&$grouped, $leadDeadline, $timezone) {
                if (!$this->isSlotAvailableForCheckout($slot, $leadDeadline, $timezone)) {
                    return;
                }

                $dateKey = $slot->schedule_date->format('Y-m-d');
                $startTime = $this->formatTimeValue($slot->start_time);
                $endTime = $this->formatTimeValue($slot->end_time);

                if (!isset($grouped[$dateKey])) {
                    $grouped[$dateKey] = [
                        'date' => $dateKey,
                        'label' => $slot->schedule_date->format('l, M j, Y'),
                        'slots' => [],
                    ];
                }

                $grouped[$dateKey]['slots'][] = [
                    'id' => $slot->id,
                    'schedule_date' => $dateKey,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'label' => $slot->label,
                    'display' => trim(($slot->label ? $slot->label . ' · ' : '') . $startTime . ' – ' . $endTime),
                ];
            });

        return ['dates' => array_values($grouped)];
    }

    /**
     * Validate checkout delivery schedule fields.
     *
     * @return array{success: bool, message?: string}
     */
    public function validateCheckoutSchedule($request): array
    {
        if (SettingsRepository::getEcommerceSetting('enable_delivery_scheduling') != config('settings.general_status.active')) {
            return ['success' => true];
        }

        if (!$this->schedulingTablesExist()) {
            return ['success' => true];
        }

        $isHomeDelivery = !$request->has('pickup_point');
        if (!$isHomeDelivery) {
            return ['success' => true];
        }

        $mode = $request->input('delivery_schedule_mode');
        $slotId = (int) $request->input('delivery_schedule_slot_id');
        $required = SettingsRepository::getEcommerceSetting('delivery_scheduling_required') == config('settings.general_status.active');

        if ($required || $mode === 'scheduled') {
            if ($mode !== 'scheduled' || $slotId <= 0) {
                return ['success' => false, 'message' => translate('Please select a delivery time slot.')];
            }

            if (!$this->findAvailableSlotForCheckout($slotId)) {
                return ['success' => false, 'message' => translate('Selected delivery time slot is no longer available.')];
            }

            return ['success' => true];
        }

        if (empty($mode)) {
            return ['success' => false, 'message' => translate('Please select a delivery time option.')];
        }

        if ($mode === 'scheduled' && $slotId <= 0) {
            return ['success' => false, 'message' => translate('Please select a delivery time slot.')];
        }

        return ['success' => true];
    }

    /**
     * Attach a scheduled delivery slot snapshot to an order.
     *
     * @return array{success: bool, message?: string}
     */
    public function attachScheduleToOrder(int $orderId, int $slotId): array
    {
        if (!$this->schedulingTablesExist()) {
            return ['success' => true];
        }

        $slot = $this->findAvailableSlotForCheckout($slotId);

        if (!$slot) {
            return ['success' => false, 'message' => translate('Selected delivery time slot is no longer available.')];
        }

        DB::table(self::ORDER_SCHEDULES_TABLE)->insert([
            'order_id' => $orderId,
            'slot_id' => $slot->id,
            'schedule_date' => $slot->schedule_date->format('Y-m-d'),
            'start_time' => $this->normalizeTime($slot->start_time),
            'end_time' => $this->normalizeTime($slot->end_time),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['success' => true];
    }

    /**
     * Read the delivery schedule snapshot for an order.
     *
     * @return array<string, mixed>|null
     */
    public function getScheduleForOrder(int $orderId): ?array
    {
        if (!$this->orderSchedulesTableExists()) {
            return null;
        }

        $row = DB::table(self::ORDER_SCHEDULES_TABLE)
            ->where('order_id', $orderId)
            ->first();

        if (!$row) {
            return null;
        }

        return $this->mapOrderScheduleRow($row);
    }

    /**
     * @param array<int> $orderIds
     * @return array<int, array<string, mixed>>
     */
    public function getSchedulesForOrders(array $orderIds): array
    {
        if (!$this->orderSchedulesTableExists() || empty($orderIds)) {
            return [];
        }

        return DB::table(self::ORDER_SCHEDULES_TABLE)
            ->whereIn('order_id', $orderIds)
            ->get()
            ->mapWithKeys(fn ($row) => [(int) $row->order_id => $this->mapOrderScheduleRow($row)])
            ->all();
    }

    public function findAvailableSlotForCheckout(int $slotId): ?DeliveryScheduleSlot
    {
        if (!$this->tablesExist() || $slotId <= 0) {
            return null;
        }

        $slot = DeliveryScheduleSlot::query()
            ->where('id', $slotId)
            ->where('status', config('settings.general_status.active'))
            ->first();

        if (!$slot) {
            return null;
        }

        $timezone = getGeneralSetting('timezone') ?: config('app.timezone');
        $leadDeadline = $this->leadTimeDeadline(Carbon::now($timezone));

        if (!$this->isSlotAvailableForCheckout($slot, $leadDeadline, $timezone)) {
            return null;
        }

        return $slot;
    }

    private function isSlotAvailableForCheckout(DeliveryScheduleSlot $slot, Carbon $leadDeadline, string $timezone): bool
    {
        $slotStart = Carbon::parse(
            $slot->schedule_date->format('Y-m-d') . ' ' . $this->normalizeTime($slot->start_time),
            $timezone
        );

        if ($slotStart->lte($leadDeadline)) {
            return false;
        }

        if ($slot->max_orders !== null && $this->bookedCount($slot->id) >= (int) $slot->max_orders) {
            return false;
        }

        return true;
    }

    private function leadTimeDeadline(Carbon $now): Carbon
    {
        $time = (int) (SettingsRepository::getEcommerceSetting('delivery_scheduling_lead_time') ?: 0);
        $unit = SettingsRepository::getEcommerceSetting('delivery_scheduling_lead_time_unit') ?: config('tlecommercecore.time_unit.Hours');

        if ($unit == config('tlecommercecore.time_unit.Days')) {
            return $now->copy()->addDays($time);
        }

        if ($unit == config('tlecommercecore.time_unit.Minutes')) {
            return $now->copy()->addMinutes($time);
        }

        return $now->copy()->addHours($time);
    }

    public function listSlots(?string $fromDate = null, ?string $toDate = null): Collection
    {
        if (!$this->tablesExist()) {
            return collect();
        }

        $from = $fromDate ?: Carbon::today()->toDateString();
        $to = $toDate ?: Carbon::today()->addDays($this->bookingHorizonDays())->toDateString();

        return DeliveryScheduleSlot::query()
            ->whereBetween('schedule_date', [$from, $to])
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get()
            ->map(function (DeliveryScheduleSlot $slot) {
                $bookedCount = $this->bookedCount($slot->id);
                $maxOrders = $slot->max_orders !== null ? (int) $slot->max_orders : null;
                $isFull = $maxOrders !== null && $bookedCount >= $maxOrders;

                return [
                    'id' => $slot->id,
                    'schedule_date' => $slot->schedule_date->format('Y-m-d'),
                    'start_time' => $this->formatTimeValue($slot->start_time),
                    'end_time' => $this->formatTimeValue($slot->end_time),
                    'max_orders' => $slot->max_orders,
                    'booked_count' => $bookedCount,
                    'remaining' => $maxOrders !== null ? max(0, $maxOrders - $bookedCount) : null,
                    'is_full' => $isFull,
                    'has_bookings' => $bookedCount > 0,
                    'status' => (int) $slot->status,
                    'label' => $slot->label,
                    'recurrence_group_id' => $slot->recurrence_group_id,
                ];
            });
    }

    /**
     * @param array<int, array<string, mixed>> $slots
     * @return array{success: bool, message?: string, created_count?: int, skipped_count?: int}
     */
    public function bulkStoreSlotsForDateRange(string $fromDate, string $untilDate, array $slots): array
    {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        $from = Carbon::parse($fromDate)->startOfDay();
        $until = Carbon::parse($untilDate)->startOfDay();

        if ($from->lt(Carbon::today())) {
            return ['success' => false, 'message' => translate('Cannot create slots for a past date.')];
        }

        if ($until->lt($from)) {
            return ['success' => false, 'message' => translate('End date must be on or after start date.')];
        }

        if ($from->diffInDays($until) > 90) {
            return ['success' => false, 'message' => translate('Date range cannot exceed 90 days.')];
        }

        foreach ($slots as $index => $slot) {
            $startTime = $this->normalizeTime($slot['start_time'] ?? '');
            $endTime = $this->normalizeTime($slot['end_time'] ?? '');
            if ($startTime >= $endTime) {
                return [
                    'success' => false,
                    'message' => translate('End time must be after start time for slot') . ' #' . ($index + 1),
                ];
            }
        }

        $createdCount = 0;
        $skippedCount = 0;

        try {
            DB::beginTransaction();

            for ($date = $from->copy(); $date->lte($until); $date->addDay()) {
                $scheduleDate = $date->toDateString();
                $pendingSlots = [];

                foreach ($slots as $slot) {
                    $startTime = $this->normalizeTime($slot['start_time'] ?? '');
                    $endTime = $this->normalizeTime($slot['end_time'] ?? '');

                    if ($this->hasOverlap($scheduleDate, $startTime, $endTime)
                        || $this->hasPendingOverlap($pendingSlots, $startTime, $endTime)) {
                        $skippedCount++;
                        continue;
                    }

                    $pendingSlots[] = [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];

                    DeliveryScheduleSlot::create([
                        'schedule_date' => $scheduleDate,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'max_orders' => $this->nullableMaxOrders($slot['max_orders'] ?? null),
                        'status' => config('settings.general_status.active'),
                        'label' => isset($slot['label']) ? trim((string) $slot['label']) ?: null : null,
                    ]);
                    $createdCount++;
                }
            }

            DB::commit();

            if ($createdCount === 0) {
                return [
                    'success' => false,
                    'message' => translate('No delivery time slots were created. Existing slots may overlap.'),
                    'created_count' => 0,
                    'skipped_count' => $skippedCount,
                ];
            }

            return [
                'success' => true,
                'created_count' => $createdCount,
                'skipped_count' => $skippedCount,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => translate('Failed to create delivery time slots.')];
        }
    }

    /**
     * @param array<int, array<string, mixed>> $slots
     * @return array{success: bool, message?: string, created_count?: int, skipped_count?: int, recurrence_group_id?: string}
     */
    public function bulkStoreRecurringSlots(
        string $fromDate,
        string $untilDate,
        array $weekdays,
        array $slots,
        ?string $groupId = null
    ): array {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        $from = Carbon::parse($fromDate)->startOfDay();
        $until = Carbon::parse($untilDate)->startOfDay();

        if ($from->lt(Carbon::today())) {
            return ['success' => false, 'message' => translate('Cannot create slots for a past date.')];
        }

        if ($until->lt($from)) {
            return ['success' => false, 'message' => translate('End date must be on or after start date.')];
        }

        if ($from->diffInDays($until) > 90) {
            return ['success' => false, 'message' => translate('Recurrence range cannot exceed 90 days.')];
        }

        $weekdayLookup = array_flip(array_map('intval', $weekdays));
        if (empty($weekdayLookup)) {
            return ['success' => false, 'message' => translate('Please select at least one weekday.')];
        }

        foreach ($slots as $index => $slot) {
            $startTime = $this->normalizeTime($slot['start_time'] ?? '');
            $endTime = $this->normalizeTime($slot['end_time'] ?? '');
            if ($startTime >= $endTime) {
                return [
                    'success' => false,
                    'message' => translate('End time must be after start time for slot') . ' #' . ($index + 1),
                ];
            }
        }

        $recurrenceGroupId = $groupId ?: (string) \Illuminate\Support\Str::uuid();
        $createdCount = 0;
        $skippedCount = 0;

        try {
            DB::beginTransaction();

            for ($date = $from->copy(); $date->lte($until); $date->addDay()) {
                if (!isset($weekdayLookup[$date->dayOfWeek])) {
                    continue;
                }

                $scheduleDate = $date->toDateString();
                $pendingSlots = [];

                foreach ($slots as $slot) {
                    $startTime = $this->normalizeTime($slot['start_time'] ?? '');
                    $endTime = $this->normalizeTime($slot['end_time'] ?? '');

                    if ($this->hasOverlap($scheduleDate, $startTime, $endTime)
                        || $this->hasPendingOverlap($pendingSlots, $startTime, $endTime)) {
                        $skippedCount++;
                        continue;
                    }

                    $pendingSlots[] = [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];

                    DeliveryScheduleSlot::create([
                        'schedule_date' => $scheduleDate,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'max_orders' => $this->nullableMaxOrders($slot['max_orders'] ?? null),
                        'status' => config('settings.general_status.active'),
                        'label' => isset($slot['label']) ? trim((string) $slot['label']) ?: null : null,
                        'recurrence_group_id' => $recurrenceGroupId,
                    ]);
                    $createdCount++;
                }
            }

            DB::commit();

            if ($createdCount === 0) {
                return [
                    'success' => false,
                    'message' => translate('No delivery time slots were created. Existing slots may overlap.'),
                    'created_count' => 0,
                    'skipped_count' => $skippedCount,
                ];
            }

            return [
                'success' => true,
                'created_count' => $createdCount,
                'skipped_count' => $skippedCount,
                'recurrence_group_id' => $recurrenceGroupId,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => translate('Failed to create recurring delivery time slots.')];
        }
    }

    /**
     * @param array<int, array<string, mixed>> $slots
     * @return array{success: bool, message?: string}
     */
    public function bulkStoreSlots(string $scheduleDate, array $slots, ?string $recurrenceGroupId = null): array
    {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        if (Carbon::parse($scheduleDate)->lt(Carbon::today())) {
            return ['success' => false, 'message' => translate('Cannot create slots for a past date.')];
        }

        try {
            DB::beginTransaction();

            $pendingSlots = [];

            foreach ($slots as $index => $slot) {
                $startTime = $this->normalizeTime($slot['start_time'] ?? '');
                $endTime = $this->normalizeTime($slot['end_time'] ?? '');

                if ($startTime >= $endTime) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => translate('End time must be after start time for slot') . ' #' . ($index + 1),
                    ];
                }

                if ($this->hasOverlap($scheduleDate, $startTime, $endTime)
                    || $this->hasPendingOverlap($pendingSlots, $startTime, $endTime)) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => translate('Time slot overlaps with an existing slot on this date.') . ' #' . ($index + 1),
                    ];
                }

                $pendingSlots[] = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];

                DeliveryScheduleSlot::create([
                    'schedule_date' => $scheduleDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'max_orders' => $this->nullableMaxOrders($slot['max_orders'] ?? null),
                    'status' => config('settings.general_status.active'),
                    'label' => isset($slot['label']) ? trim((string) $slot['label']) ?: null : null,
                    'recurrence_group_id' => $recurrenceGroupId,
                ]);
            }

            DB::commit();

            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => translate('Failed to create delivery time slots.')];
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return array{success: bool, message?: string}
     */
    public function updateSlot(int $id, array $data): array
    {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        $slot = DeliveryScheduleSlot::find($id);

        if (!$slot) {
            return ['success' => false, 'message' => translate('Time slot not found.')];
        }

        $scheduleDate = $data['schedule_date'] ?? $slot->schedule_date->format('Y-m-d');
        $startTime = $this->normalizeTime($data['start_time'] ?? $slot->start_time);
        $endTime = $this->normalizeTime($data['end_time'] ?? $slot->end_time);

        if (Carbon::parse($scheduleDate)->lt(Carbon::today())) {
            return ['success' => false, 'message' => translate('Cannot set slot to a past date.')];
        }

        if ($startTime >= $endTime) {
            return ['success' => false, 'message' => translate('End time must be after start time.')];
        }

        if ($this->hasOverlap($scheduleDate, $startTime, $endTime, $id)) {
            return ['success' => false, 'message' => translate('Time slot overlaps with an existing slot on this date.')];
        }

        $slot->schedule_date = $scheduleDate;
        $slot->start_time = $startTime;
        $slot->end_time = $endTime;
        $slot->max_orders = $this->nullableMaxOrders($data['max_orders'] ?? $slot->max_orders);
        $slot->label = isset($data['label']) ? trim((string) $data['label']) ?: null : $slot->label;
        $slot->save();

        return ['success' => true];
    }

    /**
     * @return array{success: bool, message?: string}
     */
    public function deleteSlot(int $id): array
    {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        $slot = DeliveryScheduleSlot::find($id);

        if (!$slot) {
            return ['success' => false, 'message' => translate('Time slot not found.')];
        }

        if ($this->bookedCount($id) > 0) {
            return [
                'success' => false,
                'message' => translate('Cannot delete a time slot with existing bookings. Disable it instead.'),
            ];
        }

        return $slot->delete()
            ? ['success' => true]
            : ['success' => false, 'message' => translate('Failed to delete delivery time slot.')];
    }

    /**
     * @return array{success: bool, message?: string, deleted_count?: int, skipped_count?: int}
     */
    public function deleteRecurrenceGroup(string $groupId): array
    {
        if (!$this->tablesExist() || $groupId === '') {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        $slots = DeliveryScheduleSlot::query()
            ->where('recurrence_group_id', $groupId)
            ->where('schedule_date', '>=', Carbon::today()->toDateString())
            ->get();

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($slots as $slot) {
            if ($this->bookedCount($slot->id) > 0) {
                $skippedCount++;
                continue;
            }

            if ($slot->delete()) {
                $deletedCount++;
            }
        }

        if ($deletedCount === 0 && $skippedCount === 0) {
            return ['success' => false, 'message' => translate('Failed to delete recurring time slots.')];
        }

        if ($deletedCount === 0 && $skippedCount > 0) {
            return [
                'success' => false,
                'message' => translate('All slots in this series have bookings and cannot be deleted. Disable them instead.'),
                'deleted_count' => 0,
                'skipped_count' => $skippedCount,
            ];
        }

        return [
            'success' => true,
            'deleted_count' => $deletedCount,
            'skipped_count' => $skippedCount,
        ];
    }

    public function disableSlotsForDate(string $scheduleDate): int
    {
        if (!$this->tablesExist()) {
            return 0;
        }

        return DeliveryScheduleSlot::query()
            ->where('schedule_date', $scheduleDate)
            ->where('status', config('settings.general_status.active'))
            ->update(['status' => config('settings.general_status.in_active')]);
    }

    /**
     * @return array{success: bool, message?: string, deleted_count?: int, skipped_count?: int}
     */
    public function deleteUnbookedSlotsForDate(string $scheduleDate): array
    {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        $slots = DeliveryScheduleSlot::query()
            ->where('schedule_date', $scheduleDate)
            ->get();

        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($slots as $slot) {
            if ($this->bookedCount($slot->id) > 0) {
                $skippedCount++;
                continue;
            }

            if ($slot->delete()) {
                $deletedCount++;
            }
        }

        if ($deletedCount === 0 && $skippedCount === 0) {
            return ['success' => false, 'message' => translate('No time slots found for this date.')];
        }

        if ($deletedCount === 0 && $skippedCount > 0) {
            return [
                'success' => false,
                'message' => translate('All slots on this date have bookings and cannot be deleted.'),
                'deleted_count' => 0,
                'skipped_count' => $skippedCount,
            ];
        }

        return [
            'success' => true,
            'deleted_count' => $deletedCount,
            'skipped_count' => $skippedCount,
        ];
    }

    /**
     * @return array{success: bool, message?: string, created_count?: int, skipped_count?: int}
     */
    public function copySlotsToDate(string $sourceDate, string $targetDate): array
    {
        if (!$this->tablesExist()) {
            return ['success' => false, 'message' => translate('Delivery scheduling tables are not available.')];
        }

        if (Carbon::parse($targetDate)->lt(Carbon::today())) {
            return ['success' => false, 'message' => translate('Cannot copy slots to a past date.')];
        }

        $sourceSlots = DeliveryScheduleSlot::query()
            ->where('schedule_date', $sourceDate)
            ->orderBy('start_time')
            ->get();

        if ($sourceSlots->isEmpty()) {
            return ['success' => false, 'message' => translate('No time slots found on the source date.')];
        }

        $createdCount = 0;
        $skippedCount = 0;
        $pendingSlots = [];

        try {
            DB::beginTransaction();

            foreach ($sourceSlots as $slot) {
                $startTime = $this->normalizeTime($slot->start_time);
                $endTime = $this->normalizeTime($slot->end_time);

                if ($this->hasOverlap($targetDate, $startTime, $endTime)
                    || $this->hasPendingOverlap($pendingSlots, $startTime, $endTime)) {
                    $skippedCount++;
                    continue;
                }

                $pendingSlots[] = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];

                DeliveryScheduleSlot::create([
                    'schedule_date' => $targetDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'max_orders' => $slot->max_orders,
                    'status' => $slot->status,
                    'label' => $slot->label,
                ]);
                $createdCount++;
            }

            DB::commit();

            if ($createdCount === 0) {
                return [
                    'success' => false,
                    'message' => translate('No slots were copied. Target date may already have overlapping slots.'),
                    'created_count' => 0,
                    'skipped_count' => $skippedCount,
                ];
            }

            return [
                'success' => true,
                'created_count' => $createdCount,
                'skipped_count' => $skippedCount,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['success' => false, 'message' => translate('Failed to copy delivery time slots.')];
        }
    }

    public function updateSlotStatus(int $id): bool
    {
        if (!$this->tablesExist()) {
            return false;
        }

        $slot = DeliveryScheduleSlot::find($id);

        if (!$slot) {
            return false;
        }

        $slot->status = $slot->status == config('settings.general_status.active')
            ? config('settings.general_status.in_active')
            : config('settings.general_status.active');
        $slot->save();

        return true;
    }

    public function hasOverlap(string $scheduleDate, string $startTime, string $endTime, ?int $excludeId = null): bool
    {
        if (!$this->tablesExist()) {
            return false;
        }

        $query = DeliveryScheduleSlot::query()
            ->where('schedule_date', $scheduleDate)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function bookedCount(int $slotId): int
    {
        if (!$this->orderSchedulesTableExists()) {
            return 0;
        }

        return (int) DB::table(self::ORDER_SCHEDULES_TABLE)
            ->where('slot_id', $slotId)
            ->count();
    }

    private function nullableMaxOrders($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $max = (int) $value;

        return $max > 0 ? $max : null;
    }

    private function normalizeTime(string $time): string
    {
        return Carbon::parse($time)->format('H:i:s');
    }

    private function formatTimeValue($time): string
    {
        return Carbon::parse($time)->format('H:i');
    }

    private function formatTimeDisplay($time): string
    {
        $parsed = Carbon::parse($time);
        $format = ((int) $parsed->format('i')) === 0 ? 'ga' : 'g:ia';

        return strtolower($parsed->format($format));
    }

    /**
     * @return array<string, mixed>
     */
    private function mapOrderScheduleRow(object $row): array
    {
        $startDisplay = $this->formatTimeDisplay($row->start_time);
        $endDisplay = $this->formatTimeDisplay($row->end_time);
        $dateLabel = Carbon::parse($row->schedule_date)->format('l, M j, Y');

        return [
            'mode' => 'scheduled',
            'slot_id' => (int) $row->slot_id,
            'schedule_date' => Carbon::parse($row->schedule_date)->format('Y-m-d'),
            'start_time' => $this->formatTimeValue($row->start_time),
            'end_time' => $this->formatTimeValue($row->end_time),
            'time_display' => $startDisplay . ' – ' . $endDisplay,
            'display' => $dateLabel . ' · ' . $startDisplay . ' – ' . $endDisplay,
        ];
    }

    /**
     * @param array<int, array{start_time: string, end_time: string}> $pendingSlots
     */
    private function hasPendingOverlap(array $pendingSlots, string $startTime, string $endTime): bool
    {
        foreach ($pendingSlots as $pending) {
            if ($pending['start_time'] < $endTime && $pending['end_time'] > $startTime) {
                return true;
            }
        }

        return false;
    }
}
