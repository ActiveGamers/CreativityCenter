<?php

namespace Database\Seeders;

use App\Models\Statistic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The statistics shown on the home and achievements pages.
     *
     * @var array<int, array<string, mixed>>
     */
    public const STATISTICS = [
        [
            'label' => 'رتبه اول کشوری',
            'value' => '۱۴۰۳',
            'description' => 'مقام نخست کشوری در سال تحصیلی ۱۴۰۳.',
            'icon' => 'trophy',
            'order' => 1,
        ],
        [
            'label' => 'رتبه‌های سه‌رقمی',
            'value' => '۲۰۰+',
            'description' => 'بیش از ۲۰۰ رتبه سه‌رقمی کشوری در سه سال اخیر.',
            'icon' => 'award',
            'order' => 2,
        ],
        [
            'label' => 'رتبه‌های دو‌رقمی',
            'value' => '۵۰+',
            'description' => 'بیش از ۵۰ رتبه دو‌رقمی کشوری در سه سال اخیر.',
            'icon' => 'medal',
            'order' => 3,
        ],
        [
            'label' => 'رتبه‌های تک‌رقمی',
            'value' => '۳',
            'description' => 'سه رتبه تک‌رقمی کشوری در سه سال اخیر.',
            'icon' => 'star',
            'order' => 4,
        ],
        [
            'label' => 'رشته‌های فعال',
            'value' => '۲',
            'description' => 'رشته شبکه و نرم‌افزار رایانه و رشته مکاترونیک.',
            'icon' => 'layers',
            'order' => 5,
        ],
        [
            'label' => 'پایه‌های تحصیلی',
            'value' => '۳',
            'description' => 'پایه‌های دهم، یازدهم و دوازدهم.',
            'icon' => 'book',
            'order' => 6,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::STATISTICS as $statistic) {
            Statistic::query()->updateOrCreate(
                ['label' => $statistic['label']],
                $statistic,
            );
        }
    }
}
