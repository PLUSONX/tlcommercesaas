<?php

namespace Theme\TLCommerce\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Theme\TLCommerce\Models\TlThemeOptionSettings;

class LayoutSettingsRepository {

    private const ACTIVE_LAYOUT_CACHE_KEY = 'active-store-layout';

    /**
     * Get all layouts and the currently active layout
     * * @return array
    */
    public function getLayOutSettings() {
        $layouts = DB::table('tl_store_layouts')->get();
        $activeLayout = DB::table('tl_store_layouts')->where('is_active', 1)->first();

        if (!$activeLayout) {
            $activeLayout = $layouts->firstWhere('name', 'standard') ?? $layouts->first();
        }

        $splitScreenLayout = $layouts->firstWhere('name', 'split_screen');
        $splitScreenSettings = $splitScreenLayout
            ? $this->getSplitScreenProperties($splitScreenLayout->id)
            : null;

        return [
            'layouts' => $layouts,
            'activeLayout' => $activeLayout,
            'splitScreenSettings' => $splitScreenSettings,
        ];
    }

    /**
     * Get split screen layout properties for admin editing
     * @param int $layoutId
     * @return object|null
     */
    public function getSplitScreenProperties($layoutId)
    {
        return DB::table('tl_store_layouts_split_screen_properties as props')
            ->leftJoin('tl_uploaded_files as files', 'props.feature_image', '=', 'files.id')
            ->where('props.layout_id', $layoutId)
            ->select(
                'props.*',
                'files.path as feature_image_path',
                'files.name as feature_image_name'
            )
            ->first();
    }

    /**
     * Get active layout (cached per tenant for instant storefront bootstrap)
     *
     * @return array|null
     */
    public function getActiveLayout()
    {
        return Cache::rememberForever($this->activeLayoutCacheKey(), function () {
            return $this->resolveActiveLayout();
        });
    }

    /**
     * Resolve active layout from DB (uncached)
     *
     * @return array|null
     */
    private function resolveActiveLayout()
    {
        $layout = DB::table('tl_store_layouts')
            ->where('is_active', 1)
            ->first();

        if (!$layout) {
            return null;
        }

        if ($layout->name === 'split_screen') {
            $splitScreenSettings = $this->getSplitScreenProperties($layout->id);

            if ($splitScreenSettings && !empty($splitScreenSettings->feature_image)) {
                $displayPath = getDisplayImagePath($splitScreenSettings->feature_image, 1600, false);
                if ($displayPath) {
                    // BannerFeature expects a path without the /public prefix
                    $splitScreenSettings->feature_image_path = preg_replace('#^/public/#', '', $displayPath);
                    $splitScreenSettings->feature_image_path = ltrim($splitScreenSettings->feature_image_path, '/');
                }
            }

            // Normalize to arrays for cache / JSON bootstrap
            return [
                'id' => $layout->id,
                'name' => $layout->name,
                'type' => 'split_screen',
                'settings' => json_decode($layout->settings, true),
                'split_screen' => $splitScreenSettings
                    ? json_decode(json_encode($splitScreenSettings), true)
                    : null,
            ];
        }

        return [
            'id' => $layout->id,
            'name' => $layout->name,
            'type' => 'default',
            'settings' => json_decode($layout->settings, true),
        ];
    }

    /**
     * Clear cached active layout for the current tenant
     */
    public function clearActiveLayoutCache(): void
    {
        Cache::forget($this->activeLayoutCacheKey());
    }

    private function activeLayoutCacheKey(): string
    {
        return tenantCacheKey(self::ACTIVE_LAYOUT_CACHE_KEY);
    }

    /**
     * Update Layout Settings
     * @return boolean
    */
    public function updateLayoutSettings($layoutId) 
    {
        try {
            $updated = DB::transaction(function () use ($layoutId) {
                // 1. Deactivate all
                DB::table('tl_store_layouts')->update(['is_active' => 0]);
                
                // 2. Activate selected
                $updated = DB::table('tl_store_layouts')
                    ->where('id', $layoutId)
                    ->update(['is_active' => 1]);
                
                if ($layoutId == 4) {
                    $this->ensureSplitScreenDefaults($layoutId);
                }
                    
                return $updated > 0;
            });

            if ($updated) {
                $this->clearActiveLayoutCache();
            }

            return $updated;
        } catch (\Exception $e) {
            \Log::error("Repo Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensures default properties exist for the split screen layout
     * @param int $layoutId
     */
    private function ensureSplitScreenDefaults($layoutId)
    {
        $exists = DB::table('tl_store_layouts_split_screen_properties')
                    ->where('layout_id', $layoutId)
                    ->exists();

        if (!$exists) {
            DB::table('tl_store_layouts_split_screen_properties')->insert([
                'layout_id'        => $layoutId,
                'content_position' => 'left',
                'feature_type'     => 'banner',
                'background_color' => '#f8f9fa', // A clean, neutral default
                'created_at'       => now(),
                'updated_at'       => now()
            ]);
        }
    }


    /**
     * Update split screen layout properties
     * @param array $data
     * @return bool
     */
    public function updateSplitScreenProperties(array $data)
    {
        // \Log::info('updateSplitScreenProperties method called!!!');
        // Get the active layout ID
        $layoutId = DB::table('tl_store_layouts')
                        ->where('is_active', 1)
                        ->value('id');

        if (!$layoutId) {
            \Log::warning('No active layout found with status = 1');
            return false;
        }

        // Ensure defaults exist first
        $this->ensureSplitScreenDefaults($layoutId);

        // Prepare update data
        $updateData = [
            'updated_at' => now()
        ];

        if (isset($data['content_position'])) {
            $updateData['content_position'] = $data['content_position'];
        }

        if (isset($data['feature_type'])) {
            $updateData['feature_type'] = $data['feature_type'];
        }

        if (isset($data['feature_image'])) {
            $updateData['feature_image'] = $data['feature_image'];
        }

        if (isset($data['background_color'])) {
            $updateData['background_color'] = $data['background_color'];
        }

        // \Log::info('Before updating the split screen properties!!!');

        // Update the record
        $updated = DB::table('tl_store_layouts_split_screen_properties')
                    ->where('layout_id', $layoutId)
                    ->update($updateData);

        // \Log::info('After updating the split screen properties!!!');

        if ($updated > 0) {
            $this->clearActiveLayoutCache();
        }

        return $updated > 0;
    }

}
