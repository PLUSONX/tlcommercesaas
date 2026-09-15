<?php

namespace Plugin\TlcommerceCore\Services;

use Illuminate\Support\Facades\Log;
use Plugin\TlcommerceCore\Models\Orders;
use Plugin\TlcommerceCore\Models\Product;
use Plugin\TlcommerceCore\Models\ProductAttribute;
use Plugin\TlcommerceCore\Models\OrderHasProducts;
use Plugin\TlcommerceCore\Models\SingleProductPrice;
use Plugin\TlcommerceCore\Models\VariantProductPrice;

class OrderStockService
{
    public function deductIfPaidAndDelivered(int $orderId): string
    {
        $connection = Orders::query()->getConnection();

        $callback = function () use ($orderId) {

            /*
            |--------------------------------------------------------------------------
            | Lock order
            |--------------------------------------------------------------------------
            */
            $order = Orders::where('id', $orderId)
                ->lockForUpdate()
                ->first();

            if (!$order) {
                throw new \RuntimeException(
                    'Order #' . $orderId . ' not found.'
                );
            }

            $paidStatus = (int) config(
                'tlecommercecore.order_payment_status.paid'
            );

            $deliveredStatus = (int) config(
                'tlecommercecore.order_delivery_status.delivered'
            );

            /*
            |--------------------------------------------------------------------------
            | Only Paid + Delivered
            |--------------------------------------------------------------------------
            */
            if (
                (int) $order->payment_status !== $paidStatus ||
                (int) $order->delivery_status !== $deliveredStatus
            ) {
                return 'not_ready';
            }

            /*
            |--------------------------------------------------------------------------
            | ONE-TIME protection
            |--------------------------------------------------------------------------
            */
            if (!empty($order->stock_deducted_at)) {

                Log::info('Inventory already deducted', [
                    'order_id' => $orderId,
                    'stock_deducted_at' => $order->stock_deducted_at,
                ]);

                return 'already_deducted';
            }

            /*
            |--------------------------------------------------------------------------
            | Load and lock complete order
            |--------------------------------------------------------------------------
            */
            $items = OrderHasProducts::where('order_id', $orderId)
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                throw new \RuntimeException(
                    'No products found for order #' . $orderId . '.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Every item must also be Paid + Delivered
            |--------------------------------------------------------------------------
            */
            foreach ($items as $item) {

                if (
                    (int) $item->payment_status !== $paidStatus ||
                    (int) $item->delivery_status !== $deliveredStatus
                ) {
                    return 'not_ready';
                }
            }

            $productIds = $items
                ->pluck('product_id')
                ->unique()
                ->values();

            $products = Product::whereIn('id', $productIds)
                ->select([
                    'id',
                    'has_variant',
                ])
                ->get()
                ->keyBy('id');

            /*
            |--------------------------------------------------------------------------
            | Build deduction plan
            |--------------------------------------------------------------------------
            |
            | We aggregate quantities first.
            |
            | Therefore if the SAME simple product or SAME variant somehow
            | appears more than once in the order, its quantities are summed.
            |
            */
            $deductions = [];

            foreach ($items as $item) {

                $productId = (int) $item->product_id;
                $orderedQuantity = (int) $item->quantity;

                if ($orderedQuantity <= 0) {
                    throw new \RuntimeException(
                        'Invalid quantity on order item #' .
                        $item->id . '.'
                    );
                }

                $product = $products->get($productId);

                if (!$product) {
                    throw new \RuntimeException(
                        'Product #' . $productId .
                        ' was not found.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | VARIABLE PRODUCT
                |--------------------------------------------------------------------------
                */
                if (
                    (int) $product->has_variant ===
                    (int) config(
                        'tlecommercecore.product_variant.variable'
                    )
                ) {

                    $variantCode = trim(
                        (string) ($item->variant_code ?? ''),
                        '/'
                    );

                    /*
                    | Never fall back to single stock.
                    |
                    | If a product is variable but variant code is missing,
                    | fail the entire operation safely.
                    */
                    if ($variantCode === '') {
                        throw new \RuntimeException(
                            'Variant code missing for order item #' .
                            $item->id .
                            '. Inventory was not deducted.'
                        );
                    }

                    /*
                    | Resolve using the same TLCommerce mechanism already
                    | used during checkout.
                    */
                    $resolvedVariant =
                        ProductAttribute::resolveVariantPrice(
                            $productId,
                            $variantCode
                        );

                    if (!$resolvedVariant) {
                        throw new \RuntimeException(
                            'Variant "' .
                            $variantCode .
                            '" could not be resolved for product #' .
                            $productId . '.'
                        );
                    }

                    $key = 'variant:' . $resolvedVariant->id;

                    if (!isset($deductions[$key])) {
                        $deductions[$key] = [
                            'type' => 'variant',
                            'product_id' => $productId,
                            'variant_id' => (int) $resolvedVariant->id,
                            'variant_code' => $variantCode,
                            'quantity' => 0,
                        ];
                    }

                    $deductions[$key]['quantity'] +=
                        $orderedQuantity;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SINGLE PRODUCT
                |--------------------------------------------------------------------------
                */
                if (
                    (int) $product->has_variant ===
                    (int) config(
                        'tlecommercecore.product_variant.single'
                    )
                ) {

                    $key = 'single:' . $productId;

                    if (!isset($deductions[$key])) {
                        $deductions[$key] = [
                            'type' => 'single',
                            'product_id' => $productId,
                            'quantity' => 0,
                        ];
                    }

                    $deductions[$key]['quantity'] +=
                        $orderedQuantity;

                    continue;
                }

                throw new \RuntimeException(
                    'Unknown product type for product #' .
                    $productId . '.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Execute deductions
            |--------------------------------------------------------------------------
            */
            foreach ($deductions as $deduction) {

                $requiredQuantity =
                    (int) $deduction['quantity'];

                /*
                |--------------------------------------------------------------------------
                | VARIANT INVENTORY
                |--------------------------------------------------------------------------
                */
                if ($deduction['type'] === 'variant') {

                    $stock = VariantProductPrice::where(
                            'id',
                            $deduction['variant_id']
                        )
                        ->where(
                            'product_id',
                            $deduction['product_id']
                        )
                        ->lockForUpdate()
                        ->first();

                    if (!$stock) {
                        throw new \RuntimeException(
                            'Variant stock row not found for product #' .
                            $deduction['product_id'] .
                            ', variant ' .
                            $deduction['variant_code'] .
                            '.'
                        );
                    }

                    $before = (int) $stock->quantity;

                    if ($before < $requiredQuantity) {
                        throw new \RuntimeException(
                            'Insufficient stock for product #' .
                            $deduction['product_id'] .
                            ', variant ' .
                            $deduction['variant_code'] .
                            '. Available: ' .
                            $before .
                            ', required: ' .
                            $requiredQuantity .
                            '.'
                        );
                    }

                    $stock->quantity =
                        $before - $requiredQuantity;

                    $stock->save();

                    Log::info('Variant stock deducted', [
                        'order_id' => $orderId,
                        'product_id' =>
                            $deduction['product_id'],
                        'variant_id' =>
                            $stock->id,
                        'variant_code' =>
                            $deduction['variant_code'],
                        'ordered_quantity' =>
                            $requiredQuantity,
                        'before' => $before,
                        'after' => $stock->quantity,
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPLE PRODUCT INVENTORY
                |--------------------------------------------------------------------------
                */
                $stock = SingleProductPrice::where(
                        'product_id',
                        $deduction['product_id']
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$stock) {
                    throw new \RuntimeException(
                        'Single-product stock not found for product #' .
                        $deduction['product_id'] .
                        '.'
                    );
                }

                $before = (int) $stock->quantity;

                if ($before < $requiredQuantity) {
                    throw new \RuntimeException(
                        'Insufficient stock for product #' .
                        $deduction['product_id'] .
                        '. Available: ' .
                        $before .
                        ', required: ' .
                        $requiredQuantity .
                        '.'
                    );
                }

                $stock->quantity =
                    $before - $requiredQuantity;

                $stock->save();

                Log::info('Single stock deducted', [
                    'order_id' => $orderId,
                    'product_id' =>
                        $deduction['product_id'],
                    'ordered_quantity' =>
                        $requiredQuantity,
                    'before' => $before,
                    'after' => $stock->quantity,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Mark order as completed
            |--------------------------------------------------------------------------
            |
            | ONLY after every simple/variant stock update succeeded.
            |
            */
            $order->stock_deducted_at = now();
            $order->save();

            Log::info(
                'Order inventory deduction completed',
                [
                    'order_id' => $orderId,
                    'deduction_count' =>
                        count($deductions),
                ]
            );

            return 'deducted';
        };

        /*
        |--------------------------------------------------------------------------
        | Avoid unnecessary nested transaction
        |--------------------------------------------------------------------------
        */
        if ($connection->transactionLevel() > 0) {
            return $callback();
        }

        return $connection->transaction($callback);
    }
}