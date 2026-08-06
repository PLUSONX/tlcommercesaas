<?php

namespace Core\Http\Requests;

use Core\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class UserRequest extends FormRequest
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
    public function rules()
    {
        $emailRule = empty(request('id'))
            ? 'required|email|unique:tl_users,email'
            : 'required|email|unique:tl_users,email,' . request('id');

        if (empty(request('id'))) {
            return [
                'name' => 'required|unique:tl_users,name,' . request('id'),
                'email' => $emailRule,
                'pro_pic' => 'nullable',
                'role' => 'required',
                'password' => 'required|confirmed|min:6'
            ];
        } else {
            if (!empty(request('is_for_profile'))) {
                if (!empty(request('password')) || !empty(request('password_confirmation')) || !empty(request('old_password'))) {
                    return [
                        'name' => 'required|unique:tl_users,name,' . request('id'),
                        'email' => $emailRule,
                        'bio' => 'max:200',
                        'pro_pic' => 'nullable',
                        'password' => 'required|confirmed|min:6',
                        'old_password' => 'required|min:6'
                    ];
                } else {
                    return [
                        'name' => 'required|unique:tl_users,name,' . request('id'),
                        'email' => $emailRule,
                        'bio' => 'max:200',
                        'pro_pic' => 'nullable'
                    ];
                }
            } else {
                return [
                    'name' => 'required|unique:tl_users,name,' . request('id'),
                    'email' => $emailRule,
                    'pro_pic' => 'nullable'
                ];
            }
        }
    }

    /**
     * Ensure email is unique across central tl_users and tl_store_users
     * (required for single-login-page resolution by email).
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $email = $this->input('email');
            if (empty($email) || $validator->errors()->has('email')) {
                return;
            }

            $ignoreUid = null;
            $ignoreCentralUserId = null;

            if (!empty($this->input('id'))) {
                $user = User::find($this->input('id'));
                if ($user) {
                    $ignoreUid = $user->uid;
                    if (!tenancy()->initialized) {
                        $ignoreCentralUserId = $user->id;
                    }
                }
            }

            $centralUsersQuery = DB::connection('mysql')
                ->table('tl_users')
                ->where('email', $email);

            if ($ignoreCentralUserId) {
                $centralUsersQuery->where('id', '!=', $ignoreCentralUserId);
            }

            if ($centralUsersQuery->exists()) {
                $validator->errors()->add('email', translate('This email is already in use.'));
                return;
            }

            $storeUsersQuery = DB::connection('mysql')
                ->table('tl_store_users')
                ->where('email', $email);

            if ($ignoreUid) {
                $storeUsersQuery->where('uid', '!=', $ignoreUid);
            }

            if ($storeUsersQuery->exists()) {
                $validator->errors()->add(
                    'email',
                    translate('This email is already being used in another store.')
                );
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'pro_pic.required' => translate('Profile pic is required'),
            'pro_pic.mimes' => translate('Invalid selection'),
            'email.unique' => translate('This email is already in use.'),
            'email.email' => translate('Please enter a valid email address.'),
        ];
    }
}
