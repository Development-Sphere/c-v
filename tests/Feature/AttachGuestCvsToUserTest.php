<?php

namespace Tests\Feature;

use App\Listeners\AttachGuestCvsToUser;
use App\Models\Cv;
use App\Models\User;
use App\Support\GuestIdentity;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AttachGuestCvsToUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cvs_matching_the_cookie_token_are_attached_to_the_new_user(): void
    {
        $token = (string) \Illuminate\Support\Str::uuid();
        $cv = Cv::factory()->guest($token)->create();
        $unrelatedGuestCv = Cv::factory()->guest((string) \Illuminate\Support\Str::uuid())->create();

        $request = Request::create('/register');
        $request->cookies->set(GuestIdentity::COOKIE, $token);
        $this->app->instance(Request::class, $request);

        $user = User::factory()->create();

        $listener = $this->app->make(AttachGuestCvsToUser::class);
        $listener->handleRegistered(new Registered($user));

        $cv->refresh();
        $unrelatedGuestCv->refresh();

        $this->assertSame($user->id, $cv->user_id);
        $this->assertNull($cv->session_id);

        $this->assertNull($unrelatedGuestCv->user_id);
        $this->assertNotNull($unrelatedGuestCv->session_id);
    }

    public function test_it_does_nothing_when_the_visitor_has_no_guest_token(): void
    {
        $request = Request::create('/register');
        $this->app->instance(Request::class, $request);

        $user = User::factory()->create();
        $cv = Cv::factory()->guest()->create();

        $listener = $this->app->make(AttachGuestCvsToUser::class);
        $listener->handleRegistered(new Registered($user));

        $cv->refresh();
        $this->assertNull($cv->user_id);
    }
}
