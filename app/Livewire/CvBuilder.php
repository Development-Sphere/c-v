<?php

namespace App\Livewire;

use App\Models\Cv;
use App\Models\CvTemplate;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.wizard')]
class CvBuilder extends Component
{
    public Cv $cv;

    #[Url]
    public int $step = 1;

    public array $personalInfo = [];

    public array $summary = [];

    public array $experience = [];

    public array $education = [];

    public array $skills = [];

    public string $newSkill = '';

    public string $template = 'modern';

    public ?string $title = null;

    public int $previewVersion = 0;

    public function mount(Cv $cv): void
    {
        $this->cv = $cv;

        $this->personalInfo = $cv->personal_info + [
            'name' => '', 'email' => '', 'phone' => '', 'location' => '', 'links' => [], 'photo_path' => null,
        ];
        $this->summary = $cv->summary + ['raw' => '', 'polished' => ''];
        $this->experience = $cv->experience;
        $this->education = $cv->education;
        $this->skills = $cv->skills;
        $this->template = $cv->template;
        $this->title = $cv->title;

        if ($this->step < 1 || $this->step > count($this->steps())) {
            $this->step = 1;
        }
    }

    public function steps(): array
    {
        return [
            1 => 'Personal Info',
            2 => 'Summary',
            3 => 'Experience',
            4 => 'Education',
            5 => 'Skills',
            6 => 'Template',
            7 => 'Review',
        ];
    }

    public function goToStep(int $step): void
    {
        if (array_key_exists($step, $this->steps())) {
            $this->step = $step;
        }
    }

    public function nextStep(): void
    {
        $this->goToStep($this->step + 1);
    }

    public function previousStep(): void
    {
        $this->goToStep($this->step - 1);
    }

    public function currentStepView(): string
    {
        return match ($this->step) {
            1 => 'personal-info',
            2 => 'summary',
            3 => 'experience',
            4 => 'education',
            5 => 'skills',
            6 => 'template',
            default => 'review',
        };
    }

    public function stepIsComplete(int $step): bool
    {
        return match ($step) {
            1 => filled($this->personalInfo['name'] ?? null) && filled($this->personalInfo['email'] ?? null),
            2 => filled($this->summary['raw'] ?? null),
            3 => count($this->experience) > 0,
            4 => count($this->education) > 0,
            5 => count($this->skills) > 0,
            6 => filled($this->template),
            default => false,
        };
    }

    public function addLink(): void
    {
        $this->personalInfo['links'][] = ['label' => '', 'url' => ''];
        $this->persist();
    }

    public function removeLink(int $index): void
    {
        unset($this->personalInfo['links'][$index]);
        $this->personalInfo['links'] = array_values($this->personalInfo['links']);
        $this->persist();
    }

    public function addSkill(): void
    {
        $skill = trim($this->newSkill);

        if ($skill !== '' && ! in_array($skill, $this->skills, true)) {
            $this->skills[] = $skill;
        }

        $this->newSkill = '';
        $this->persist();
    }

    public function removeSkill(int $index): void
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
        $this->persist();
    }

    public function addExperience(): void
    {
        $this->experience[] = [
            'title' => '', 'company' => '', 'location' => '',
            'start_date' => '', 'end_date' => '', 'current' => false, 'bullets' => [],
        ];
        $this->persist();
    }

    public function removeExperience(int $index): void
    {
        unset($this->experience[$index]);
        $this->experience = array_values($this->experience);
        $this->persist();
    }

    public function moveExperienceUp(int $index): void
    {
        $this->moveArrayItem($this->experience, $index, $index - 1);
        $this->persist();
    }

    public function moveExperienceDown(int $index): void
    {
        $this->moveArrayItem($this->experience, $index, $index + 1);
        $this->persist();
    }

    public function addExperienceBullet(int $index): void
    {
        $this->experience[$index]['bullets'][] = '';
        $this->persist();
    }

    public function removeExperienceBullet(int $expIndex, int $bulletIndex): void
    {
        unset($this->experience[$expIndex]['bullets'][$bulletIndex]);
        $this->experience[$expIndex]['bullets'] = array_values($this->experience[$expIndex]['bullets']);
        $this->persist();
    }

    public function addEducation(): void
    {
        $this->education[] = [
            'institution' => '', 'qualification' => '',
            'start_date' => '', 'end_date' => '', 'notes' => '',
        ];
        $this->persist();
    }

    public function removeEducation(int $index): void
    {
        unset($this->education[$index]);
        $this->education = array_values($this->education);
        $this->persist();
    }

    public function moveEducationUp(int $index): void
    {
        $this->moveArrayItem($this->education, $index, $index - 1);
        $this->persist();
    }

    public function moveEducationDown(int $index): void
    {
        $this->moveArrayItem($this->education, $index, $index + 1);
        $this->persist();
    }

    public function selectTemplate(string $key): void
    {
        $this->template = $key;
        $this->persist();
    }

    protected function rules(): array
    {
        return [
            'personalInfo.email' => 'nullable|email',
        ];
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['newSkill', 'step'], true)) {
            return;
        }

        if (array_key_exists($property, $this->rules())) {
            $this->validateOnly($property, $this->rules());
        }

        $this->persist();
    }

    private function moveArrayItem(array &$items, int $from, int $to): void
    {
        if (! isset($items[$from]) || $to < 0 || $to >= count($items)) {
            return;
        }

        $item = $items[$from];
        unset($items[$from]);
        $items = array_values($items);
        array_splice($items, $to, 0, [$item]);
    }

    private function persist(): void
    {
        if (in_array($this->title, [null, '', 'Untitled CV'], true) && filled($this->personalInfo['name'] ?? null)) {
            $this->title = "{$this->personalInfo['name']}'s CV";
        }

        $this->cv->fill([
            'personal_info' => $this->personalInfo,
            'summary' => $this->summary,
            'experience' => $this->experience,
            'education' => $this->education,
            'skills' => $this->skills,
            'template' => $this->template,
            'title' => $this->title,
        ]);

        $this->cv->save();

        $this->previewVersion++;
    }

    public function render(): View
    {
        return view('livewire.cv-builder', [
            'templates' => CvTemplate::query()->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
