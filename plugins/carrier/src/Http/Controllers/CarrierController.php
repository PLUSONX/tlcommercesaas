<?php

namespace Plugin\Carrier\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugin\Carrier\Http\Requests\CarrierRequest;
use Plugin\Carrier\Repositories\CarrierRepository;
use Plugin\Carrier\Services\ArmadaService;

class CarrierController extends Controller
{
    protected $carrier_repository;

    public function __construct(CarrierRepository $carrier_repository)
    {
        isActiveParentPlugin('tlecommercecore');

        $this->carrier_repository = $carrier_repository;
    }
    /**
     * Will return carrier list
     * 
     * @return mixed
     */
    public function carriers()
    {
        return redirect()->route('plugin.tlcommercecore.shipping.hub', ['tab' => 'carriers']);
    }
    /**
     * Will store new courier service
     * 
     * @param CarrierRequest $request
     * @return void
     */
    public function storeNewCourier(CarrierRequest $request)
    {
        $res = $this->carrier_repository->storeNewCourier($request);
        if ($res == true) {
            toastNotification('success', translate('New courier added successfully'), 'Success');
        } else {
            toastNotification('error', translate('Action failed'), 'Failed');
        }
    }
    /**
     * Will update courier status
     * 
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function updateCourierStatus(Request $request)
    {
        $res = $this->carrier_repository->updateCourierStatus($request['id']);
        if ($res == true) {
            toastNotification('success', translate('Courier status updated successfully'), 'Success');
        } else {
            toastNotification('error', translate('Action failed'), 'Failed');
        }
    }
    /**
     * Will delete courier
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function deleteCourier(Request $request)
    {
        $res = $this->carrier_repository->deleteCourier($request['id']);
        if ($res == true) {
            toastNotification('success', translate('Courier deleted successfully'), 'Success');
            return redirect()->route('plugin.tlcommercecore.shipping.hub', ['tab' => 'carriers']);
        } else {
            toastNotification('error', translate('Action failed'), 'Failed');
            return redirect()->back();
        }
    }
    /**
     * Will update courier module status
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function courierModuleUpdateStatus(Request $request)
    {
        $res = $this->carrier_repository->enableDisableCourierModule();
        if ($res == true) {
            toastNotification('success', translate('Status updated successfully'), 'Success');
        } else {
            toastNotification('error', translate('Action failed'), 'Failed');
        }
    }
    /**
     * Wii return courier edit form
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function editCourier(Request $request)
    {
        return view('plugin/carrier::pages.edit_courier')->with(
            [
                'courier_info' => $this->carrier_repository->courierDetails($request['id']),
            ]
        );
    }
    /**
     * Will update courier information
     * 
     * @param CarrierRequest $request
     * @return void
     */
    public function updateCourier(CarrierRequest $request)
    {
        $res = $this->carrier_repository->updateCourier($request);
        if ($res == true) {
            toastNotification('success', translate('Courier updated successfully'), 'Success');
        } else {
            toastNotification('error',translate('Action failed'), 'Failed');
        }
    }

    /**
     * Wii return courier properties form
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function courierProperties(Request $request)
    {
        Log::info("courierProperties method called!");

        return view('plugin/carrier::pages.properties_courier')->with(
            [
                'courier_properties' => $this->carrier_repository->courierProperties($request['id']),
            ]
        );

    }

     /**
     * Will update courier information
     * 
     * @param CarrierRequest $request
     * @return void
     */
    public function submitCourierProperties(Request $request)
    {
        Log::info("submitCourierProperties method called!");
        Log::info("Courier 1 Properties data:", ['request' => $request->all()]);
        $res = $this->carrier_repository->submitCourierProperties($request);
        if ($res == true) {
            toastNotification('success', translate('Courier properties updated successfully'), 'Success');
        } else {
            toastNotification('error',translate('Action failed'), 'Failed');
        }
    }

    /**
     * Will return carrier list
     * 
     * @return collections
     */
    public function getActiveCarriers()
    {
        return [
            'couriers' => $this->carrier_repository->couriers(1),
        ];
    }

    /**
     * Will submit courier request
     * 
     * @param Request $request
     * @return void
     */
    public function submitCourierRequest(Request $request)
    {
        Log::info("submitCourierRequest method called!");
        Log::info("Courier request data:", ['request' => $request->all()]);
        $res = $this->carrier_repository->submitCourierRequest($request);

        if (!is_array($res)) {
            $res = [
                'success' => (bool) $res,
                'message' => $res
                    ? translate('Delivery request submitted successfully.')
                    : translate('Something went wrong'),
            ];
        }

        return response()->json([
            'success' => (bool) ($res['success'] ?? false),
            'message' => $res['message'] ?? null,
        ]);
    }

    /**
     * Will update courier Orders
     * 
     * @param Request $request
     * @return void
     */
    public function updateShippingCourierOrders(Request $request)
    {
        Log::info("updateShippingCourierOrders method called!", ['payload' => $request->all()]);

        $res = $this->carrier_repository->updateShippingCourierOrders($request);

        if ($res) {
            return response()->json(['success' => true], 200);
        }

        return response()->json(['success' => false], 500);
    }

    /**
     * Will return courier order updates
     * 
     * @return collections
     */
    public function getCarriersOrderUpdates(Request $request)
    {
        Log::info("getCarriersOrderUpdates method called!", ['request' => $request->all()]);

        return [
            'order_details' => $this->carrier_repository->getCarriersOrderUpdates($request->order_id),
        ];
    }

    /**
     * Debug-only: exercise ArmadaService::getCoordinates with free-form address fields.
     *
     * POST /api/carrier/test-geocode
     * Body: address?, city?, state?, country?
     */
    public function testGeocode(Request $request)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $validated = $request->validate([
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
        ]);

        $addressText = trim((string) ($validated['address'] ?? ''));
        $city = trim((string) ($validated['city'] ?? ''));
        $state = trim((string) ($validated['state'] ?? ''));
        $country = trim((string) ($validated['country'] ?? '')) ?: 'Kuwait';

        if ($addressText === '' && $city === '' && $state === '') {
            return response()->json([
                'success' => false,
                'message' => 'Provide at least one of: address, city, state.',
            ], 422);
        }

        $address = new \stdClass();
        $address->address = $addressText;
        $address->city = $city !== '' ? (object) ['name' => $city] : null;
        $address->state = $state !== '' ? (object) ['name' => $state] : null;
        $address->country = (object) ['name' => $country];

        $candidates = $this->buildTestGeocodeCandidates($address);
        $result = app(ArmadaService::class)->getCoordinates($address);

        $resolved = !is_null($result['lat'] ?? null) && !is_null($result['lng'] ?? null);

        return response()->json([
            'success' => $resolved,
            'candidates' => $candidates,
            'result' => $result,
        ]);
    }

    /**
     * Mirror ArmadaService candidate building for debug response visibility.
     *
     * @param object $address
     * @return string[]
     */
    private function buildTestGeocodeCandidates($address): array
    {
        $addressParts = array_values(array_filter(array_map('trim', explode(',', (string) ($address->address ?? '')))));

        $tail = [
            $address->city->name ?? null,
            $address->state->name ?? null,
            $address->country->name ?? 'Kuwait',
        ];
        $tail = array_values(array_filter($tail));

        $candidates = [];
        $parts = $addressParts;
        while (!empty($parts)) {
            $candidates[] = implode(', ', array_merge($parts, $tail));
            array_shift($parts);
        }
        if (!empty($tail)) {
            $candidates[] = implode(', ', $tail);
        }

        return array_values(array_unique(array_filter($candidates)));
    }
}
