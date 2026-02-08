<?php

namespace Plugin\TlcommerceCore\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Plugin\TlcommerceCore\Models\EcommerceConfig;
use Plugin\TlcommerceCore\Repositories\CurrencyRepository;
use Plugin\TlcommerceCore\Repositories\SettingsRepository;
use Illuminate\Support\Facades\Log;
use Plugin\Multivendor\Models\SellerShop;

class SettingsController extends Controller
{
    protected $settings_repository;
    protected $currency_repository;

    public function __construct(SettingsRepository $settings_repository, CurrencyRepository $currency_repository)
    {
        $this->settings_repository = $settings_repository;
        $this->currency_repository = $currency_repository;
    }
    /**
     * Will return ecommerce settings
     * 
     * @return mixed
     */
    public function ecommerceConfig()
    {
        $currencies = $this->currency_repository->currencies(config('settings.general_status.active'));
        return view('plugin/tlecommercecore::ecommerce-settings.settings')->with(
            [
                'currencies' => $currencies,
            ]
        );
    }
    /**
     * Will update ecommerce settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEcommerceSettings(Request $request)
    {
        $tenant = tenant(); 
        Log::info('Tenant resolved', [
            'tenant' => $tenant ? $tenant->toArray() : null,
            'tenant_slug' => $tenant->shop_slug
        ]);

        if (!$request->filled('shop_slug')) {
            $request->merge([
                'shop_slug' => $tenant->tenancy_db_name
            ]);
        }

        // Normalize shop_name
        if (!$request->filled('shop_name')) {
            $request->merge([
                'shop_name' => ucfirst($tenant->tenancy_db_name) . ' Store'
            ]);
        }

        // Optional SEO defaults
        if (!$request->filled('shop_meta_title')) {
            $request->merge([
                'shop_meta_title' => $request->shop_name
            ]);
        }

        if (!$request->filled('shop_meta_description')) {
            $request->merge([
                'shop_meta_description' => 'Welcome to ' . $request->shop_name
            ]);
        }
        // if (!$request->filled('shop_slug')) {
        //     $request->merge(['shop_slug' => $tenant->slug]);

        // }
        // Log::info('updateEcommerceSettings request', $request->all());
        //validate shop url
        if (isActivePluging('multivendor') && $request->has('shop_name')) {
            $supper_admin_id = getSupperAdminId();
            if ($supper_admin_id == null) {
                throw ValidationException::withMessages([
                    'admin' => ['No super user found'],
                ]);
            }
            $in_house_shop_id = SettingsRepository::getEcommerceSetting('in_house_shop_id');
            if ($request['shop_slug'] == null) {
                throw ValidationException::withMessages([
                    'shop_slug' => ['Shop slug is required'],
                ]);
            }

            if (!\Plugin\Multivendor\Models\SellerShop::where('shop_slug', $request['shop_slug'])->whereNot('id', $in_house_shop_id)->doesntExist()) {
                throw ValidationException::withMessages([
                    'shop_slug' => ['Shop slug is already taken'],
                ]);
            }

            // if ($in_house_shop_id == null) {
            //     $shop = new \Plugin\Multivendor\Models\SellerShop;
            // }
            // if ($in_house_shop_id != null) {
            //     $shop = \Plugin\Multivendor\Models\SellerShop::where('id', $in_house_shop_id)->first();
            // }

            $shop = null;

            if ($in_house_shop_id) {
                $shop = SellerShop::find($in_house_shop_id);
            }

            // If ID is missing OR record does not exist → create
            if (!$shop) {
                $shop = new SellerShop();
                // $shop->is_in_house = 1; // if this column exists
            }


            //Store or update in-house shop
            $shop->shop_name = $request['shop_name'];
            $shop->shop_slug = $request['shop_slug'];
            $shop->seller_id = $supper_admin_id;
            $shop->shop_phone = $request['shop_phone'];
            $shop->logo = $request['shop_logo'];
            $shop->shop_banner = $request['shop_banner'];
            $shop->shop_address = $request['shop_address'];
            $shop->meta_title = $request['shop_meta_title'];
            $shop->meta_description = $request['shop_meta_description'];
            $shop->meta_image = $request['shop_meta_image'];
            $shop->status = config('settings.general_status.active');
            $shop->save();
            //store in-house shop id
            $config = EcommerceConfig::firstOrCreate(['key_name' => 'in_house_shop_id']);
            $config->key_value = $shop->id;
            $config->save();
        }
        $res = $this->settings_repository->updateEcommerceSettings($request);

        if ($res) {
            return response()->json(
                [
                    'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }
}
