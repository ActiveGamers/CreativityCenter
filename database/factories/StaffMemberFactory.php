<?php

namespace Database\Factories;

use App\Models\StaffMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffMember>
 */
class StaffMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => 'دبیر',
            'subject' => 'ریاضیات فنی',
            'photo' => null,
            'phone' => fake()->numerify('09#########'),
            'bio' => 'معرفی کوتاه از سوابق و مسئولیت‌های این عضو کادر آموزشی.',
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
