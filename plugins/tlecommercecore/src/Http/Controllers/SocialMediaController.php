<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Plugin\TlcommerceCore\Repositories\IntegrationsRepository;

class SocialMediaController extends Controller {

    protected $integrations_repository;

    public function __construct(IntegrationsRepository $integrations_repository)
    {
        $this->integrations_repository = $integrations_repository;
    }

    /**
     * Will return custom notification list
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function socialMediaIntegration(Request $request)
    {
        if (!tenant()) {
            abort(403);
        }

        $integrationSettings = $this->integrations_repository->getAllIntegrationSettings();

        return view('plugin/tlecommercecore::marketing.social_media_integration', compact('integrationSettings'));
    }


    public function updateIntegrationSettings(Request $request)
    {
        if (!tenant()) {
            abort(403);
        }

        try {
            // We loop through the request data excluding the CSRF token
            // This allows you to scale and add 'google_ads', etc., without changing code
            foreach ($request->except('_token') as $provider => $data) {
                
                // Extract the 'is_active' toggle (defaults to 0 if unchecked)
                $isActive = isset($data['is_active']) ? 1 : 0;
                
                // The rest of the fields go into the JSON settings column
                // We remove 'is_active' from the settings array to avoid redundancy
                $settings = $data;
                unset($settings['is_active']);

                $this->integrations_repository->updateIntegrationSettings(
                    $provider, 
                    $isActive, 
                    $settings
                );
            }

            toastNotification('success', 'Settings updated successfully', 'Success');


            return redirect()->back()->with('success', translate('Settings updated successfully'));
            
        } catch (\Exception $e) {
            toastNotification('error', $e->getMessage(), 'Error');
            return redirect()->back()->with('error', translate('Update failed: ') . $e->getMessage());
        }
    }

}