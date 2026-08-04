<?php

namespace Plugin\TlcommerceCore\Http\Controllers\Api;

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
     * Return bookable delivery time slots for checkout.
     */
    public function availableSlots()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->delivery_schedule_repository->listAvailableSlotsForCheckout(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => translate('Failed to load delivery time slots.'),
            ]);
        }
    }
}
