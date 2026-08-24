<?php

namespace App\Ai\Agents;

class PolishesSummary extends CvAgent
{
    protected function taskInstructions(): string
    {
        return <<<'TEXT'
        You are a professional CV/resume editor. Rewrite the professional summary the
        user gives you so it reads more clearly and with more impact. Return only the
        rewritten summary text — no preamble, no quotation marks, no explanation.
        TEXT;
    }
}
