<?php

namespace Tests\Feature;

use App\Models\Cv;
use App\Models\User;
use App\Support\GuestIdentity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Tests\TestCase;

class CvPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_and_update_their_own_cv(): void
    {
        $owner = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $this->assertTrue(Gate::forUser($owner)->allows('view', $cv));
        $this->assertTrue(Gate::forUser($owner)->allows('update', $cv));
    }

    public function test_another_user_cannot_view_or_update_someone_elses_cv(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $this->assertFalse(Gate::forUser($otherUser)->allows('view', $cv));
        $this->assertFalse(Gate::forUser($otherUser)->allows('update', $cv));
    }

    public function test_guest_with_matching_cookie_token_can_view_and_update_their_cv(): void
    {
        $token = (string) Str::uuid();
        $cv = Cv::factory()->guest($token)->create();

        $this->bindGuestRequest($token);

        $this->assertTrue(Gate::forUser(null)->allows('view', $cv));
        $this->assertTrue(Gate::forUser(null)->allows('update', $cv));
    }

    public function test_guest_without_matching_cookie_token_cannot_access_the_cv(): void
    {
        $cv = Cv::factory()->guest((string) Str::uuid())->create();

        $this->bindGuestRequest((string) Str::uuid());

        $this->assertFalse(Gate::forUser(null)->allows('view', $cv));

        $this->bindGuestRequest(null);

        $this->assertFalse(Gate::forUser(null)->allows('view', $cv));
    }

    private function bindGuestRequest(?string $token): void
    {
        $request = Request::create('/');

        if ($token !== null) {
            $request->cookies->set(GuestIdentity::COOKIE, $token);
        }

        $this->app->instance(Request::class, $request);
    }
}
