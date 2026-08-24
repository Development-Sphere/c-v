<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

/**
 * Identifies a guest (unauthenticated) visitor across visits so they can
 * return to their in-progress CV without an account. Backed by a long-lived,
 * Laravel-signed/encrypted cookie rather than the PHP session ID, since the
 * session ID rotates on login and expires far sooner than "come back
 * tomorrow" requires.
 */
class GuestIdentity
{
    public const COOKIE = 'cv_guest_token';

    private const LIFETIME_MINUTES = 60 * 24 * 365;

    /**
     * Get the current guest token from the request, if one exists.
     */
    public function tokenFrom(Request $request): ?string
    {
        return $request->cookie(self::COOKIE);
    }

    /**
     * Get the current guest token, generating and queuing a new cookie
     * for it if one doesn't already exist on the request.
     */
    public function resolve(Request $request): string
    {
        $token = $this->tokenFrom($request);

        if ($token) {
            return $token;
        }

        $token = (string) Str::uuid();

        Cookie::queue(Cookie::make(
            self::COOKIE,
            $token,
            self::LIFETIME_MINUTES,
            sameSite: SymfonyCookie::SAMESITE_LAX,
        ));

        return $token;
    }

    /**
     * Forget the guest token cookie, e.g. after its CVs have been
     * handed off to a newly authenticated user.
     */
    public function forget(): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE));
    }
}
