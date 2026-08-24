<?php

namespace App\Listeners;

use App\Models\Cv;
use App\Support\GuestIdentity;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

/**
 * When a guest who has been building a CV signs up or logs in, hand off
 * any CVs matching their guest cookie token to their new account so they
 * don't lose the in-progress work.
 */
class AttachGuestCvsToUser
{
    public function __construct(
        private readonly GuestIdentity $guestIdentity,
        private readonly Request $request,
    ) {}

    public function handleRegistered(Registered $event): void
    {
        $this->attach($event->user);
    }

    public function handleLogin(Login $event): void
    {
        $this->attach($event->user);
    }

    private function attach(Authenticatable $user): void
    {
        $token = $this->guestIdentity->tokenFrom($this->request);

        if (! $token) {
            return;
        }

        Cv::query()
            ->whereNull('user_id')
            ->where('session_id', $token)
            ->update([
                'user_id' => $user->getAuthIdentifier(),
                'session_id' => null,
            ]);

        $this->guestIdentity->forget();
    }
}
