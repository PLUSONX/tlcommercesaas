<?php

namespace Theme\TLCommerce\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Theme\TLCommerce\Models\Sliders;
use Illuminate\Support\Facades\Cache;
use Theme\TLCommerce\Http\Resources\SliderResource;

class SliderController extends Controller
{
    /**
     * Will return active sliders
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function sliders()
    {
        return response()->json($this->slidersPayload());
    }

    /**
     * Resolved sliders JSON for the API and Blade bootstrap.
     *
     * @return array
     */
    public function slidersPayload(): array
    {
        $cached = Cache::get(tenantCacheKey('home-page-sliders-resource'));

        if ($cached instanceof SliderResource) {
            $payload = $this->resourceToArray($cached);
            Cache::forever(tenantCacheKey('home-page-sliders-resource'), $payload);
            return $payload;
        }

        if (is_array($cached) && array_key_exists('data', $cached)) {
            return $cached;
        }

        $payload = $this->buildSlidersPayload();
        Cache::forever(tenantCacheKey('home-page-sliders-resource'), $payload);

        return $payload;
    }

    /**
     * @return array
     */
    private function buildSlidersPayload(): array
    {
        $resource = new SliderResource(
            Sliders::select('url', 'desktop', 'mobile')
                ->where('status', config('settings.general_status.active'))
                ->get()
        );

        return $this->resourceToArray($resource);
    }

    /**
     * @param \Illuminate\Http\Resources\Json\JsonResource $resource
     * @return array
     */
    private function resourceToArray($resource): array
    {
        return json_decode($resource->toResponse(request())->getContent(), true) ?: [
            'data' => [],
            'success' => true,
            'status' => 200,
        ];
    }
}
