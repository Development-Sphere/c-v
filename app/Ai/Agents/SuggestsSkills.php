<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\HasStructuredOutput;

class SuggestsSkills extends CvAgent implements HasStructuredOutput
{
    protected function taskInstructions(): string
    {
        return <<<'TEXT'
        You are a career coach. Given a role/summary context and the skills a person
        has already listed on their CV, suggest additional relevant skills they may
        have forgotten to list. Do not repeat any skill already listed. Suggest at
        most 10 skills, ordered by relevance.
        TEXT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'skills' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
