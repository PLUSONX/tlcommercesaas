<?php

namespace Plugin\TlcommerceCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Session;

class CustomerFeedbackRequest extends FormRequest
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
            'name' => 'nullable|string|max:100',

            'satisfaction_rating' => 'required|integer|between:1,5',

            'phone' => 'nullable|string|max:50',

            'comment' => [
                'nullable',
                'string',
                'max:1000',

                // Prevent URLs
                'not_regex:/https?:\/\/|www\.|[a-zA-Z0-9\-]+\.(com|net|org|io|co|xyz|ru|tk)/i',

                // Prevent HTML tags
                'not_regex:/<[^>]*>/',

                // Prevent excessive repeated characters
                'not_regex:/^(.)\1{9,}$/'
            ]
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
            'satisfaction_rating.required' =>
                translate('Rating is required', Session::get('api_locale')),

            'satisfaction_rating.between' =>
                translate('The rating must be between 1 and 5', Session::get('api_locale')),

            'comment.max' =>
                translate('Comment is too long', Session::get('api_locale')),

            'comment.not_regex' =>
                translate('Comment contains invalid content', Session::get('api_locale')),

            'name.max' =>
                translate('Name is too long', Session::get('api_locale')),

            'phone.max' =>
                translate('Phone number is too long', Session::get('api_locale')),
        ];
    }
}