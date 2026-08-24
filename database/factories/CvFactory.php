<?php

namespace Database\Factories;

use App\Enums\CvStatus;
use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Cv>
 */
class CvFactory extends Factory
{
    protected $model = Cv::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'session_id' => null,
            'template' => 'modern',
            'title' => fake()->jobTitle().' CV',
            'personal_info' => [
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'location' => fake()->city(),
                'links' => [],
                'photo_path' => null,
            ],
            'summary' => [
                'raw' => fake()->sentence(),
                'polished' => '',
            ],
            'experience' => [],
            'education' => [],
            'skills' => [],
            'extras' => null,
            'status' => CvStatus::Draft,
        ];
    }

    /**
     * Indicate that the CV belongs to a guest, identified by a token.
     */
    public function guest(?string $token = null): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'session_id' => $token ?? (string) Str::uuid(),
        ]);
    }
}
