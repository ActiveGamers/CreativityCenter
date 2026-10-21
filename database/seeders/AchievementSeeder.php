<?php

namespace Database\Seeders;

use App\Enums\AchievementScope;
use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The achievements of the school.
     *
     * The wording is deliberately limited to the facts the school provided:
     * the first national rank in 1403 and the cumulative three-year record of
     * three-digit, two-digit and single-digit national ranks. The name of the
     * competition is still unknown, so it stays null until the school confirms
     * it.
     *
     * @var array<int, array<string, mixed>>
     */
    public const ACHIEVEMENTS = [
        [
            'title' => 'رتبه اول کشوری',
            'description' => 'کسب رتبه اول کشوری در سال تحصیلی ۱۴۰۳.',
            'scope' => AchievementScope::National,
            'rank_label' => 'رتبه اول',
            'year' => 1403,
            'competition' => null,
            'order' => 1,
        ],
        [
            'title' => 'کسب بیش از ۲۰۰ رتبه سه‌رقمی کشوری در سه سال اخیر',
            'description' => 'مجموع رتبه‌های سه‌رقمی کشوری کسب‌شده در سه سال اخیر (۱۴۰۱ تا ۱۴۰۳).',
            'scope' => AchievementScope::National,
            'rank_label' => 'بیش از ۲۰۰ رتبه سه‌رقمی',
            'year' => 1403,
            'competition' => null,
            'order' => 2,
        ],
        [
            'title' => 'کسب بیش از ۵۰ رتبه دو‌رقمی کشوری در سه سال اخیر',
            'description' => 'مجموع رتبه‌های دو‌رقمی کشوری کسب‌شده در سه سال اخیر (۱۴۰۱ تا ۱۴۰۳).',
            'scope' => AchievementScope::National,
            'rank_label' => 'بیش از ۵۰ رتبه دو‌رقمی',
            'year' => 1403,
            'competition' => null,
            'order' => 3,
        ],
        [
            'title' => 'کسب ۳ رتبه تک‌رقمی کشوری در سه سال اخیر',
            'description' => 'مجموع رتبه‌های تک‌رقمی کشوری کسب‌شده در سه سال اخیر (۱۴۰۱ تا ۱۴۰۳).',
            'scope' => AchievementScope::National,
            'rank_label' => '۳ رتبه تک‌رقمی',
            'year' => 1403,
            'competition' => null,
            'order' => 4,
        ],
        [
            'title' => 'کسب رتبه‌های سه‌رقمی کشوری در سال تحصیلی ۱۴۰۱',
            'description' => 'بخشی از رکورد سه‌ساله رتبه‌های سه‌رقمی کشوری هنرستان.',
            'scope' => AchievementScope::National,
            'rank_label' => 'رتبه‌های سه‌رقمی کشوری',
            'year' => 1401,
            'competition' => null,
            'order' => 5,
        ],
        [
            'title' => 'کسب رتبه‌های سه‌رقمی کشوری در سال تحصیلی ۱۴۰۲',
            'description' => 'بخشی از رکورد سه‌ساله رتبه‌های سه‌رقمی کشوری هنرستان.',
            'scope' => AchievementScope::National,
            'rank_label' => 'رتبه‌های سه‌رقمی کشوری',
            'year' => 1402,
            'competition' => null,
            'order' => 6,
        ],
        [
            'title' => 'کسب رتبه‌های سه‌رقمی کشوری در سال تحصیلی ۱۴۰۳',
            'description' => 'بخشی از رکورد سه‌ساله رتبه‌های سه‌رقمی کشوری هنرستان.',
            'scope' => AchievementScope::National,
            'rank_label' => 'رتبه‌های سه‌رقمی کشوری',
            'year' => 1403,
            'competition' => null,
            'order' => 7,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::ACHIEVEMENTS as $achievement) {
            Achievement::query()->updateOrCreate(
                [
                    'title' => $achievement['title'],
                    'year' => $achievement['year'],
                ],
                $achievement,
            );
        }
    }
}
