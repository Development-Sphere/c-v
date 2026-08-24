<?php

namespace Tests\Feature;

use App\Livewire\CvBuilder;
use App\Models\Cv;
use App\Models\CvTemplate;
use App\Services\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
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

    public function test_polishing_the_summary_stores_the_suggestion_and_persists_it(): void
    {
        $cv = Cv::factory()->create(['summary' => ['raw' => 'Backend engineer.', 'polished' => '']]);

        $gemini = Mockery::mock(GeminiService::class);
        $gemini->shouldReceive('polishSummary')->once()->with('Backend engineer.')->andReturn('A backend engineer with a track record of delivery.');
        $this->instance(GeminiService::class, $gemini);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('polishSummary')
            ->assertSet('summary.polished', 'A backend engineer with a track record of delivery.')
            ->assertSet('aiError', null);

        $this->assertSame('A backend engineer with a track record of delivery.', $cv->fresh()->summary['polished']);
    }

    public function test_polish_summary_failure_sets_an_error_and_leaves_summary_untouched(): void
    {
        $cv = Cv::factory()->create(['summary' => ['raw' => 'Backend engineer.', 'polished' => '']]);

        $gemini = Mockery::mock(GeminiService::class);
        $gemini->shouldReceive('polishSummary')->once()->andReturn(null);
        $this->instance(GeminiService::class, $gemini);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('polishSummary')
            ->assertSet('summary.polished', '')
            ->assertSet('aiError', fn ($error) => filled($error));
    }

    public function test_accepting_the_polished_summary_replaces_raw_and_clears_the_suggestion(): void
    {
        $cv = Cv::factory()->create(['summary' => ['raw' => 'Backend engineer.', 'polished' => 'A backend engineer with impact.']]);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('summary.polished', 'A backend engineer with impact.')
            ->call('acceptPolishedSummary')
            ->assertSet('summary.raw', 'A backend engineer with impact.')
            ->assertSet('summary.polished', '');

        $this->assertSame('A backend engineer with impact.', $cv->fresh()->summary['raw']);
    }

    public function test_discarding_the_polished_summary_leaves_raw_untouched(): void
    {
        $cv = Cv::factory()->create(['summary' => ['raw' => 'Backend engineer.', 'polished' => '']]);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('summary.polished', 'Suggested text')
            ->call('discardPolishedSummary')
            ->assertSet('summary.raw', 'Backend engineer.')
            ->assertSet('summary.polished', '');
    }

    public function test_improving_bullets_stores_a_transient_suggestion_without_persisting(): void
    {
        $cv = Cv::factory()->create();

        $gemini = Mockery::mock(GeminiService::class);
        $gemini->shouldReceive('improveBullets')
            ->once()
            ->with(['Did some work.'], 'Engineer')
            ->andReturn(['Delivered measurable engineering impact.']);
        $this->instance(GeminiService::class, $gemini);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('addExperience')
            ->set('experience.0.title', 'Engineer')
            ->set('experience.0.bullets.0', 'Did some work.')
            ->call('improveBullets', 0)
            ->assertSet('improvedBullets.0', ['Delivered measurable engineering impact.']);

        // Transient: not written to the experience column until accepted.
        $this->assertSame(['Did some work.'], $cv->fresh()->experience[0]['bullets']);
    }

    public function test_accepting_improved_bullets_replaces_the_entrys_bullets_and_persists(): void
    {
        $cv = Cv::factory()->create();

        $gemini = Mockery::mock(GeminiService::class);
        $gemini->shouldReceive('improveBullets')->once()->andReturn(['Improved bullet.']);
        $this->instance(GeminiService::class, $gemini);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('addExperience')
            ->set('experience.0.bullets.0', 'Original bullet.')
            ->call('improveBullets', 0)
            ->call('acceptImprovedBullets', 0)
            ->assertSet('experience.0.bullets', ['Improved bullet.'])
            ->assertSet('improvedBullets', []);

        $this->assertSame(['Improved bullet.'], $cv->fresh()->experience[0]['bullets']);
    }

    public function test_improve_bullets_failure_sets_an_error(): void
    {
        $cv = Cv::factory()->create();

        $gemini = Mockery::mock(GeminiService::class);
        $gemini->shouldReceive('improveBullets')->once()->andReturn(null);
        $this->instance(GeminiService::class, $gemini);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('addExperience')
            ->set('experience.0.bullets.0', 'Original bullet.')
            ->call('improveBullets', 0)
            ->assertSet('improvedBullets', [])
            ->assertSet('aiError', fn ($error) => filled($error));
    }

    public function test_suggesting_skills_excludes_ones_already_added(): void
    {
        $cv = Cv::factory()->create(['skills' => ['PHP']]);

        $gemini = Mockery::mock(GeminiService::class);
        $gemini->shouldReceive('suggestSkills')->once()->with(['PHP'], null)->andReturn(['PHP', 'Laravel', 'MySQL']);
        $this->instance(GeminiService::class, $gemini);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->call('suggestSkills')
            ->assertSet('suggestedSkills', ['Laravel', 'MySQL']);
    }

    public function test_accepting_a_suggested_skill_adds_it_and_persists(): void
    {
        $cv = Cv::factory()->create(['skills' => []]);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('suggestedSkills', ['Laravel'])
            ->call('acceptSuggestedSkill', 'Laravel')
            ->assertSet('skills', ['Laravel'])
            ->assertSet('suggestedSkills', []);

        $this->assertSame(['Laravel'], $cv->fresh()->skills);
    }

    public function test_dismissing_suggested_skills_clears_them_without_persisting(): void
    {
        $cv = Cv::factory()->create(['skills' => []]);

        Livewire::test(CvBuilder::class, ['cv' => $cv])
            ->set('suggestedSkills', ['Laravel'])
            ->call('dismissSuggestedSkills')
            ->assertSet('suggestedSkills', []);

        $this->assertSame([], $cv->fresh()->skills);
    }
}
