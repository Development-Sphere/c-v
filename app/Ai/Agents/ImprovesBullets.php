<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\HasStructuredOutput;

class ImprovesBullets extends CvAgent implements HasStructuredOutput
{
    protected function taskInstructions(): string
    {
        return <<<'TEXT'
        You are a professional CV/resume editor. Rewrite each of the given bullet
        points to use strong action verbs and, where the facts support it, quantifiable
        impact. Return exactly the same number of bullets, in the same order, one
        rewritten bullet per original bullet.
        TEXT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'bullets' => $schema->array()->items($schema->string())->required(),
        ];
    }
}
