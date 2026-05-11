<?php

namespace Core\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!isTenant()) {
            $this->middleware(['license', 'is-saas']);
        }
    }

    public function dashboard()
    {

        \Log::info('Dashboard controller reached - user: ' . \Auth::id() . ' user_type: ' . \Auth::user()->user_type);

        if (!isTenant()) {
            $update_config_path = base_path('updates/config.json');

            $config_file = @file_get_contents($update_config_path, true);
            if ($config_file === true) {
                $json = json_decode($config_file, true);
                $system_version = getGeneralSetting('system_version');
                if ($system_version == $json['version']) {
                    return view('core::base.dashboard.index');
                }
            }


            if (file_exists($update_config_path)) {
                return view('core::base.system.update.update_dashboard');
            }
        }
        return view('core::base.dashboard.index');
    }

    public function filter(Request $request)
    {
        try {
                \Log::info('Filter Dashboard method called');

                $filter = $request->get('filter', 'all-time');

                // Customers
                $customerQuery = \Plugin\TlcommerceCore\Models\Customers::select('id');

                // Products
                $productQuery = \Plugin\TlcommerceCore\Models\Product::select('id');

                // Sales
                // $salesQuery = \Plugin\TlcommerceCore\Models\Orders::query();
                $salesQuery = \Plugin\TlcommerceCore\Models\Orders::query()
                    ->where('payment_status', config('tlecommercecore.order_payment_status.paid'));

                // Orders
                $ordersQuery = DB::table('tl_com_ordered_products')
                    ->leftJoin('tl_com_orders', 'tl_com_orders.id', '=', 'tl_com_ordered_products.order_id')
                    ->groupBy('tl_com_ordered_products.order_id')
                    ->select(DB::raw('GROUP_CONCAT(DISTINCT(tl_com_ordered_products.order_id)) as order_id'));

                switch ($filter) {
                    case 'daily':
                        $customerQuery->whereDate('created_at', today());
                        $productQuery->whereDate('created_at', today());
                        $salesQuery->whereDate('created_at', today());
                        $ordersQuery->whereDate('tl_com_orders.created_at', today());
                        break;
                    case 'weekly':
                        $customerQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $productQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $salesQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $ordersQuery->whereBetween('tl_com_orders.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'monthly':
                        $customerQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        $productQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        $salesQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        $ordersQuery->whereMonth('tl_com_orders.created_at', now()->month)
                                    ->whereYear('tl_com_orders.created_at', now()->year);
                        break;
                }

                return response()->json([
                    'total_customers' => $customerQuery->count(),
                    'total_products'  => $productQuery->count(),
                    'total_sales'     => $salesQuery->sum('total_payable_amount'),
                    'total_orders'    => $ordersQuery->get()->count(),
                ]);
        }
        catch (\Exception $e) {
            Log::error('filter dashboard failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
    }
}
