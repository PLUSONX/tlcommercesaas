<?php

namespace Theme\TLCommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SliderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $hasExternalMedia = $this->hasSupportedMediaUrl();

        return [
            'title' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2048'],

            'desktop' => [
                'nullable',
                Rule::requiredIf(!$hasExternalMedia),
            ],

            'mobile' => [
                'nullable',
                Rule::requiredIf(!$hasExternalMedia),
            ],
        ];
    }

    private function hasSupportedMediaUrl(): bool
    {
        $url = trim((string) $this->input('url'));

        if ($url === '' || $url === '/') {
            return false;
        }

        return (bool) preg_match(
            '/(youtube\.com|youtu\.be|vimeo\.com|\.(mp4|webm|ogg|mov|m3u8|jpg|jpeg|png|webp|gif|avif)(\?.*)?$)/i',
            $url
        );
    }

    public function messages()
    {
        return [
            'title.required' => translate('Title is required'),

            'desktop.required' => translate(
                'Desktop image is required when no media URL is provided'
            ),

            'mobile.required' => translate(
                'Mobile image is required when no media URL is provided'
            ),
        ];
    }
}