<?php

namespace Database\Factories;

use App\Enums\GradeLevel;
use App\Models\Major;
use App\Models\MajorCourse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MajorCourse>
 */
class MajorCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'major_id' => Major::factory(),
            'grade' => fake()->randomElement(GradeLevel::cases()),
            'title' => 'درس '.$this->faker->unique()->words(2, true),
            'description' => 'شرح کوتاه درباره محتوای این درس و مهارت‌هایی که دانش‌آموز در آن کسب می‌کند.',
            'weekly_hours' => fake()->numberBetween(2, 4),
            'order' => fake()->numberBetween(1, 20),
        ];
    }

    /**
     * Indicate that the course belongs to the given grade level.
     */
    public function forGrade(GradeLevel $grade): static
    {
        return $this->state(fn (array $attributes): array => [
            'grade' => $grade,
        ]);
    }
}
