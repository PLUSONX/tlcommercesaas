<?php

namespace Theme\TLCommerce\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Theme\TLCommerce\Models\TlThemeOptionSettings;

class LayoutSettingsRepository {

    private const ACTIVE_LAYOUT_CACHE_KEY = 'active-store-layout';

    /** Layouts that use feature-pane props (same table/shape as split_screen). */
    private const FEATURE_PANE_LAYOUTS = ['split_screen', 'modern'];

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

        // Load feature-pane props for the active layout when it uses that settings shape
        $splitScreenSettings = null;
        if ($activeLayout && $this->isFeaturePaneLayout($activeLayout->name)) {
            $splitScreenSettings = $this->getSplitScreenProperties($activeLayout->id);
        }

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
            ->leftJoin('tl_uploaded_files as header_bg_files', 'props.header_background_image', '=', 'header_bg_files.id')
            ->where('props.layout_id', $layoutId)
            ->select(
                'props.*',
                'files.path as feature_image_path',
                'files.name as feature_image_name',
                'header_bg_files.path as header_background_image_path',
                'header_bg_files.name as header_background_image_name'
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

        if ($this->isFeaturePaneLayout($layout->name)) {
            $featurePaneSettings = $this->getSplitScreenProperties($layout->id);

            if ($featurePaneSettings && !empty($featurePaneSettings->feature_image)) {
                $displayPath = getDisplayImagePath($featurePaneSettings->feature_image, 1600, false);
                if ($displayPath) {
                    // BannerFeature expects a path without the /public prefix
                    $featurePaneSettings->feature_image_path = preg_replace('#^/public/#', '', $displayPath);
                    $featurePaneSettings->feature_image_path = ltrim($featurePaneSettings->feature_image_path, '/');
                }
            }

            if ($featurePaneSettings && !empty($featurePaneSettings->header_background_image)) {
                $headerBgPath = getDisplayImagePath($featurePaneSettings->header_background_image, 1600, false);
                if ($headerBgPath) {
                    $featurePaneSettings->header_background_image_path = preg_replace('#^/public/#', '', $headerBgPath);
                    $featurePaneSettings->header_background_image_path = ltrim($featurePaneSettings->header_background_image_path, '/');
                }
            }

            // Normalize to arrays for cache / JSON bootstrap.
            // Key `split_screen` = feature-pane props blob (shared by split_screen + modern).
            return [
                'id' => $layout->id,
                'name' => $layout->name,
                'type' => $layout->name,
                'settings' => json_decode($layout->settings, true),
                'split_screen' => $featurePaneSettings
                    ? json_decode(json_encode($featurePaneSettings), true)
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

    private function isFeaturePaneLayout(?string $name): bool
    {
        return in_array($name, self::FEATURE_PANE_LAYOUTS, true);
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

                $layoutName = DB::table('tl_store_layouts')
                    ->where('id', $layoutId)
                    ->value('name');

                if ($this->isFeaturePaneLayout($layoutName)) {
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
     * Ensures default properties exist for a feature-pane layout (split_screen / modern)
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

        if (array_key_exists('header_background_image', $data)) {
            $updateData['header_background_image'] = $data['header_background_image'] ?: null;
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
