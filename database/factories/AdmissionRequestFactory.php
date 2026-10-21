<?php

namespace Database\Factories;

use App\Enums\AdmissionStatus;
use App\Enums\GradeLevel;
use App\Models\AdmissionRequest;
use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdmissionRequest>
 */
class AdmissionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * The request is attached to a major that already exists, so a batch of
     * requests never creates a major for every single one of them.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_name' => fake()->name(),
            'parent_name' => fake()->name(),
            'phone' => fake()->numerify('09#########'),
            'email' => fake()->safeEmail(),
            'grade_level' => fake()->randomElement(GradeLevel::cases()),
            'major_id' => Major::query()->orderBy('id')->value('id'),
            'note' => null,
            'status' => AdmissionStatus::New,
        ];
    }
}
