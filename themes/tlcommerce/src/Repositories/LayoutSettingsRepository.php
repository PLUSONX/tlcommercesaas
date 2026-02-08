<?php

namespace Theme\TLCommerce\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Theme\TLCommerce\Models\TlThemeOptionSettings;

class LayoutSettingsRepository {

    /**
     * Get all layouts and the currently active layout
     * * @return array
    */
    public function getLayOutSettings() {

        // \Log::info('getLayOutSettings repository method called !!!!');

        $layouts = DB::table('tl_store_layouts')->get();
        $activeLayout = DB::table('tl_store_layouts')->where('is_active', 1)->first();

        // \Log::info('layout settings data', [
        //         'layouts' => json_encode($layouts),
        //         'activeLayout' => json_encode($activeLayout),
        // ]);

        return [
            'layouts' => $layouts,
            'activeLayout' => $activeLayout
        ];

    }

    /**
     * Get active layout
     * * @return array
    */
    public function getActiveLayout()
    {
        $layout = DB::table('tl_store_layouts')
            ->where('is_active', 1)
            ->first();

        if (!$layout) {
            return response()->json(['layout' => null]);
        }

        // If it's a split-screen layout (id: 4)
        if ($layout->id == 4) {

            $splitScreenSettings = DB::table('tl_store_layouts_split_screen_properties as props')
                ->leftJoin('tl_uploaded_files as files', 'props.feature_image', '=', 'files.id')
                ->where('props.layout_id', $layout->id)
                ->select(
                    'props.*',
                    'files.path as feature_image_path',
                    'files.name as feature_image_name'
                )
                ->first();

            \Log::info('splitScreenSettings data!!!', [
                'splitScreenSettings' => $splitScreenSettings,
            ]);

            return [
                'id' => $layout->id,
                'name' => $layout->name,
                'type' => 'split_screen',
                'settings' => json_decode($layout->settings, true),
                'split_screen' => $splitScreenSettings
            ];
            // $splitScreenSettings = DB::table('tl_store_layouts_split_screen_properties')
            //     ->where('layout_id', $layout->id)
            //     ->first();

            // return [
            //     'id' => $layout->id,
            //     'name' => $layout->name,
            //     'type' => 'split_screen',
            //     'settings' => json_decode($layout->settings, true),
            //     'split_screen' => $splitScreenSettings
            // ];
        }

        return [
                'id' => $layout->id,
                'name' => $layout->name,
                'type' => 'default',
                'settings' => json_decode($layout->settings, true)
        ];
    }

    /**
     * Update Layout Settings
     * @return boolean
    */
    public function updateLayoutSettings($layoutId) 
    {
        try {
            return DB::transaction(function () use ($layoutId) {
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
        \Log::info('updateSplitScreenProperties method called!!!');
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

        \Log::info('Before updating the split screen properties!!!');

        // Update the record
        $updated = DB::table('tl_store_layouts_split_screen_properties')
                    ->where('layout_id', $layoutId)
                    ->update($updateData);

        \Log::info('After updating the split screen properties!!!');
        

        return $updated > 0;
    }

}