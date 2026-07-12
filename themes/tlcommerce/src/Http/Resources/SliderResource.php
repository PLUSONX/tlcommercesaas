<?php

namespace Theme\TLCommerce\Http\Resources;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SliderResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'url' => $data->url,
                    'desktop' => Cache::rememberForever('home-page-desktop-slider-display-v1-' . $data->desktop, function () use ($data) {
                        return getDisplayImagePath($data->desktop, 1600, false);
                    }),
                    'mobile'
                    => Cache::rememberForever('home-page-mobile-slider-display-v1-' . $data->mobile, function () use ($data) {
                        return getDisplayImagePath($data->mobile, 800, false);
                    }),
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
