<?php

namespace Tests\Feature;

use App\Models\Cv;
use App\Models\User;
use App\Support\GuestIdentity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CvControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_create_a_cv_and_is_issued_a_guest_cookie(): void
    {
        $response = $this->post(route('cv.store'));

        $cv = Cv::first();

        $this->assertNotNull($cv);
        $this->assertNull($cv->user_id);
        $this->assertNotNull($cv->session_id);
        $this->assertSame('Untitled CV', $cv->title);

        $response->assertRedirect(route('cv.edit', $cv));
        $response->assertCookie(GuestIdentity::COOKIE);
    }

    public function test_an_authenticated_user_can_create_a_cv_owned_by_them(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('cv.store'));

        $cv = Cv::first();

        $this->assertSame($user->id, $cv->user_id);
        $this->assertNull($cv->session_id);
        $response->assertRedirect(route('cv.edit', $cv));
    }

    public function test_the_owner_can_open_the_editor(): void
    {
        $user = User::factory()->create();
        $cv = Cv::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('cv.edit', $cv))
            ->assertOk();
    }

    public function test_a_guest_with_the_matching_cookie_can_open_the_editor(): void
    {
        $token = (string) \Illuminate\Support\Str::uuid();
        $cv = Cv::factory()->guest($token)->create();

        $this->withCookie(GuestIdentity::COOKIE, $token)
            ->get(route('cv.edit', $cv))
            ->assertOk();
    }

    public function test_a_different_authenticated_user_cannot_open_someone_elses_editor(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $this->actingAs($otherUser)
            ->get(route('cv.edit', $cv))
            ->assertForbidden();
    }

    public function test_a_guest_without_the_matching_cookie_cannot_open_the_editor(): void
    {
        $cv = Cv::factory()->guest()->create();

        $this->get(route('cv.edit', $cv))
            ->assertForbidden();
    }

    public function test_the_owner_can_view_the_preview(): void
    {
        $user = User::factory()->create();
        $cv = Cv::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('cv.preview', $cv))
            ->assertOk()
            ->assertSee($cv->personal_info['name']);
    }

    public function test_a_guest_with_the_matching_cookie_can_view_the_preview(): void
    {
        $token = (string) \Illuminate\Support\Str::uuid();
        $cv = Cv::factory()->guest($token)->create();

        $this->withCookie(GuestIdentity::COOKIE, $token)
            ->get(route('cv.preview', $cv))
            ->assertOk();
    }

    public function test_a_different_authenticated_user_cannot_view_someone_elses_preview(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $this->actingAs($otherUser)
            ->get(route('cv.preview', $cv))
            ->assertForbidden();
    }

    public function test_a_guest_is_redirected_to_login_when_trying_to_export(): void
    {
        $token = (string) \Illuminate\Support\Str::uuid();
        $cv = Cv::factory()->guest($token)->create();

        $this->withCookie(GuestIdentity::COOKIE, $token)
            ->get(route('cv.export', $cv))
            ->assertRedirect(route('login'));
    }

    public function test_a_different_authenticated_user_cannot_export_someone_elses_cv(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cv = Cv::factory()->for($owner)->create();

        $this->actingAs($otherUser)
            ->get(route('cv.export', $cv))
            ->assertForbidden();
    }
}
