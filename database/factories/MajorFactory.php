<?php

namespace Database\Factories;

use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Major>
 */
class MajorFactory extends Factory
{
    /**
     * The known majors of the school, with a stable slug for each one.
     *
     * @var array<int, array<string, string>>
     */
    public const MAJORS = [
        [
            'title' => 'شبکه و نرم‌افزار رایانه',
            'slug' => 'network-and-software',
            'icon' => 'network',
            'accent' => 'brand',
            'subtitle' => 'دانش بنیان در حوزه فناوری اطلاعات',
            'summary' => 'طراحی، پیاده‌سازی و مدیریت شبکه‌های رایانه‌ای به همراه توسعه نرم‌افزارهای تحت وب.',
        ],
        [
            'title' => 'مکاترونیک',
            'slug' => 'mechatronics',
            'icon' => 'mechatronics',
            'accent' => 'accent',
            'subtitle' => 'تلاقی مکانیک، الکترونیک و کنترل',
            'summary' => 'طراحی و کنترل سامانه‌های صنعتی هوشمند با تکیه بر الکترونیک، کنترل و اتوماسیون.',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $major = fake()->randomElement(self::MAJORS);

        return [
            'title' => $major['title'],
            'slug' => $major['slug'].'-'.fake()->unique()->numberBetween(1, PHP_INT_MAX),
            'subtitle' => $major['subtitle'],
            'summary' => $major['summary'],
            'description' => $major['summary'].' دانش‌آموزان این رشته در سه پایه دهم، یازدهم و دوازدهم با مبانی تخصصی، کارگاه‌های عملی و پروژه‌های پایانی آماده ورود به بازار کار می‌شوند.',
            'icon' => $major['icon'],
            'accent' => $major['accent'],
            'seats' => fake()->numberBetween(20, 40),
            'order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the major is hidden from the public website.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
