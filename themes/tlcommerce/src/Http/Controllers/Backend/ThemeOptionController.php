<?php

namespace Theme\TLCommerce\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Expr\Cast\Object_;
use Theme\TLCommerce\Repositories\ThemeOptionRepository;
use Theme\TLCommerce\Repositories\LayoutSettingsRepository;
use Illuminate\Support\Facades\Log;

class ThemeOptionController extends Controller
{

    protected $themeOption_repository;
    protected $layoutRepo;

    public function __construct(ThemeOptionRepository $themeOption_repository, LayoutSettingsRepository $layoutRepo)
    {
        $this->themeOption_repository = $themeOption_repository;
        $this->layoutRepo = $layoutRepo;
    }

    /**
     ** Theme Options Page
     * @return View
     */
    public function themeOptions()
    {
        try {
            return view('theme/tlcommerce::backend.theme.options');
        } catch (\Exception $e) {
            toastNotification('error', translate('Theme Option Page Failed'));
            return redirect()->back();
        }
    }

    /**
     ** Get Theme Option Form
     * @param object $request
     * @return Response
     */
    public function getOptionForm(Request $request)
    {
        try {
            $active_theme = getActiveTheme();
            $option_name = $request->id;
            $option_settings = getThemeOption($option_name, $active_theme->id);
            $form = view('theme/tlcommerce::backend.theme.option-form.' . $option_name, compact('option_settings'))->render();
            return response()->json(['form' => $form]);
        } catch (\Exception $e) {
            return response()->json(['error' => translate('Theme Option Getting Failed')]);
        }
    }

    /**
     ** Save Theme Option Form
     * @param object $request
     * @return Response
     */
    public function saveOptionForm(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->submitType == 'reset_all' || $request->submitType == 'reset_section') {
                $this->themeOption_repository->resetThemeOption($request);
            } else {
                if ($request->option_name == 'social') {
                    $this->themeOption_repository->saveSocialLink($request);
                    $this->themeOption_repository->saveThemeOption($request);
                } elseif ($request->option_name == 'custom_fonts') {
                    $this->themeOption_repository->saveCustomFont($request);
                } else {
                    $this->themeOption_repository->saveThemeOption($request);
                }
            }

            DB::commit();
            toastNotification('success', translate('Theme Option Saved'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            toastNotification('error', translate('Theme Option Saving Failed'));
            return redirect()->back();
        }
    }


    /**
     ** Layout Settings Page
     * @return View
     */
    // public function layoutSettings()
    // {
    //     try {
    //         return view('theme/tlcommerce::backend.layout-settings.layout_settings');
    //     } catch (\Exception $e) {
    //         toastNotification('error', translate('Layout Settings Page Failed'));
    //         return redirect()->back();
    //     }
    // }


    /**
 * Layout Settings Page
 * @return View
 */
public function layoutSettings()
{
    try {
        //  \Log::info('layoutSettings method called !!!!');

         $data = $this->layoutRepo->getLayOutSettings();
       
        return view('theme/tlcommerce::backend.layout-settings.layout_settings', $data);

    } catch (\Exception $e) {
        Log::error("Layout page Failed: {$e->getMessage()}");

        toastNotification('error', translate('Layout Settings Page Failed'));
        return redirect()->back();
    }
}


 /**
 * Activate Layout Data
 * @return array
 */
public function getActiveLayout()
{
    try {
        // \Log::info('getActiveLayout method called !!!!');

        $layout = $this->layoutRepo->getActiveLayout();
       
        return response()->json([
                    'layout' => $layout
                ]);

    } catch (\Exception $e) {
        Log::error("Layout Get Error: {$e->getMessage()}");
        toastNotification('error', translate('Layout Get Error'));
        return redirect()->back();
    }
}

    /**
     * Update Layout Settings
     * @return RedirectResponse
     */
    public function updateLayoutSettings(Request $request)
    {
        // 1. Validate here (or use a FormRequest)
        $request->validate([
            'layout_id' => 'required|exists:tl_store_layouts,id'
        ]);

        try {
            $status = $this->layoutRepo->updateLayoutSettings($request->layout_id);
            
            if ($status) {
                toastNotification('success', translate('Layout Updated Successfully'));
            } else {
                toastNotification('error', translate('Layout Update Failed'));
            }
            
            return redirect()->back();
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * edit Layout Settings
     * @return RedirectResponse
     */
    public function editLayoutSettings(Request $request) 
    {
        // \Log::info('editLayoutSettings method called!!!', [
        //     'request' => $request->all(),
        // ]);

        // Validate the request
        // $request->validate([
        //     'content_position' => 'required|in:left,right',
        //     'feature_type'     => 'required|in:banner', // Add more types as needed
        //     'background_color' => 'nullable|string|max:7' // For hex colors
        // ]);

        // Update the properties
        $updated = $this->layoutRepo->updateSplitScreenProperties([
            'content_position' => $request->content_position,
            'feature_type'     => $request->feature_type,
            'feature_image'    => $request->feature_image,
            'background_color' => $request->background_color,
        ]);

        if ($updated) {
            return redirect()->back()->with('success', translate('Layout settings updated successfully'));
        }

        return redirect()->back()->with('error', translate('Failed to update layout settings'));
    }
}
