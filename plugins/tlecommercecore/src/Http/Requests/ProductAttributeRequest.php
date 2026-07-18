<?php

namespace Plugin\TlcommerceCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'multi_select' => $this->boolean('multi_select'),
        ]);

        if (!$this->boolean('multi_select')) {
            $this->merge([
                'multi_select_limit' => null,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'multi_select' => 'boolean',
            'multi_select_limit' => 'nullable|required_if:multi_select,1,true|integer|min:1',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => translate('Name is required'),
            'multi_select_limit.required_if' => translate('Multi select limit is required'),
            'multi_select_limit.integer' => translate('Multi select limit must be an integer'),
            'multi_select_limit.min' => translate('Multi select limit must be at least 1'),
        ];
    }
}
