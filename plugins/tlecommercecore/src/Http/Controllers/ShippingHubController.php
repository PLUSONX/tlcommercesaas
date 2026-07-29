<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugin\TlcommerceCore\Repositories\LocationRepository;

class ShippingHubController extends Controller
{
    protected $location_repository;

    public function __construct(LocationRepository $location_repository)
    {
        $this->location_repository = $location_repository;
    }

    /**
     * Unified shipping hub: carriers + locations tabs.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if (!$this->userCanAccessHub()) {
            abort(403);
        }

        $tab = $request->get('tab', $this->defaultTab());
        $locationTab = $request->get('location', 'countries');

        if ($tab === 'carriers') {
            if (!$this->userCanAccessCarriers()) {
                abort(403);
            }

            $carrier_repository = app(\Plugin\Carrier\Repositories\CarrierRepository::class);

            return view('plugin/tlecommercecore::shipping.hub.index')->with([
                'active_tab' => 'carriers',
                'location_tab' => $locationTab,
                'couriers' => $carrier_repository->couriers(),
                'can_carriers' => true,
                'can_locations' => auth()->user()->can('Manage Locations'),
            ]);
        }

        if ($tab === 'locations') {
            if (!auth()->user()->can('Manage Locations')) {
                abort(403);
            }

            $viewData = [
                'active_tab' => 'locations',
                'location_tab' => $locationTab,
                'can_carriers' => $this->userCanAccessCarriers(),
                'can_locations' => true,
            ];

            if ($locationTab === 'states') {
                $viewData['states'] = $this->location_repository->statesList($request);
            } elseif ($locationTab === 'cities') {
                $viewData['cities'] = $this->location_repository->citiesList($request);
            } else {
                $viewData['location_tab'] = 'countries';
                $viewData['countries'] = $this->location_repository->countryList($request);
            }

            return view('plugin/tlecommercecore::shipping.hub.index')->with($viewData);
        }

        abort(404);
    }

    protected function userCanAccessHub(): bool
    {
        return $this->userCanAccessCarriers() || auth()->user()->can('Manage Locations');
    }

    protected function userCanAccessCarriers(): bool
    {
        return isActivePluging('carrier') && auth()->user()->can('Manage Carriers');
    }

    protected function defaultTab(): string
    {
        if ($this->userCanAccessCarriers()) {
            return 'carriers';
        }

        if (auth()->user()->can('Manage Locations')) {
            return 'locations';
        }

        abort(403);
    }
}
