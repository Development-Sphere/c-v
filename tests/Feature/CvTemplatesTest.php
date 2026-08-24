<?php

namespace Tests\Feature;

use App\Models\Cv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CvTemplatesTest extends TestCase
{
    use RefreshDatabase;

    public static function templateKeys(): array
    {
        return [
            'modern' => ['modern'],
            'classic' => ['classic'],
            'minimal' => ['minimal'],
        ];
    }

    #[DataProvider('templateKeys')]
    public function test_template_renders_with_populated_data(string $key): void
    {
        $cv = Cv::factory()->create([
            'template' => $key,
            'personal_info' => [
                'name' => 'Ada Lovelace',
                'email' => 'ada@example.com',
                'phone' => '555-0100',
                'location' => 'London, UK',
                'links' => [['label' => 'Portfolio', 'url' => 'https://ada.example.com']],
                'photo_path' => null,
            ],
            'summary' => ['raw' => 'Mathematician and writer.', 'polished' => ''],
            'experience' => [[
                'title' => 'Analyst', 'company' => 'Analytical Engines Ltd', 'location' => 'London',
                'start_date' => '2020-01', 'end_date' => '2022-06', 'current' => false,
                'bullets' => ['Wrote the first published algorithm.'],
            ]],
            'education' => [[
                'institution' => 'Home schooling', 'qualification' => 'Mathematics',
                'start_date' => '2010-01', 'end_date' => '2015-01', 'notes' => 'Focused on Bernoulli numbers.',
            ]],
            'skills' => ['Analytical Engine programming', 'Mathematics'],
        ]);

        $html = view($cv->templateView(), ['cv' => $cv])->render();

        $this->assertStringContainsString('Ada Lovelace', $html);
        $this->assertStringContainsString('ada@example.com', $html);
        $this->assertStringContainsString('Analyst', $html);
        $this->assertStringContainsString('Analytical Engines Ltd', $html);
        $this->assertStringContainsString('Wrote the first published algorithm.', $html);
        $this->assertStringContainsString('Mathematics', $html);
    }

    #[DataProvider('templateKeys')]
    public function test_template_renders_without_error_when_optional_sections_are_empty(string $key): void
    {
        $cv = Cv::factory()->create(['template' => $key]);

        $html = view($cv->templateView(), ['cv' => $cv])->render();

        $this->assertStringContainsString($cv->personal_info['name'], $html);
    }
}
