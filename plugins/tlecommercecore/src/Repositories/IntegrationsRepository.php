<?php

namespace Plugin\TlcommerceCore\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class IntegrationsRepository {


    /**
     * Get active layout
     * * @return array
    */
    public function getAllIntegrationSettings()
    {
        if (!tenant()) {
            return [];
        }

        $integrationSettings = DB::table('tl_com_social_media_integrations')
            ->get()
            ->keyBy('provider');

        return $integrationSettings;
    }

    /**
     * Update or Create Integration Settings
     * * @param string $provider
     * @param int $isActive
     * @param array $settings
     * @return bool
     */
    public function updateIntegrationSettings($provider, $isActive, array $settings)
    {
        if (!tenant()) {
            return false;
        }

        $updated = DB::table('tl_com_social_media_integrations')->updateOrInsert(
            ['provider' => $provider],
            [
                'is_active'  => $isActive,
                'settings'   => json_encode($settings),
                'updated_at' => now(),
                'created_at' => DB::raw('IFNULL(created_at, NOW())') 
            ]
        );

        Cache::forget(tenantCacheKey('social-pixel-integrations'));

        return $updated;
    }

}