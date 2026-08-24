<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * Base for every CV-content agent. `instructions()` is final so the
 * "never invent facts" guardrail can't be silently skipped or drift
 * out of sync between subclasses — only `taskInstructions()` varies.
 */
abstract class CvAgent implements Agent
{
    use Promptable;

    abstract protected function taskInstructions(): string;

    final public function instructions(): Stringable|string
    {
        return $this->guardrails()."\n\n".$this->taskInstructions();
    }

    protected function guardrails(): string
    {
        return <<<'TEXT'
        You must never invent, guess, or embellish facts. Preserve all dates, company
        names, job titles, numbers, and metrics exactly as given. Only improve wording,
        clarity, tone, and impact. If information is missing, leave it out rather than
        inventing it.
        TEXT;
    }
}
