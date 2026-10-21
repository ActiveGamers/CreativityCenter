<?php

namespace Database\Factories;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Statistic>
 */
class StatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => 'عنوان آمار',
            'value' => '۰',
            'description' => 'توضیح کوتاهی درباره این عدد.',
            'icon' => 'chart',
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
