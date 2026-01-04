<?php

namespace Plugin\Saas\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugin\Saas\Models\PaymentHistory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /*
     * will return store admin dashboard
     */
    public function index()
    {
        // Get tenant-specific data
        // $tenantId = tenant('id');

        $tenantId = 'd0b0bbb-1ff2-4c50-b8d1-7901ff0c20bc';

         return view('core::base.dashboard.index');

        //  return view('core::base.dashboard.index', [
        //     'tenant_id' => $tenantId,
        //     // Add other data you need for the store admin dashboard
        // ]);

        //  return view('plugin/multivendor::seller.dashboard.pages.dashboard', [
        //     'tenant_id' => $tenantId,
        // ]);
        // return view('core::base.dashboard.index', [
        //     'tenant_id' => $tenantId,
        // ]);

        // return view('plugin/saas::includes.dashboard', [
        //     'tenant_id' => $tenantId,
        // ]);
        
        // Add your store admin dashboard logic here
        // For example: orders, products, sales stats for THIS store only
    //       dd([
    //     'controller' => 'Plugin\Saas\Http\Controllers\Admin\DashboardController',
    //     'method' => 'dashboard',
    //     'tenant_id' => $tenantId,
    //     'auth_user' => auth()->user()?->email,
    //     'route_name' => request()->route()->getName()
    // ]);
        // return view('core::base.dashboard.index', [
        //     'tenant_id' => $tenantId,
        //     // Add other data you need for the store admin dashboard
        // ]);
    }
    /**
     * Will return sales chart report data
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse 
     */
    public function salesChartReport(Request $request)
    {
        $current_locale = getLocale();
        if ($current_locale == 'sa') {
            $current_locale = 'ar';
        }

        if ($current_locale == 'bd') {
            $current_locale = 'bn';
        }
        Carbon::setLocale($current_locale);

        if ($request['type'] == 'monthly') {
            $times = array();
            $sales = array();
            for ($i = 11; $i >= 0; $i--) {
                $first_day_of_month = Carbon::today()->startOfMonth()->subMonth($i);

                $last_day_of_month = Carbon::today()->endOfMonth()->subMonth($i);

                $total_sales = PaymentHistory::whereBetween(
                    'created_at',
                    [$first_day_of_month, $last_day_of_month]
                )
                    ->sum('final_amount');

                array_push($times, $first_day_of_month->shortMonthName);
                array_push($sales, $total_sales);
            }
            return response()->json(
                [
                    'success' => true,
                    'times' => $times,
                    'sales' => $sales,
                ]
            );
        }

        if ($request['type'] == 'daily') {
            $times = array();
            $sales = array();
            for ($i = 29; $i >= 0; $i--) {

                $day = Carbon::today()->endOfDay()->subDay($i);
                $total_sales = PaymentHistory::whereDate('created_at', $day)->sum('final_amount');
                array_push($sales, $total_sales);

                array_push($times, $day->translatedFormat('d M'));
            }

            return response()->json(
                [
                    'success' => true,
                    'times' => $times,
                    'sales' => $sales,
                ]
            );
        }
    }
}
