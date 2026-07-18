<?php

namespace Plugin\TlcommerceCore\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Plugin\TlcommerceCore\Models\AttributeValues;
use Plugin\TlcommerceCore\Models\ProductHasChoices;
use Plugin\TlcommerceCore\Models\VariantProductPrice;
use Plugin\TlcommerceCore\Models\ProductAttributeTranslation;

class ProductAttribute extends Model
{
    protected $table = "tl_com_attributes";

    protected $casts = [
        'multi_select' => 'boolean',
        'multi_select_limit' => 'integer',
    ];

    public function translation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $attribute_translations = $this->attribute_translations->where('lang', $lang)->first();
        return $attribute_translations != null ? $attribute_translations->$field : $this->$field;
    }

    public function attribute_translations()
    {
        return $this->hasMany(ProductAttributeTranslation::class, 'attribute_id');
    }

    public function attribute_values()
    {
        return $this->hasMany(AttributeValues::class, 'attribute_id');
    }

    /**
     * Remove annotation-only multi-select segments from a variant code.
     *
     * @param int $productId
     * @param string|null $variantCode
     * @return string
     */
    public static function skuVariantCode($productId, $variantCode, $multiSelectIds = null)
    {
        if ($multiSelectIds === null) {
            $multiSelectIds = static::multiSelectChoiceIds($productId);
        }

        return collect(explode('/', (string) $variantCode))
            ->filter()
            ->reject(function ($segment) use ($multiSelectIds) {
                $choiceId = explode(':', $segment, 2)[0];
                return in_array((string) $choiceId, $multiSelectIds, true);
            })
            ->implode('/');
    }

    /**
     * Get annotation-only multi-select choice IDs used by a product.
     *
     * @param int $productId
     * @return array
     */
    protected static function multiSelectChoiceIds($productId)
    {
        $choiceIds = ProductHasChoices::where('product_id', $productId)->pluck('choice_id');

        return static::whereIn('id', $choiceIds)
            ->where('multi_select', true)
            ->pluck('id')
            ->map(function ($id) {
                return (string) $id;
            })
            ->all();
    }

    /**
     * Whether the product has any SKU-driving (non multi-select) choices.
     *
     * @param int $productId
     * @param array|null $multiSelectIds
     * @return bool
     */
    protected static function hasSkuDrivingChoices($productId, $multiSelectIds = null)
    {
        if ($multiSelectIds === null) {
            $multiSelectIds = static::multiSelectChoiceIds($productId);
        }

        $choiceIds = ProductHasChoices::where('product_id', $productId)
            ->pluck('choice_id')
            ->map(function ($id) {
                return (string) $id;
            });

        return $choiceIds->contains(function ($choiceId) use ($multiSelectIds) {
            return !in_array($choiceId, $multiSelectIds, true);
        });
    }

    /**
     * Resolve the SKU row while ignoring annotation-only multi-select choices.
     *
     * @param int $productId
     * @param string|null $variantCode
     * @return VariantProductPrice|null
     */
    public static function resolveVariantPrice($productId, $variantCode)
    {
        $multiSelectIds = static::multiSelectChoiceIds($productId);
        $skuCode = static::skuVariantCode($productId, $variantCode, $multiSelectIds);
        $variants = VariantProductPrice::where('product_id', $productId)
            ->orderBy('id')
            ->get();

        // Incomplete selection: multi-select-only codes must not fall back to the first SKU
        // when the product also has SKU-driving attributes.
        if ($skuCode === '') {
            if (static::hasSkuDrivingChoices($productId, $multiSelectIds)) {
                return null;
            }

            return $variants->first();
        }

        return $variants->first(function ($variant) use ($productId, $skuCode, $multiSelectIds) {
            return static::skuVariantCode($productId, $variant->variant, $multiSelectIds) === $skuCode;
        });
    }
}
