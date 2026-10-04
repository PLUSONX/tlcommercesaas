<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Plugin\TlcommerceCore\Models\StudioBooking;
use Plugin\TlcommerceCore\Models\StudioBookingSlot;

/**
 * Studio "Book Now" (hourly studio booking).
 * Completely separate from Deliver Now / delivery scheduling.
 */
class StudioBookingController extends Controller
{
    private const ENABLE_KEY = 'enable_booking_now';
    private const ENABLED = 1;
    private const DISABLED = 2;
    private const SLOT_MINUTES = 60;
    private const HOLD_MINUTES = 15;
    private const MAX_RANGE_DAYS = 366;

    /* ------------------------------------------------------------------
     |  ADMIN
     * ------------------------------------------------------------------ */

    public function index(Request $request)
    {
        $date = now()->toDateString();

        if ($request->filled('date')) {
            try {
                $date = Carbon::createFromFormat('!Y-m-d', (string) $request->input('date'))->toDateString();
            } catch (\Throwable $e) {
                $date = now()->toDateString();
            }
        }

        $slots = StudioBookingSlot::whereDate('schedule_date', $date)
            ->orderBy('start_time')
            ->get();

        // Attach booking state (held / confirmed) to every slot for the admin list.
        $bookings = $this->activeBookings()->whereDate('schedule_date', $date)->get();

        $slots->each(function (StudioBookingSlot $slot) use ($bookings) {
            $match = $bookings->first(function ($booking) use ($slot) {
                return $slot->start_time < $booking->end_time && $slot->end_time > $booking->start_time;
            });
            $slot->setAttribute('booking_state', $match ? $match->status : null);
        });

        // Overview of every generated date (so the admin can verify a date range was created).
        $overview = StudioBookingSlot::where('schedule_date', '>=', now()->toDateString())
            ->selectRaw('schedule_date, COUNT(*) as total_slots, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as available_slots')
            ->groupBy('schedule_date')
            ->orderBy('schedule_date')
            ->limit(120)
            ->get();

        return view('plugin/tlecommercecore::shipping.studio-booking.index', [
            'enabled' => $this->isEnabled(),
            'selectedDate' => $date,
            'slots' => $slots,
            'overview' => $overview,
        ]);
    }

    public function updateSettings(Request $request)
    {
        try {
            DB::table('tl_com_ecommerce_settings')->updateOrInsert(
                ['key_name' => self::ENABLE_KEY],
                [
                    'key_value' => $request->boolean('enabled') ? self::ENABLED : self::DISABLED,
                    'updated_at' => now(),
                ]
            );

            return redirect()
                ->route('plugin.tlcommercecore.shipping.studio.booking')
                ->with('success', translate('Studio booking settings updated successfully'));
        } catch (\Throwable $e) {
            Log::error('Studio booking settings update failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('plugin.tlcommercecore.shipping.studio.booking')
                ->with('error', translate('Studio booking settings could not be saved'));
        }
    }

    public function generateSlots(Request $request)
    {
        $data = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'opening_time' => ['required', 'date_format:H:i,H:i:s'],
            'closing_time' => ['required', 'date_format:H:i,H:i:s'],
            'slot_minutes' => ['nullable', 'integer', 'in:60'],
        ]);

        $startDate = Carbon::createFromFormat('!Y-m-d', $data['start_date']);
        $endDate = Carbon::createFromFormat('!Y-m-d', $data['end_date'] ?? $data['start_date']);

        if ($startDate->lt(now()->startOfDay())) {
            return redirect()->back()
                ->withInput()
                ->with('error', translate('Booking start date cannot be in the past'));
        }

        if ($endDate->lt($startDate)) {
            return redirect()->back()
                ->withInput()
                ->with('error', translate('End date must be on or after the start date'));
        }

        if ($startDate->diffInDays($endDate) > self::MAX_RANGE_DAYS) {
            return redirect()->back()
                ->withInput()
                ->with('error', translate('Date range is too long. Please generate at most one year at a time'));
        }

        $opening = Carbon::createFromFormat('!H:i', substr($data['opening_time'], 0, 5));
        $closing = Carbon::createFromFormat('!H:i', substr($data['closing_time'], 0, 5));

        if ($closing->lessThanOrEqualTo($opening)) {
            return redirect()->back()
                ->withInput()
                ->with('error', translate('Closing time must be after opening time'));
        }

        $slotMinutes = (int) ($data['slot_minutes'] ?? self::SLOT_MINUTES);
        $created = 0;
        $days = 0;

        try {
            DB::transaction(function () use ($startDate, $endDate, $opening, $closing, $slotMinutes, &$created, &$days) {
                $date = $startDate->copy();

                while ($date->lessThanOrEqualTo($endDate)) {
                    $dateKey = $date->toDateString();
                    $cursor = $opening->copy();
                    $generatedKeys = [];

                    while ($cursor->copy()->addMinutes($slotMinutes)->lessThanOrEqualTo($closing)) {
                        $start = $cursor->format('H:i:s');
                        $end = $cursor->copy()->addMinutes($slotMinutes)->format('H:i:s');
                        $generatedKeys[$start . '|' . $end] = true;

                        $slot = StudioBookingSlot::firstOrCreate(
                            [
                                'schedule_date' => $dateKey,
                                'start_time' => $start,
                                'end_time' => $end,
                            ],
                            ['status' => 1]
                        );

                        if ($slot->wasRecentlyCreated) {
                            $created++;
                        } elseif ((int) $slot->status !== 1) {
                            $slot->status = 1;
                            $slot->save();
                        }

                        $cursor->addMinutes($slotMinutes);
                    }

                    // Hours that already exist on this date but are outside the new range get disabled
                    // (unless somebody holds / has booked them).
                    StudioBookingSlot::whereDate('schedule_date', $dateKey)
                        ->get()
                        ->each(function (StudioBookingSlot $slot) use ($generatedKeys, $dateKey) {
                            $key = $slot->start_time . '|' . $slot->end_time;

                            if (isset($generatedKeys[$key])) {
                                return;
                            }

                            if ((int) $slot->status === 1
                                && !$this->hasBlockingBooking($dateKey, $slot->start_time, $slot->end_time)) {
                                $slot->status = 0;
                                $slot->save();
                            }
                        });

                    $days++;
                    $date->addDay();
                }
            });
        } catch (\Throwable $e) {
            Log::error('Studio booking slot generation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', translate('Studio hours could not be generated. Please check that the studio booking tables are installed'));
        }

        return redirect()
            ->route('plugin.tlcommercecore.shipping.studio.booking', ['date' => $startDate->toDateString()])
            ->with('success', translate('Hourly studio slots generated successfully for the selected date range')
                . ' (' . $days . ' ' . translate('days') . ', ' . $created . ' ' . translate('new hours') . ')');
    }

    public function updateSlotStatus(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        $slot = StudioBookingSlot::findOrFail($data['id']);

        if (!$data['status'] && $this->hasBlockingBooking(
            $slot->schedule_date->format('Y-m-d'),
            $slot->start_time,
            $slot->end_time
        )) {
            return redirect()->back()->with('error', translate('This hour has an active booking and cannot be disabled'));
        }

        $slot->status = $data['status'] ? 1 : 0;
        $slot->save();

        return redirect()->back()->with('success', translate('Studio hour updated successfully'));
    }

    public function deleteSlot(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $slot = StudioBookingSlot::findOrFail($data['id']);

        if ($this->hasBlockingBooking($slot->schedule_date->format('Y-m-d'), $slot->start_time, $slot->end_time)) {
            return redirect()->back()->with(
                'error',
                translate('This hour has an active booking and cannot be deleted')
            );
        }

        $slot->delete();

        return redirect()->back()->with('success', translate('Studio hour deleted successfully'));
    }

    /* ------------------------------------------------------------------
     |  PUBLIC API (storefront)
     * ------------------------------------------------------------------ */

    public function config(): JsonResponse
    {
        $enabled = $this->isEnabled();

        // Deliver Now flags are returned here too because the checkout component
        // (DeliveryShipping.vue) reads them from this same endpoint.
        $deliverNow = (int) (
            DB::table('tl_com_ecommerce_settings')
                ->where('key_name', 'enable_deliver_now')
                ->value('key_value') ?? 1
        ) === 1;

        return response()->json([
            'success' => true,
            'enabled' => $enabled,
            'booking_now_enabled' => $enabled,
            'deliver_now_enabled' => $deliverNow,
            'delivery_scheduling_enabled' => $deliverNow,
            'slot_minutes' => self::SLOT_MINUTES,
        ]);
    }

    public function availableSlots(Request $request): JsonResponse
    {
        if (!$this->isEnabled()) {
            return response()->json([
                'success' => true,
                'enabled' => false,
                'data' => ['dates' => []],
                'current_booking' => null,
            ]);
        }

        $data = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
            'booking_token' => ['nullable', 'string', 'max:300'],
        ]);

        // The "token" is the customer's own selection (no server hold exists until checkout).
        $bookingToken = $data['booking_token'] ?? null;
        $selection = $this->decodeSelection($bookingToken);
        $currentBooking = ($selection && $this->selectionIsAvailable($selection))
            ? $this->selectionPayload($selection['date'], $selection['start'], $selection['end'])
            : null;

        $start = !empty($data['start_date'])
            ? Carbon::createFromFormat('!Y-m-d', $data['start_date'])
            : now()->startOfDay();
        $end = !empty($data['end_date'])
            ? Carbon::createFromFormat('!Y-m-d', $data['end_date'])
            : $start->copy()->addDays(60);

        if ($end->lt($start)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking date range.',
            ], 422);
        }

        $slots = StudioBookingSlot::whereBetween('schedule_date', [$start->toDateString(), $end->toDateString()])
            ->where('status', 1)
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->get();

        // One query for all active bookings in the range (no N+1).
        $bookings = $this->activeBookings()
            ->whereBetween('schedule_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->groupBy(fn ($b) => $b->schedule_date->format('Y-m-d'));

        $today = now()->toDateString();
        $nowTime = now()->format('H:i:s');
        $dates = [];

        foreach ($slots->groupBy(fn ($slot) => $slot->schedule_date->format('Y-m-d')) as $dateKey => $dateSlots) {
            $dayBookings = $bookings->get($dateKey, collect());

            $visibleSlots = $dateSlots->filter(function (StudioBookingSlot $slot) use ($dateKey, $today, $nowTime, $dayBookings) {
                // Past hours of today are never offered.
                if ($dateKey === $today && $slot->start_time <= $nowTime) {
                    return false;
                }

                // Only hours that belong to a placed (confirmed) order are hidden.
                return !$dayBookings->contains(function ($booking) use ($slot) {
                    return $slot->start_time < $booking->end_time && $slot->end_time > $booking->start_time;
                });
            })->values();

            if ($visibleSlots->isEmpty()) {
                continue;
            }

            $dates[] = [
                'date' => $dateKey,
                'label' => Carbon::createFromFormat('!Y-m-d', $dateKey)->format('D, M j, Y'),
                'slots' => $visibleSlots->map(function (StudioBookingSlot $slot) {
                    return [
                        'id' => $slot->id,
                        'schedule_date' => $slot->schedule_date->format('Y-m-d'),
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time,
                        'label' => $this->formatSlot($slot->start_time, $slot->end_time),
                        'display' => $this->formatSlot($slot->start_time, $slot->end_time),
                    ];
                })->values()->all(),
            ];
        }

        return response()->json([
            'success' => true,
            'enabled' => true,
            'data' => ['dates' => $dates],
            'current_booking' => $currentBooking,
        ]);
    }

    /**
     * "Hold" is now a SELECTION, exactly like Deliver Now: it validates the hours and
     * returns them, but reserves NOTHING. Hours are reserved only when the order is
     * placed (see attachBookingToOrder).
     */
    public function hold(Request $request): JsonResponse
    {
        if (!$this->isEnabled()) {
            return response()->json(['success' => false, 'message' => 'Studio booking is disabled.'], 422);
        }

        $data = $request->validate([
            'schedule_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'booking_token' => ['nullable', 'string', 'max:300'],
        ]);

        $selection = ['date' => $data['schedule_date'], 'start' => $data['start_time'], 'end' => $data['end_time']];

        if ($selection['end'] <= $selection['start']) {
            return response()->json(['success' => false, 'message' => 'Invalid studio time range.'], 409);
        }

        if ($selection['date'] < now()->toDateString()
            || ($selection['date'] === now()->toDateString() && $selection['start'] <= now()->format('H:i:s'))) {
            return response()->json(['success' => false, 'message' => 'Selected studio hours are in the past.'], 409);
        }

        if (!$this->selectionIsAvailable($selection)) {
            return response()->json([
                'success' => false,
                'message' => 'Selected studio hours are no longer available. Please choose consecutive one-hour slots.',
            ], 409);
        }

        return response()->json([
            'success' => true,
            'data' => $this->selectionPayload($selection['date'], $selection['start'], $selection['end']),
        ]);
    }

    /** Nothing is reserved before checkout, so there is nothing to release. */
    public function release(Request $request): JsonResponse
    {
        return response()->json(['success' => true]);
    }

    /* ------------------------------------------------------------------
     |  CHECKOUT HOOKS (called from OrderRepository)
     * ------------------------------------------------------------------ */

    /**
     * Called when the order is placed: reserves the customer's selected hours for this order.
     * Returns false ONLY when the hours were taken by another order meanwhile (order must not
     * be created). Missing / stale / unreadable selections are ignored.
     */
    public function attachBookingToOrder(string $bookingToken, int $orderId, bool $isPaid = false): bool
    {
        try {
            if (!$this->isEnabled()) {
                return true;
            }

            $selection = $this->decodeSelection($bookingToken);

            if (!$selection) {
                Log::warning('Studio booking selection unreadable at checkout', ['order_id' => $orderId]);
                return true;
            }

            if ($selection['date'] < now()->toDateString()) {
                return true; // stale selection from an earlier day
            }

            return DB::transaction(function () use ($selection, $orderId) {
                if (StudioBooking::where('order_id', $orderId)->exists()) {
                    return true; // already attached (retry safe)
                }

                // Lock the hour rows so two orders cannot take the same hours.
                $slots = $this->selectedSlots($selection['date'], $selection['start'], $selection['end'], true);

                if ($slots->count() !== $this->expectedHours($selection['start'], $selection['end'])
                    || $this->hasBlockingBooking($selection['date'], $selection['start'], $selection['end'])) {
                    Log::warning('Studio booking hours no longer available at checkout', ['order_id' => $orderId]);
                    return false;
                }

                StudioBooking::create([
                    'booking_token' => Str::random(48),
                    'customer_id' => auth('jwt-customer')->id(),
                    'order_id' => $orderId,
                    'schedule_date' => $selection['date'],
                    'start_time' => $selection['start'],
                    'end_time' => $selection['end'],
                    'duration_minutes' => $slots->count() * self::SLOT_MINUTES,
                    'status' => 'confirmed',
                    'expires_at' => null,
                ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Studio booking attach failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }

    /**
     * Called whenever an order's payment status changes. Never throws: a booking
     * problem must not break order payment updates.
     */
    public function syncBookingPaymentForOrder(int $orderId, int $paymentStatus): bool
    {
        try {
            StudioBooking::where('order_id', $orderId)
                ->where('status', 'held')
                ->update(['status' => 'confirmed', 'expires_at' => null]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Studio booking payment sync failed', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Free the studio hours of an order (use when an order is cancelled).
     */
    public function releaseBookingForOrder(int $orderId): bool
    {
        try {
            StudioBooking::where('order_id', $orderId)
                ->whereIn('status', ['held', 'confirmed'])
                ->update(['status' => 'cancelled']);

            return true;
        } catch (\Throwable $e) {
            Log::error('Studio booking release failed', ['order_id' => $orderId, 'message' => $e->getMessage()]);
            return false;
        }
    }

    /* ------------------------------------------------------------------
     |  HELPERS
     * ------------------------------------------------------------------ */

    private function isEnabled(): bool
    {
        return (int) (DB::table('tl_com_ecommerce_settings')
            ->where('key_name', self::ENABLE_KEY)
            ->value('key_value') ?? self::ENABLED) === self::ENABLED;
    }

    /** Bookings that currently occupy studio hours: confirmed, or held and not expired. */
    private function activeBookings()
    {
        return StudioBooking::where(function ($query) {
            $query->where('status', 'confirmed')
                ->orWhere(function ($held) {
                    $held->where('status', 'held')
                        ->where(function ($exp) {
                            $exp->whereNull('expires_at')->orWhere('expires_at', '>', now());
                        });
                });
        });
    }

    /** Selection token = url-safe base64 of {d,s,e}. Stateless: nothing is stored server-side. */
    private function encodeSelection(string $date, string $start, string $end): string
    {
        return rtrim(strtr(base64_encode(json_encode(['d' => $date, 's' => $start, 'e' => $end])), '+/', '-_'), '=');
    }

    private function decodeSelection(?string $token): ?array
    {
        if (!$token || strlen($token) > 300) {
            return null;
        }

        $json = base64_decode(strtr($token, '-_', '+/'), true);
        if ($json === false) {
            return null;
        }

        $d = json_decode($json, true);
        if (!is_array($d)) {
            return null;
        }

        $date = $d['d'] ?? null;
        $start = $d['s'] ?? null;
        $end = $d['e'] ?? null;

        if (!is_string($date) || !is_string($start) || !is_string($end)
            || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
            || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $start)
            || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $end)
            || $end <= $start) {
            return null;
        }

        return ['date' => $date, 'start' => $start, 'end' => $end];
    }

    private function expectedHours(string $start, string $end): int
    {
        $cursor = Carbon::createFromFormat('!H:i:s', $start);
        $stop = Carbon::createFromFormat('!H:i:s', $end);
        $count = 0;

        while ($cursor->lessThan($stop)) {
            $count++;
            $cursor->addMinutes(self::SLOT_MINUTES);
        }

        return $count;
    }

    private function selectedSlots(string $date, string $start, string $end, bool $lock = false)
    {
        $query = StudioBookingSlot::whereDate('schedule_date', $date)
            ->where('status', 1)
            ->where('start_time', '>=', $start)
            ->where('end_time', '<=', $end)
            ->orderBy('start_time');

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->get();
    }

    /** Hours exist, are enabled, are consecutive, and are not taken by a placed order. */
    private function selectionIsAvailable(array $selection): bool
    {
        if ($selection['date'] < now()->toDateString()) {
            return false;
        }

        $slots = $this->selectedSlots($selection['date'], $selection['start'], $selection['end']);

        return $slots->isNotEmpty()
            && $slots->count() === $this->expectedHours($selection['start'], $selection['end'])
            && !$this->hasBlockingBooking($selection['date'], $selection['start'], $selection['end']);
    }

    private function selectionPayload(string $date, string $start, string $end): array
    {
        return [
            'booking_token' => $this->encodeSelection($date, $start, $end),
            'schedule_date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'duration_minutes' => $this->expectedHours($start, $end) * self::SLOT_MINUTES,
            'expires_at' => null,
        ];
    }

    /**
     * $includeHeld is kept for signature compatibility; active holds always block.
     */
    private function hasBlockingBooking(
        string $date,
        string $start,
        string $end,
        bool $includeHeld = true,
        ?string $excludeBookingToken = null
    ): bool {
        $query = $this->activeBookings()
            ->whereDate('schedule_date', $date)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start);

        if ($excludeBookingToken) {
            $query->where('booking_token', '!=', $excludeBookingToken);
        }

        return $query->exists();
    }

    private function formatSlot(string $start, string $end): string
    {
        $startCarbon = Carbon::createFromFormat('H:i:s', $start);
        $endCarbon = Carbon::createFromFormat('H:i:s', $end);

        return $startCarbon->format('g:i A') . ' - ' . $endCarbon->format('g:i A');
    }
}
