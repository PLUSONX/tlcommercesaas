<?php

namespace Plugin\TlcommerceCore\Support;

class KuwaitPhone
{
    public const ERROR_MESSAGE = 'Please enter a valid Kuwait mobile number (8 digits starting with 5, 6, or 9).';

    /**
     * Normalize a Kuwait mobile number to 8 local digits (5/6/9xxxxxxx).
     */
    public static function normalize($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $value);
        if ($digits === null || $digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00965')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '965')) {
            $digits = substr($digits, 3);
        }

        if (strlen($digits) === 8 && preg_match('/^[569]/', $digits)) {
            return $digits;
        }

        return null;
    }

    public static function isValid($value): bool
    {
        return self::normalize($value) !== null;
    }
}
