<?php

namespace Plugin\TlcommerceCore\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Plugin\TlcommerceCore\Models\CustomerReview;

class OrderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $orderIds = $this->collection->pluck('id');
        $customerId = $this->collection->first()?->customer_id;

        $reviewedOrderIds = [];
        if ($customerId && $orderIds->isNotEmpty()) {
            $reviewedOrderIds = CustomerReview::where('customer_id', $customerId)
                ->whereIn('order_id', $orderIds)
                ->whereNull('product_id')
                ->pluck('order_id')
                ->flip()
                ->all();
        }

        return [
            'data' => $this->collection->map(function ($data) use ($reviewedOrderIds) {
                $canReview = $data->payment_status == config('tlecommercecore.order_payment_status.paid')
                    && $data->delivery_status == config('tlecommercecore.order_delivery_status.delivered');

                return [
                    'id' => (int) $data->id,
                    'order_code' => $data->order_code,
                    'total_payable_amount' => $data->total_payable_amount,
                    'total_products' => $data->products->sum('quantity'),
                    'order_date' => $data->created_at->format('d M Y h:i:s A'),
                    'payment_status' => (int) $data->payment_status,
                    'delivery_status' => (int) $data->delivery_status,
                    'can_review' => $canReview,
                    'has_review' => isset($reviewedOrderIds[$data->id]),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
