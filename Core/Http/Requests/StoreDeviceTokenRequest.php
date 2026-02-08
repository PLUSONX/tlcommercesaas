<?php

namespace Core\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint before authentication
    }

    public function rules(): array
    {
        return [
            'old_token' => 'nullable|string|max:500',
            'new_token' => 'required|string|max:500',
            'platform' => 'required|string|in:ios,android,web',
            'updated_at' => 'nullable|date',
        ];
    }
}