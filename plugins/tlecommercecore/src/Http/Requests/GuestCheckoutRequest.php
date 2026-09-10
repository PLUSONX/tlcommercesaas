<?php

namespace Plugin\TlcommerceCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Plugin\TlcommerceCore\Rules\KuwaitMobile;
use Session;

class GuestCheckoutRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        $rules = [];

        if (getEcommerceSetting('enable_personal_info_guest_checkout') == 1) {
            $rules['name'] = 'required|max:250';
            // $rules['email'] = 'required|email|unique:Plugin\TlcommerceCore\Models\Customers,email,' . $request->id;
            $rules['email'] = 'nullable|email|unique:Plugin\TlcommerceCore\Models\Customers,email,' . $request->id;
        }

        if (getEcommerceSetting('create_account_in_guest_checkout') == 1 && getEcommerceSetting('enable_personal_info_guest_checkout') == 1) {
            $rules['password'] = 'required|max:250|confirmed|min:6';
        }

        if (is_array($this->input('shipping_address_data'))) {
            $rules['shipping_address_data.phone'] = ['required', new KuwaitMobile];
        }

        if (is_array($this->input('billing_address_data'))) {
            $rules['billing_address_data.phone'] = ['required', new KuwaitMobile];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->mergeDecodedAddress('shipping_address', 'shipping_address_data');
        $this->mergeDecodedAddress('billing_address', 'billing_address_data');
    }

    private function mergeDecodedAddress(string $sourceKey, string $targetKey): void
    {
        if (!$this->has($sourceKey)) {
            return;
        }

        $value = $this->input($sourceKey);
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $this->merge([$targetKey => $decoded]);
            }
            return;
        }

        if (is_array($value)) {
            $this->merge([$targetKey => $value]);
        }
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => translate('Name is required', Session::get('api_locale')),
            'password.confirmed' => translate('Password does not match', Session::get('api_locale')),
            'email.required' => translate('Email is required', Session::get('api_locale')),
            'email.email' => translate('Incorrect email', Session::get('api_locale')),
            'email.unique' => translate('Email is already used', Session::get('api_locale')),
            'shipping_address_data.phone.required' => translate('Phone is required', Session::get('api_locale')),
            'billing_address_data.phone.required' => translate('Phone is required', Session::get('api_locale')),
        ];
    }
}
