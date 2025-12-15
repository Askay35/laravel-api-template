<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as CookieObject;

class CookieService
{
    /**
     * Create a refresh token cookie.
     *
     * @param string $refreshToken
     * @return CookieObject
     */
    public function createRefreshTokenCookie(string $refreshToken): CookieObject
    {
        return Cookie::make(
            'refresh_token',
            $refreshToken,
            config('auth.refresh_token_lifetime'), // Minutes
            '/',
            null,
            true,   // Secure
            true,   // HttpOnly
            false,  // Raw
            'Strict'
        );
    }
}