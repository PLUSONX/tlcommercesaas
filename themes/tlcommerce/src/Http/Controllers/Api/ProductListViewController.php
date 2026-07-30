<?php

namespace Theme\TLCommerce\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Theme\TLCommerce\Repositories\ProductListViewRepository;

class ProductListViewController extends Controller
{
    protected ProductListViewRepository $productListViewRepo;

    public function __construct(ProductListViewRepository $productListViewRepo)
    {
        $this->productListViewRepo = $productListViewRepo;
    }

    /**
     * Split-screen home product list with summary.
     */
    public function splitScreenProductList()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->productListViewRepo->getSplitScreenProductList(),
            ]);
        } catch (\Exception $e) {
            Log::error('Split-screen product list API failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'data' => [
                    'enabled' => false,
                ],
            ], 500);
        }
    }
}
