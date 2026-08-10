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
                case 'customize':
                    $startDate = \Carbon\Carbon::parse($request->get('start_date'))->startOfDay();
                    $endDate = \Carbon\Carbon::parse($request->get('end_date'))->endOfDay();

                    $customerQuery->whereBetween('created_at', [$startDate, $endDate]);
                    $productQuery->whereBetween('created_at', [$startDate, $endDate]);
                    $salesQuery->whereBetween('created_at', [$startDate, $endDate]);
                    $ordersQuery->whereBetween('tl_com_orders.created_at', [$startDate, $endDate]);
                    break;
            }

            $analytics_store_visits = 0;
            $analytics_add_to_cart = 0;
            $analytics_checkout = 0;
            $analytics_content_view = 0;

            try {
                $storeVisitQuery = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'store_visit');
                $addToCartQuery = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'add_to_cart');
                $checkoutQuery = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'checkout');
                $contentViewQuery = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'content_view');

                switch ($filter) {
                    case 'daily':
                        $storeVisitQuery->whereDate('created_at', today());
                        $addToCartQuery->whereDate('created_at', today());
                        $checkoutQuery->whereDate('created_at', today());
                        $contentViewQuery->whereDate('created_at', today());
                        break;
                    case 'weekly':
                        $storeVisitQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $addToCartQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $checkoutQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $contentViewQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'monthly':
                        $storeVisitQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        $addToCartQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        $checkoutQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        $contentViewQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        break;
                    case 'customize':
                        $startDate = \Carbon\Carbon::parse($request->get('start_date'))->startOfDay();
                        $endDate = \Carbon\Carbon::parse($request->get('end_date'))->endOfDay();

                        $storeVisitQuery->whereBetween('created_at', [$startDate, $endDate]);
                        $addToCartQuery->whereBetween('created_at', [$startDate, $endDate]);
                        $checkoutQuery->whereBetween('created_at', [$startDate, $endDate]);
                        $contentViewQuery->whereBetween('created_at', [$startDate, $endDate]);
                        break;
                }

                $analytics_store_visits = $storeVisitQuery->count();
                $analytics_add_to_cart = $addToCartQuery->count();
                $analytics_checkout = $checkoutQuery->count();
                $analytics_content_view = $contentViewQuery->count();
            } catch (\Throwable $e) {
                $analytics_store_visits = 0;
                $analytics_add_to_cart = 0;
                $analytics_checkout = 0;
                $analytics_content_view = 0;
            }

            return response()->json([
                'total_customers' => $customerQuery->count(),
                'total_products'  => $productQuery->count(),
                'total_sales'     => $salesQuery->sum('total_payable_amount'),
                'total_orders'    => $ordersQuery->get()->count(),
                'analytics_store_visits' => $analytics_store_visits,
                'analytics_add_to_cart' => $analytics_add_to_cart,
                'analytics_checkout' => $analytics_checkout,
                'analytics_content_view' => $analytics_content_view,
            ]);
        } catch (\Exception $e) {
            Log::error('filter dashboard failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Per-product analytics breakdown for Content View / Add to Cart cards.
     */
    public function analyticsByProduct(Request $request)
    {
        $eventType = (string) $request->get('event_type', '');
        $allowed = ['content_view', 'add_to_cart'];

        if (!in_array($eventType, $allowed, true)) {
            return response()->json([
                'success' => true,
                'items' => [],
            ]);
        }

        $filter = $request->get('filter', 'all-time');

        try {
            $query = DB::table('tl_com_analytics_events')
                ->where('event_type', $eventType)
                ->whereNotNull('product_id');

            switch ($filter) {
                case 'daily':
                    $query->whereDate('created_at', today());
                    break;
                case 'weekly':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
            }

            $rows = $query
                ->select('product_id', DB::raw('COUNT(*) as total'))
                ->groupBy('product_id')
                ->orderByDesc('total')
                ->limit(50)
                ->get();

            $productIds = $rows->pluck('product_id')->filter()->unique()->values()->all();
            $products = collect();
            if (!empty($productIds)) {
                $products = DB::table('tl_com_products')
                    ->whereIn('id', $productIds)
                    ->get(['id', 'name', 'thumbnail_image'])
                    ->keyBy('id');
            }

            $fallbackImage = asset('backend/assets/img/avatar/avatar-user.png');

            $items = $rows->map(function ($row) use ($products, $fallbackImage) {
                $id = (int) $row->product_id;
                $product = $products->get($id);
                $image = $fallbackImage;
                if ($product && !empty($product->thumbnail_image)) {
                    $image = str_replace('/public', '', getFilePath($product->thumbnail_image));
                }

                return [
                    'product_id' => $id,
                    'name' => $product->name ?? ('Product #' . $id),
                    'image' => $image,
                    'total' => (int) $row->total,
                ];
            })->values();

            return response()->json([
                'success' => true,
                'items' => $items,
            ]);
        } catch (\Throwable $e) {
            Log::error('analytics by product failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => true,
                'items' => [],
            ]);
        }
    }
}
