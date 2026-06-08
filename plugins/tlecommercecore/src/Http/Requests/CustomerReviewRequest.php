<?php

namespace Plugin\TlcommerceCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Session;

class CustomerReviewRequest extends FormRequest
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
        return [
            'order_id' => 'required|integer|exists:tl_com_orders,id',

            'rating' => 'required|numeric|between:1,5',

            'review' => [
                'nullable',
                'string',
                'max:1000',

                // Prevent URLs
                'not_regex:/https?:\/\/|www\.|[a-zA-Z0-9\-]+\.(com|net|org|io|co|xyz|ru|tk)/i',

                // Prevent HTML tags
                'not_regex:/<[^>]*>/',

                // Prevent excessive repeated characters
                'not_regex:/^(.)\1{9,}$/'
            ],
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
            'order_id.required' =>
                translate('Order is required', Session::get('api_locale')),

            'order_id.exists' =>
                translate('Order not found', Session::get('api_locale')),

            'rating.required' =>
                translate('Rating is required', Session::get('api_locale')),

            'rating.between' =>
                translate('The rating must be between 1 and 5', Session::get('api_locale')),

            'review.max' =>
                translate('Review is too long', Session::get('api_locale')),

            'review.not_regex' =>
                translate('Review contains invalid content', Session::get('api_locale')),
        ];
    }
}
