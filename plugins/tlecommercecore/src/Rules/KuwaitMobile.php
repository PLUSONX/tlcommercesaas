<?php

namespace Plugin\TlcommerceCore\Rules;

use Illuminate\Contracts\Validation\Rule;
use Plugin\TlcommerceCore\Support\KuwaitPhone;

class KuwaitMobile implements Rule
{
    public function passes($attribute, $value)
    {
        return KuwaitPhone::isValid($value);
    }

    public function message()
    {
        return translate(KuwaitPhone::ERROR_MESSAGE, session()->get('api_locale'));
    }
}
