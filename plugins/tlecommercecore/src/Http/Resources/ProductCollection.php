<?php

namespace Plugin\TlcommerceCore\Http\Resources;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'has_variant' => (int) $data->has_variant,
                    'name' => $data->translation('name', session()->get('api_locale')),
                    'slug' => $data->permalink,
                    'thumbnail_image' => getFilePathWithSize($data->thumbnail_image, true, '250x250'),
                    'base_price' => (float) $this->base_price($data),
                    'price' => $data->unit_price,
                    'discount' => $this->productDiscount($data),
                    'quantity' => (float) $this->stock($data),
                    'unit' => $data->unit_info != null ? $data->unit_info->translation('name', session()->get('api_locale')) : null,
                    'min_qty' => $data->min_item_on_purchase != null ? $data->min_item_on_purchase : 0,
                    'max_qty' => $data->max_item_on_purchase != null ? $data->max_item_on_purchase : 0,
                    'total_reviews' => $this->rating($data),
                    'avg_rating' => $this->avgRating($data),
                    'seller' => $data->supplier,
                    'shop' => isActivePluging('multivendor') && $data->seller != null ? $data->seller->shop : null,
                    'low_stock_quantity_alert' => (int) ($data->low_stock_quantity_alert ?? 0),
                    'is_low_stock' => $this->isLowStock($data),
                    'is_out_of_stock' => $this->isOutOfStock($data),
                ];
            })
        ];
    }

    public function productDiscount($data)
    {
        $discount = Cache::remember('product-discount-' . $data->name, 60 * 60, function () use ($data) {
            return  $data->applicableDiscount();
        });
        return $discount;
    }

    public function rating($data)
    {
        $total_rating = count($data->reviews);
        return $total_rating;
    }
    public function avgRating($data)
    {
        $avg = $data->reviews->avg('rating');
        return $avg != null ? $avg : 0;
    }
    public function base_price($data)
    {
        if ($data->has_variant == config('tlecommercecore.product_variant.single')) {
            return $data->single_price != null ? $data->single_price->unit_price : 0;
        } else {
            return $data->variations != null ? $data->variations[0]->unit_price : 0;
        }
    }
    public function stock($data)
    {
        if ($data->has_variant == config('tlecommercecore.product_variant.single')) {
            return $data->single_price != null ? $data->single_price->quantity : 0;
        } else {
            return $data->variations != null ? array_reduce($data->variations->toArray(), function ($qty, $item) {
                $qty += $item['quantity'];
                return $qty;
            }) : 0;
        }
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    public function isLowStock($data)
{
    $threshold = (int) (
        $data->low_stock_quantity_alert ?? 0
    );

    if ($threshold <= 0) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Simple product
    |--------------------------------------------------------------------------
    */
    if (
        (int) $data->has_variant ===
        (int) config(
            'tlecommercecore.product_variant.single'
        )
    ) {
        $quantity = $data->single_price != null
            ? (int) $data->single_price->quantity
            : 0;

        return (
            $quantity > 0 &&
            $quantity <= $threshold
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Variant product
    |--------------------------------------------------------------------------
    |
    | Product is Low Stock if AT LEAST ONE variant is low.
    |
    */
    if (
        $data->variations == null ||
        $data->variations->isEmpty()
    ) {
        return false;
    }

    return $data->variations->contains(
        function ($variation) use ($threshold) {

            $quantity = (int) $variation->quantity;

            return (
                $quantity > 0 &&
                $quantity <= $threshold
            );
        }
    );
}

public function isOutOfStock($data)
{
    /*
    |--------------------------------------------------------------------------
    | Simple product
    |--------------------------------------------------------------------------
    */
    if (
        (int) $data->has_variant ===
        (int) config(
            'tlecommercecore.product_variant.single'
        )
    ) {
        return (
            $data->single_price == null ||
            (int) $data->single_price->quantity <= 0
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Variant product
    |--------------------------------------------------------------------------
    | Out of stock only when ALL variants are out.
    */
    if (
        $data->variations == null ||
        $data->variations->isEmpty()
    ) {
        return true;
    }

    return $data->variations->every(
        function ($variation) {
            return (int) $variation->quantity <= 0;
        }
    );
}
}
