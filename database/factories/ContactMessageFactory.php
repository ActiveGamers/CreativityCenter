<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
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
            'phone' => fake()->numerify('09#########'),
            'email' => fake()->safeEmail(),
            'subject' => 'پرسش درباره ثبت‌نام',
            'message' => fake()->paragraph(),
            'is_read' => false,
        ];
    }
}
