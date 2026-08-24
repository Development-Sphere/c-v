<?php

namespace Database\Seeders;

use App\Models\CvTemplate;
use Illuminate\Database\Seeder;

class CvTemplateSeeder extends Seeder
{
    /**
     * Seed the application's CV templates.
     */
    public function run(): void
    {
        $templates = [
            ['key' => 'modern', 'name' => 'Modern', 'description' => 'Clean two-column layout with an accent sidebar.', 'sort_order' => 1],
            ['key' => 'classic', 'name' => 'Classic', 'description' => 'Traditional single-column resume layout.', 'sort_order' => 2],
            ['key' => 'minimal', 'name' => 'Minimal', 'description' => 'Understated typography-first layout.', 'sort_order' => 3],
        ];

        foreach ($templates as $template) {
            CvTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
