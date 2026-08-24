<?php

namespace Tests\Feature;

use App\Livewire\CvBuilder;
use App\Models\Cv;
use App\Models\CvTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CvBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CvTemplate::create(['key' => 'modern', 'name' => 'Modern', 'sort_order' => 1]);
        CvTemplate::create(['key' => 'classic', 'name' => 'Classic', 'sort_order' => 2]);
    }

    public function test_it_hydrates_from_the_given_cv(): void
    {
        $cv = Cv::factory()->create([
            'personal_info' => ['name' => 'Ada Lovelace', 'email' => 'ada@example.com', 'phone' => '', 'location' => '', 'links' => []],
        ]);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->assertSet('personalInfo.name', 'Ada Lovelace')
            ->assertSet('personalInfo.email', 'ada@example.com');
    }

    public function test_updating_personal_info_autosaves_to_the_database(): void
    {
        $cv = Cv::factory()->create();

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('personalInfo.name', 'Grace Hopper')
            ->set('personalInfo.email', 'grace@example.com');

        $this->assertSame('Grace Hopper', $cv->fresh()->personal_info['name']);
        $this->assertSame('grace@example.com', $cv->fresh()->personal_info['email']);
    }

    public function test_title_is_auto_filled_from_name_once(): void
    {
        $cv = Cv::factory()->create(['title' => 'Untitled CV']);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('personalInfo.name', 'Grace Hopper');

        $this->assertSame("Grace Hopper's CV", $cv->fresh()->title);
    }

    public function test_invalid_email_shows_a_validation_error_but_still_persists_other_data(): void
    {
        $cv = Cv::factory()->create();

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('personalInfo.email', 'not-an-email')
            ->assertHasErrors('personalInfo.email');
    }

    public function test_skills_can_be_added_and_removed(): void
    {
        $cv = Cv::factory()->create();

        $component = Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('newSkill', 'Laravel')
            ->call('addSkill')
            ->set('newSkill', 'PHP')
            ->call('addSkill');

        $component->assertSet('skills', ['Laravel', 'PHP']);
        $this->assertSame(['Laravel', 'PHP'], $cv->fresh()->skills);

        $component->call('removeSkill', 0);
        $component->assertSet('skills', ['PHP']);
        $this->assertSame(['PHP'], $cv->fresh()->skills);
    }

    public function test_experience_entries_can_be_added_and_reordered_preserving_data(): void
    {
        $cv = Cv::factory()->create();

        $component = Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('addExperience')
            ->set('experience.0.title', 'First Role')
            ->call('addExperience')
            ->set('experience.1.title', 'Second Role')
            ->call('addExperience')
            ->set('experience.2.title', 'Third Role');

        $component->call('moveExperienceUp', 1);

        $component->assertSet('experience.0.title', 'Second Role');
        $component->assertSet('experience.1.title', 'First Role');
        $component->assertSet('experience.2.title', 'Third Role');

        $titles = collect($cv->fresh()->experience)->pluck('title')->all();
        $this->assertSame(['Second Role', 'First Role', 'Third Role'], $titles);
    }

    public function test_experience_can_be_removed(): void
    {
        $cv = Cv::factory()->create();

        $component = Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('addExperience')
            ->set('experience.0.title', 'Only Role');

        $component->call('removeExperience', 0);
        $component->assertSet('experience', []);
        $this->assertSame([], $cv->fresh()->experience);
    }

    public function test_step_navigation_stays_within_bounds(): void
    {
        $cv = Cv::factory()->create();

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('previousStep')
            ->assertSet('step', 1)
            ->call('goToStep', 99)
            ->assertSet('step', 1)
            ->call('goToStep', 7)
            ->assertSet('step', 7)
            ->call('nextStep')
            ->assertSet('step', 7);
    }

    public function test_template_selection_persists(): void
    {
        $cv = Cv::factory()->create();

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('selectTemplate', 'classic')
            ->assertSet('template', 'classic');

        $this->assertSame('classic', $cv->fresh()->template);
    }

    public function test_review_step_prompts_guests_to_register(): void
    {
        $cv = Cv::factory()->create();

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('step', 7)
            ->assertSee('Create free account');
    }
}
