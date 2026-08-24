<?php

namespace Tests\Feature;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class CvPdfExportTest extends TestCase
{
    use RefreshDatabase;

    #[Group('browsershot')]
    public function test_the_owner_can_export_a_real_pdf(): void
    {
        $user = User::factory()->create();
        $cv = Cv::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('cv.export', $cv));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }
}
