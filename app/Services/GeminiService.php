<?php

namespace App\Services;

use App\Ai\Agents\ImprovesBullets;
use App\Ai\Agents\PolishesSummary;
use App\Ai\Agents\SuggestsSkills;
use Laravel\Ai\Enums\Lab;
use Throwable;

/**
 * The only place in this app that talks to the Gemini API. Every method
 * returns null on any failure (rate limit, provider down, timeout, missing
 * key) so callers can degrade gracefully instead of blocking manual editing.
 */
class GeminiService
{
    public function polishSummary(string $raw): ?string
    {
        try {
            $response = (new PolishesSummary)->prompt(
                $raw,
                provider: Lab::Gemini,
                model: config('services.gemini.model'),
                timeout: config('services.gemini.timeout'),
            );

            $polished = trim((string) $response);

            return $polished !== '' ? $polished : null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    public function improveBullets(array $bullets, ?string $context = null): ?array
    {
        try {
            $prompt = $this->formatBulletsPrompt($bullets, $context);

            $response = (new ImprovesBullets)->prompt(
                $prompt,
                provider: Lab::Gemini,
                model: config('services.gemini.model'),
                timeout: config('services.gemini.timeout'),
            );

            $result = $response['bullets'] ?? null;

            return is_array($result) ? array_values(array_map('strval', $result)) : null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    public function suggestSkills(array $existingSkills, ?string $context = null): ?array
    {
        try {
            $prompt = $this->formatSkillsPrompt($existingSkills, $context);

            $response = (new SuggestsSkills)->prompt(
                $prompt,
                provider: Lab::Gemini,
                model: config('services.gemini.model'),
                timeout: config('services.gemini.timeout'),
            );

            $result = $response['skills'] ?? null;

            return is_array($result) ? array_values(array_map('strval', $result)) : null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    private function formatBulletsPrompt(array $bullets, ?string $context): string
    {
        $lines = collect($bullets)
            ->values()
            ->map(fn (string $bullet, int $i) => ($i + 1).". {$bullet}")
            ->implode("\n");

        $prefix = $context ? "Role: {$context}\n\n" : '';

        return "{$prefix}Bullet points:\n{$lines}";
    }

    private function formatSkillsPrompt(array $existingSkills, ?string $context): string
    {
        $prefix = $context ? "Role/summary context: {$context}\n\n" : '';
        $existing = count($existingSkills) > 0
            ? implode(', ', $existingSkills)
            : '(none listed yet)';

        return "{$prefix}Skills already listed: {$existing}";
    }
}
