<?php

namespace Theme\TLCommerce\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Plugin\TlcommerceCore\Models\Product;

class ProductListViewRepository
{
    private const SETTINGS_TABLE = 'tl_com_product_list_view_settings';

    private const ORDER_TABLE = 'tl_com_product_list_view_category_order';

    private const SETTINGS_ID = 1;

    private const CACHE_KEY = 'product-list-view-settings';

    /**
     * Data for the admin modal on Layout Settings.
     */
    public function getAdminData(): array
    {
        return [
            'productListViewSettings' => $this->getSettings(),
            'productListViewCategories' => $this->getOrderedTopLevelCategories(),
        ];
    }

    /**
     * Settings payload for the storefront API.
     */
    public function getStorefrontSettings(): array
    {
        $settings = $this->getSettings();

        $payload = [
            'is_list_view_enabled' => (bool) $settings->is_list_view_enabled,
            'organise_by_category' => (bool) $settings->organise_by_category,
            'categories' => [],
        ];

        if ($payload['is_list_view_enabled'] && $payload['organise_by_category']) {
            $payload['categories'] = $this->getOrderedTopLevelCategories()
                ->map(function ($category) {
                    return [
                        'id' => (int) $category->id,
                        'name' => $category->name,
                        'ordering' => (int) $category->ordering,
                    ];
                })
                ->values()
                ->all();
        }

        return $payload;
    }

    /**
     * Products payload for split-screen home list view.
     */
    public function getSplitScreenProductList(): array
    {
        $settings = $this->getSettings();

        if (!(bool) $settings->is_list_view_enabled) {
            return [
                'enabled' => false,
            ];
        }

        $organiseByCategory = (bool) $settings->organise_by_category;
        $limit = (int) (getEcommerceSetting('product_per_page') ?: 50);
        $limit = $limit > 0 ? $limit : 50;

        if ($organiseByCategory) {
            $sections = [];

            foreach ($this->getOrderedTopLevelCategories() as $category) {
                $products = $this->getProductsForCategory((int) $category->id, $limit);

                if ($products->isEmpty()) {
                    continue;
                }

                $sections[] = [
                    'category' => [
                        'id' => (int) $category->id,
                        'name' => $category->name,
                    ],
                    'products' => $products->values()->all(),
                ];
            }

            return [
                'enabled' => true,
                'organise_by_category' => true,
                'sections' => $sections,
            ];
        }

        return [
            'enabled' => true,
            'organise_by_category' => false,
            'products' => $this->getFlatProducts($limit)->values()->all(),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function getFlatProducts(int $limit): Collection
    {
        return $this->baseProductQuery()
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => $this->mapProduct($product));
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function getProductsForCategory(int $categoryId, int $limit): Collection
    {
        return $this->baseProductQuery()
            ->whereHas('product_categories', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => $this->mapProduct($product));
    }

    private function baseProductQuery()
    {
        return Product::query()
            ->where('status', config('settings.general_status.active'))
            ->with([
                'product_translations',
                'single_price',
                'variations',
                'seller.shop',
            ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapProduct(Product $product): array
    {
        $locale = session()->get('api_locale');

        return [
            'id' => (int) $product->id,
            'has_variant' => (int) $product->has_variant,
            'name' => $product->translation('name', $locale),
            'slug' => $product->permalink,
            'summary' => strip_tags((string) $product->translation('summary', $locale)),
            'thumbnail_image' => getFilePathWithSize($product->thumbnail_image, true, '250x250'),
            'base_price' => (float) $this->productBasePrice($product),
            'price' => (float) $product->unit_price,
            'quantity' => (float) $this->productStock($product),
            'min_qty' => $product->min_item_on_purchase != null ? (int) $product->min_item_on_purchase : 0,
            'max_qty' => $product->max_item_on_purchase != null ? (int) $product->max_item_on_purchase : 0,
            'seller' => $product->supplier,
            'shop' => isActivePluging('multivendor') && $product->seller != null ? $product->seller->shop : null,
        ];
    }

    private function productBasePrice(Product $product): float
    {
        if ($product->has_variant == config('tlecommercecore.product_variant.single')) {
            return $product->single_price != null ? (float) $product->single_price->unit_price : 0;
        }

        return $product->variations != null && $product->variations->isNotEmpty()
            ? (float) $product->variations[0]->unit_price
            : 0;
    }

    private function productStock(Product $product): float
    {
        if ($product->has_variant == config('tlecommercecore.product_variant.single')) {
            return $product->single_price != null ? (float) $product->single_price->quantity : 0;
        }

        if ($product->variations == null) {
            return 0;
        }

        return (float) $product->variations->sum('quantity');
    }

    /**
     * @return object{is_list_view_enabled:int,organise_by_category:int}
     */
    public function getSettings(): object
    {
        if (!$this->tablesExist()) {
            return $this->defaultSettingsObject();
        }

        return Cache::remember($this->cacheKey(), 3600, function () {
            $settings = DB::table(self::SETTINGS_TABLE)
                ->where('id', self::SETTINGS_ID)
                ->first();

            if (!$settings) {
                $this->seedDefaultSettings();

                return $this->defaultSettingsObject();
            }

            return $settings;
        });
    }

    /**
     * Top-level categories merged with custom list-view ordering.
     */
    public function getOrderedTopLevelCategories(): Collection
    {
        if (!$this->tablesExist()) {
            return collect();
        }

        return DB::table('tl_com_categories as categories')
            ->leftJoin(self::ORDER_TABLE . ' as category_order', 'categories.id', '=', 'category_order.category_id')
            ->whereNull('categories.parent')
            ->where('categories.status', config('settings.general_status.active'))
            ->select(
                'categories.id',
                'categories.name',
                'categories.icon',
                DB::raw('COALESCE(category_order.ordering, 999999) as ordering')
            )
            ->orderBy('ordering')
            ->orderBy('categories.id')
            ->get();
    }

    /**
     * @param array<int,array{category_id:int,ordering:int}> $categoryOrder
     */
    public function saveSettings(bool $isListViewEnabled, bool $organiseByCategory, array $categoryOrder = []): bool
    {
        if (!$this->tablesExist()) {
            return false;
        }

        try {
            DB::transaction(function () use ($isListViewEnabled, $organiseByCategory, $categoryOrder) {
                DB::table(self::SETTINGS_TABLE)->updateOrInsert(
                    ['id' => self::SETTINGS_ID],
                    [
                        'is_list_view_enabled' => $isListViewEnabled ? 1 : 0,
                        'organise_by_category' => $organiseByCategory ? 1 : 0,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                DB::table(self::ORDER_TABLE)->delete();

                if (!$isListViewEnabled || !$organiseByCategory) {
                    return;
                }

                $validCategoryIds = DB::table('tl_com_categories')
                    ->whereNull('parent')
                    ->where('status', config('settings.general_status.active'))
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $validCategoryIdLookup = array_flip($validCategoryIds);

                foreach ($categoryOrder as $index => $item) {
                    $categoryId = (int) ($item['category_id'] ?? 0);

                    if ($categoryId <= 0 || !isset($validCategoryIdLookup[$categoryId])) {
                        continue;
                    }

                    DB::table(self::ORDER_TABLE)->insert([
                        'category_id' => $categoryId,
                        'ordering' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            $this->clearCache();

            return true;
        } catch (\Exception $e) {
            \Log::error('Product list view save failed: ' . $e->getMessage());

            return false;
        }
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey());
    }

    private function seedDefaultSettings(): void
    {
        DB::table(self::SETTINGS_TABLE)->insert([
            'id' => self::SETTINGS_ID,
            'is_list_view_enabled' => 0,
            'organise_by_category' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function defaultSettingsObject(): object
    {
        return (object) [
            'is_list_view_enabled' => 0,
            'organise_by_category' => 0,
        ];
    }

    private function tablesExist(): bool
    {
        return Schema::hasTable(self::SETTINGS_TABLE)
            && Schema::hasTable(self::ORDER_TABLE);
    }

    private function cacheKey(): string
    {
        return tenantCacheKey(self::CACHE_KEY);
    }
}
