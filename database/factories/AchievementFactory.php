<?php

namespace Database\Factories;

use App\Enums\AchievementScope;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'کسب رتبه برتر در مسابقات کشوری',
            'description' => 'توضیح کوتاهی درباره این دستاورد و سال تحصیلی مربوط به آن.',
            'scope' => AchievementScope::National,
            'rank_label' => 'رتبه اول کشوری',
            'year' => fake()->numberBetween(1401, 1403),
            'competition' => null,
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
