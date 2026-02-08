<?php

namespace Plugin\PickupPoint\Http\Repositories;

use Illuminate\Support\Facades\DB;
use Plugin\PickupPoint\Models\PickupPoint;
use Illuminate\Support\Facades\Log;

class PickupPointRepository
{
    /**
     * get pickup point list query builder
     *
     * @return mixed
     */
    public function getPickupPointList()
    {

        return PickupPoint::with(['country', 'state', 'city'])->get()->map(function ($item) {
            return [
                'pickup_id' => $item->id,
                'name' => $item->name,
                'location' => $item->location,
                'status' => $item->status,
                'phone' => $item->phone,
                'country' => $item->country != null ? $item->country->translation('name') : null,
                'state' => $item->state != null ? $item->state->translation('name') : null,
                'city' => $item->city != null ? $item->city->translation('name') : null,
            ];
        });
    }

    /**
     * Will get active pickup point list
     * 
     * @param Object $request
     * @return Collections
     */
    public function getActivePickupPoint($request)
    {

        return PickupPoint::with(['country', 'city', 'state'])
            ->where('status', config('settings.general_status.active'))
            ->select('name', 'location', 'phone', 'country_id', 'city_id', 'state_id', 'id')
            ->get();
    }
    // public function getActivePickupPoint($request)
    // {
    //     // Log the active status being used for the query
    //     $activeStatus = config('settings.general_status.active');
    //     \Log::info('Pickup Point Fetch - Active Status Setting:', ['status' => $activeStatus]);

    //     $data = PickupPoint::with(['country', 'city', 'state'])
    //         ->where('status', $activeStatus)
    //         ->select('name', 'location', 'phone', 'country_id', 'city_id', 'state_id', 'id')
    //         ->get();

    //     // Log the count and the data
    //     \Log::info('Pickup Point Results:', [
    //         'count' => $data->count(),
    //         'results' => $data->toArray()
    //     ]);

    //     return $data;
    // }
    
}
