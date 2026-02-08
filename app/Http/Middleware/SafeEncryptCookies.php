<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class SafeEncryptCookies extends Middleware
{
    /**
     * Decrypt the cookies on the request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function decrypt($request)
    {
        foreach ($request->cookies as $key => $cookie) {
            if ($this->isDisabled($key)) {
                continue;
            }

            // PHP 8.3 compatibility: Skip null/empty cookies
            if ($cookie === null || $cookie === '') {
                $request->cookies->remove($key);
                continue;
            }

            try {
                $request->cookies->set($key, $this->decryptCookie($key, $cookie));
            } catch (\Exception $e) {
                // If decryption fails, remove the corrupted cookie
                $request->cookies->remove($key);
            }
        }

        return $request;
    }
}