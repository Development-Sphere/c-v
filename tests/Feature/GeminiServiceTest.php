<?php

namespace Tests\Feature;

use App\Ai\Agents\ImprovesBullets;
use App\Ai\Agents\PolishesSummary;
use App\Ai\Agents\SuggestsSkills;
use App\Services\GeminiService;
use Tests\TestCase;

class GeminiServiceTest extends TestCase
{
    public function test_polish_summary_returns_null_when_the_provider_is_unavailable(): void
    {
        $this->assertNull((new GeminiService)->polishSummary('Experienced engineer.'));
    }

    public function test_improve_bullets_returns_null_when_the_provider_is_unavailable(): void
    {
        $this->assertNull((new GeminiService)->improveBullets(['Did some work.']));
    }

    public function test_suggest_skills_returns_null_when_the_provider_is_unavailable(): void
    {
        $this->assertNull((new GeminiService)->suggestSkills(['PHP']));
    }

    public function test_every_agent_shares_the_same_fact_preserving_guardrail(): void
    {
        $marker = 'never invent, guess, or embellish facts';

        $this->assertStringContainsString($marker, (string) (new PolishesSummary)->instructions());
        $this->assertStringContainsString($marker, (string) (new ImprovesBullets)->instructions());
        $this->assertStringContainsString($marker, (string) (new SuggestsSkills)->instructions());
    }
}
