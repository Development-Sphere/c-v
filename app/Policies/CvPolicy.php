<?php

namespace App\Policies;

use App\Models\Cv;
use App\Models\User;
use App\Support\GuestIdentity;
use Illuminate\Http\Request;

class CvPolicy
{
    public function __construct(
        private readonly GuestIdentity $guestIdentity,
        private readonly Request $request,
    ) {}

    public function view(?User $user, Cv $cv): bool
    {
        return $this->owns($user, $cv);
    }

    public function update(?User $user, Cv $cv): bool
    {
        return $this->owns($user, $cv);
    }

    public function delete(?User $user, Cv $cv): bool
    {
        return $this->owns($user, $cv);
    }

    private function owns(?User $user, Cv $cv): bool
    {
        if ($user) {
            return $cv->user_id === $user->id;
        }

        if (! $cv->session_id) {
            return false;
        }

        return hash_equals($cv->session_id, $this->guestIdentity->tokenFrom($this->request) ?? '');
    }
}
