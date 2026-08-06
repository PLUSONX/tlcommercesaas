<?php

namespace Plugin\TlcommerceCore\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugin\TlcommerceCore\Models\AnalyticsEvent;

class AnalyticsController extends Controller
{
    private const ALLOWED_EVENT_TYPES = [
        'page_view',
        'store_visit',
        'add_to_cart',
        'checkout',
        'content_view',
    ];

    /**
     * Best-effort analytics ingest. Never throws or returns 500 —
     * missing table / DB errors must not affect the storefront.
     */
    public function track(Request $request)
    {
        try {
            $eventType = (string) $request->input('event_type', '');

            if (!in_array($eventType, self::ALLOWED_EVENT_TYPES, true)) {
                return response()->json(['success' => true]);
            }

            $productId = $request->input('product_id');
            if ($productId !== null && $productId !== '') {
                $productId = (int) $productId;
                if ($productId <= 0) {
                    $productId = null;
                }
            } else {
                $productId = null;
            }

            AnalyticsEvent::create([
                'event_type' => $eventType,
                'product_id' => $productId,
            ]);
        } catch (\Throwable $e) {
            // Missing table, connection issues, etc. — swallow silently.
        }

        return response()->json(['success' => true]);
    }
}
