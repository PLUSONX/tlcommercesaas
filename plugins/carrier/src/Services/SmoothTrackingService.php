<?php

namespace Plugin\Carrier\Services;

use Plugin\TlcommerceCore\Models\Orders;
use Plugin\TlcommerceCore\Models\ShippingCourierOrders;

/**
 * Smooth-only tracking/status mapping.
 *
 * This class deliberately does not participate in Armada or Karrix flows.
 * It converts Smooth last-mile states into the existing TLCommerce delivery
 * stages and prepares a live admin snapshot without introducing DB columns.
 */
class SmoothTrackingService
{
    /**
     * Only statuses that represent an actual local workflow transition are mapped.
     * Pre-pick / warehouse / driver-assigned statuses intentionally leave the
     * existing local Ready To Ship state untouched.
     */
    public function localDeliveryStatusFor(string $smoothStatus): ?int
    {
        $status = strtoupper(trim($smoothStatus));

        if (in_array($status, [
            'LM_COLLECTED',
            'LM_DRIVER_ON_THE_WAY',
            'LM_DRIVER_CLOSE_BY',
            'LM_DRIVER_ARRIVED_TO_DROP_OFF',
            'LM_PARTIALLY_DELIVERED',
        ], true)) {
            return (int) config('tlecommercecore.order_delivery_status.shipped');
        }

        if (in_array($status, [
            'LM_DELIVERED',
            'SELF_PICKED_UP',
        ], true)) {
            return (int) config('tlecommercecore.order_delivery_status.delivered');
        }

        if (in_array($status, [
            'CANCELLED',
            'LM_REJECTED',
        ], true)) {
            return (int) config('tlecommercecore.order_delivery_status.cancelled');
        }

        // NEW/RELEASED/PICKING/PICKED/PACKING/PACKED/STAGED/LM_NEW/etc.
        // must not change the existing local workflow automatically.
        return null;
    }

    /**
     * Protect the local order from stale/out-of-order callbacks.
     */
    public function shouldApplyLocalStatus(int $currentStatus, int $targetStatus): bool
    {
        $pending = (int) config('tlecommercecore.order_delivery_status.pending');
        $processing = (int) config('tlecommercecore.order_delivery_status.processing');
        $ready = (int) config('tlecommercecore.order_delivery_status.ready_to_ship');
        $shipped = (int) config('tlecommercecore.order_delivery_status.shipped');
        $delivered = (int) config('tlecommercecore.order_delivery_status.delivered');
        $cancelled = (int) config('tlecommercecore.order_delivery_status.cancelled');

        if ($currentStatus === $targetStatus) {
            return false;
        }

        // A final local state is never moved backwards by a later callback.
        if ($currentStatus === $delivered || $currentStatus === $cancelled) {
            return false;
        }

        // Smooth cancellation/rejection may cancel any non-final local order.
        if ($targetStatus === $cancelled) {
            return true;
        }

        $rank = [
            $pending => 10,
            $processing => 20,
            $ready => 30,
            $shipped => 40,
            $delivered => 50,
        ];

        $currentRank = $rank[$currentStatus] ?? 0;
        $targetRank = $rank[$targetStatus] ?? 0;

        return $targetRank > $currentRank;
    }

    public function smoothStage(string $smoothStatus): string
    {
        $status = strtoupper(trim($smoothStatus));

        if (in_array($status, ['LM_DELIVERED', 'SELF_PICKED_UP'], true)) {
            return 'delivered';
        }

        if (in_array($status, [
            'LM_COLLECTED',
            'LM_DRIVER_ON_THE_WAY',
            'LM_DRIVER_CLOSE_BY',
            'LM_DRIVER_ARRIVED_TO_DROP_OFF',
            'LM_PARTIALLY_DELIVERED',
        ], true)) {
            return 'shipped';
        }

        if (in_array($status, ['CANCELLED', 'LM_REJECTED'], true)) {
            return 'cancelled';
        }

        return 'ready_to_ship';
    }

    public function statusLabel(string $status): string
    {
        $label = trim(str_replace('_', ' ', strtoupper(trim($status))));
        return $label !== '' ? $label : 'UNKNOWN';
    }

    /**
     * Build the Smooth-only live admin payload from GET /orders/{store}/{reference}/.
     * The parser is intentionally tolerant because Smooth can add read-only fields
     * without requiring a local schema change.
     */
    public function buildLiveAdminPayload(
        array $payload,
        ShippingCourierOrders $courierOrder,
        Orders $order
    ): array {
        $shipment = is_array($payload['shipment'] ?? null) ? $payload['shipment'] : [];
        $address = is_array($payload['shipping_address'] ?? null) ? $payload['shipping_address'] : [];
        $lines = is_array($payload['order_lines'] ?? null) ? $payload['order_lines'] : [];
        $events = is_array($shipment['events'] ?? null)
            ? $shipment['events']
            : (is_array($payload['events'] ?? null) ? $payload['events'] : []);

        $status = strtoupper(trim((string) (
            $payload['status']
            ?? $shipment['status']
            ?? $courierOrder->status
            ?? ''
        )));

        $trackingId = $payload['tracking_id']
            ?? $shipment['tracking_id']
            ?? $courierOrder->code
            ?? null;

        $trackingUrl = $payload['tracking_url']
            ?? $shipment['tracking_url']
            ?? $courierOrder->tracking_url
            ?? null;

        $routeId = $payload['route_id']
            ?? $shipment['route_id']
            ?? null;

        $driverName = $payload['driver_name']
            ?? $shipment['driver_name']
            ?? $courierOrder->driver_name
            ?? null;

        $driverPhone = $payload['driver_number']
            ?? $payload['driver_phone']
            ?? $shipment['driver_number']
            ?? $shipment['driver_phone']
            ?? $courierOrder->driver_phone
            ?? null;

        $normalizedLines = [];
        foreach ($lines as $line) {
            if (!is_array($line)) {
                continue;
            }

            $normalizedLines[] = [
                'line_id' => $line['line_id'] ?? $line['id'] ?? null,
                'sku' => $line['sku'] ?? data_get($line, 'product.sku') ?? null,
                'name' => $line['name']
                    ?? $line['description']
                    ?? data_get($line, 'product.name')
                    ?? data_get($line, 'product.description')
                    ?? null,
                'quantity' => $line['quantity'] ?? null,
                'unit_of_measure' => $line['unit_of_measure'] ?? $line['uom'] ?? null,
                'unit_price_net' => $line['unit_price_net'] ?? null,
            ];
        }

        $normalizedEvents = [];
        foreach ($events as $event) {
            if (!is_array($event)) {
                continue;
            }

            $eventStatus = strtoupper(trim((string) ($event['status'] ?? '')));
            $normalizedEvents[] = [
                'status' => $eventStatus,
                'label' => $this->statusLabel($eventStatus),
                'created' => $event['created'] ?? $event['created_at'] ?? null,
            ];
        }

        return [
            'provider' => 'smooth',
            'live' => true,
            'source' => 'smooth_api',
            'order_id' => (int) $order->id,
            'order_code' => (string) $order->order_code,
            'courier_order_id' => (int) $courierOrder->id,
            'shipping_courier_id' => (int) $courierOrder->shipping_courier_id,
            'reference_id' => $payload['reference_id'] ?? $order->order_code,
            'status' => $status,
            'status_label' => $this->statusLabel($status),
            'smooth_stage' => $this->smoothStage($status),
            'local_delivery_status' => (int) $order->fresh()->delivery_status,
            'local_delivery_status_label' => $order->fresh()->delivery_status_label(),
            'processing_status' => $payload['processing_status'] ?? null,
            'tracking_id' => $trackingId,
            'tracking_url' => $trackingUrl,
            'pickup_tracking_url' => $payload['pickup_tracking_url'] ?? null,
            'route_id' => $routeId,
            'driver_name' => $driverName,
            'driver_phone' => $driverPhone,
            'delivery_partner' => $payload['delivery_partner'] ?? null,
            'order_type' => $payload['order_type'] ?? null,
            'delivery_mode' => $payload['delivery_mode'] ?? null,
            'payment_status' => $payload['payment_status'] ?? null,
            'payment_method' => $payload['payment_method'] ?? null,
            'amount' => $payload['amount'] ?? $courierOrder->amount,
            'delivery_fee' => $payload['delivery_fee'] ?? $courierOrder->delivery_fee,
            'currency' => $courierOrder->currency,
            'dispatch_time' => $payload['dispatch_time'] ?? null,
            'delivery_time' => $payload['delivery_time'] ?? $shipment['delivery_time'] ?? null,
            'created' => $payload['created'] ?? null,
            'confirmed' => $payload['confirmed'] ?? null,
            'store' => $payload['store'] ?? null,
            'store_name' => $payload['store_name'] ?? null,
            'shipment_reference' => $payload['shipment_reference']
                ?? $shipment['reference_id']
                ?? null,
            'shipment_status' => $shipment['status'] ?? null,
            'address' => [
                'name' => trim((string) (($address['first_name'] ?? '') . ' ' . ($address['last_name'] ?? ''))),
                'phone' => $address['phone'] ?? null,
                'email' => $address['email'] ?? null,
                'street_address_1' => $address['street_address_1'] ?? null,
                'street_address_2' => $address['street_address_2'] ?? null,
                'city' => $address['city'] ?? null,
                'state' => $address['state'] ?? null,
                'country' => $address['country'] ?? null,
                'postal_code' => $address['postal_code'] ?? null,
            ],
            'items' => $normalizedLines,
            'events' => $normalizedEvents,
            'last_local_update' => optional($courierOrder->updated_at)->toIso8601String(),
        ];
    }

    public function buildCachedAdminPayload(
        ShippingCourierOrders $courierOrder,
        Orders $order,
        ?string $message = null
    ): array {
        $status = strtoupper(trim((string) ($courierOrder->status ?? '')));

        return [
            'provider' => 'smooth',
            'live' => false,
            'source' => 'local_cache',
            'message' => $message ?: 'Live Smooth data is temporarily unavailable. Showing the last received update.',
            'order_id' => (int) $order->id,
            'order_code' => (string) $order->order_code,
            'courier_order_id' => (int) $courierOrder->id,
            'shipping_courier_id' => (int) $courierOrder->shipping_courier_id,
            'reference_id' => $order->order_code,
            'status' => $status,
            'status_label' => $this->statusLabel($status),
            'smooth_stage' => $this->smoothStage($status),
            'local_delivery_status' => (int) $order->delivery_status,
            'local_delivery_status_label' => $order->delivery_status_label(),
            'processing_status' => null,
            'tracking_id' => $courierOrder->code,
            'tracking_url' => $courierOrder->tracking_url,
            'pickup_tracking_url' => null,
            'route_id' => null,
            'driver_name' => $courierOrder->driver_name,
            'driver_phone' => $courierOrder->driver_phone,
            'delivery_partner' => 'Smooth Logistics',
            'order_type' => null,
            'delivery_mode' => null,
            'payment_status' => null,
            'payment_method' => null,
            'amount' => $courierOrder->amount,
            'delivery_fee' => $courierOrder->delivery_fee,
            'currency' => $courierOrder->currency,
            'dispatch_time' => null,
            'delivery_time' => null,
            'created' => null,
            'confirmed' => null,
            'store' => null,
            'store_name' => null,
            'shipment_reference' => null,
            'shipment_status' => null,
            'address' => [],
            'items' => [],
            'events' => [],
            'last_local_update' => optional($courierOrder->updated_at)->toIso8601String(),
        ];
    }
}
