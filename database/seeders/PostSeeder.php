<?php

namespace Database\Seeders;

use App\Enums\PostCategory;
use App\Models\Post;
use App\Support\SchoolContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The introductory paragraph of the school, reused in the seeded posts.
     */
    public const INTRO = SchoolContent::INTRO;

    /**
     * The sample posts of the website.
     *
     * NOTE: These are sample posts written for the layout only. The school
     * still has to send the real news, announcements and reports.
     *
     * @return array<int, array<string, mixed>>
     */
    public function posts(): array
    {
        return [
            [
                'title' => 'آغاز سال تحصیلی جدید در هنرستان جوار شرکت پالایش نفت بندرعباس',
                'slug' => 'start-of-new-school-year',
                'excerpt' => 'دانش‌آموزان هنرستان جوار شرکت پالایش نفت بندرعباس سال تحصیلی جدید را با حضور در کلاس‌های تخصصی رشته‌های شبکه و نرم‌افزار رایانه و مکاترونیک آغاز کردند.',
                'body' => self::INTRO."\n\n".'در سال تحصیلی جدید، دانش‌آموزان این هنرستان در سه پایه دهم، یازدهم و دوازدهم در دو رشته شبکه و نرم‌افزار رایانه و مکاترونیک مشغول به تحصیل شدند. برنامه آموزشی این سال بر پایه کارگاه‌های عملی آزمایشگاه شبکه و کارگاه‌های کنترل صنعتی و رباتیک تنظیم شده است.',
                'category' => PostCategory::News,
                'cover_image' => null,
                'published_at' => now()->subDays(18),
                'is_published' => true,
            ],
            [
                'title' => 'اطلاعیه ثبت‌نام پایه دهم در سال تحصیلی جدید',
                'slug' => 'registration-notice-for-tenth-grade',
                'excerpt' => 'ثبت‌نام دانش‌آموزان پایه دهم در دو رشته شبکه و نرم‌افزار رایانه و مکاترونیک از طریق مراجعه به دفتر هنرستان انجام می‌شود.',
                'body' => 'به خانواده‌های محترم دانش‌آموزان و داوطلبان ورود به پایه دهم اطلاع می‌دهیم که ثبت‌نام سال تحصیلی جدید در دو رشته شبکه و نرم‌افزار رایانه و مکاترونیک آغاز شده است. برای دریافت راهنمایی درباره مدارک مورد نیاز و ظرفیت پذیرش هر رشته، می‌توانید از طریق فرم ثبت‌نام همین وب‌سایت یا با مراجعه به دفتر هنرستان در نشانی «میدان ۹ دی، بندرعباس، هرمزگان» با کادر آموزشی تماس بگیرید.',
                'category' => PostCategory::Announcement,
                'cover_image' => null,
                'published_at' => now()->subDays(9),
                'is_published' => true,
            ],
            [
                'title' => 'کسب رتبه اول کشوری در سال تحصیلی ۱۴۰۳',
                'slug' => 'first-national-rank-in-1403',
                'excerpt' => 'هنرستان جوار شرکت پالایش نفت بندرعباس موفق شد در سال تحصیلی ۱۴۰۳ رتبه اول کشوری را به خود اختصاص دهد.',
                'body' => self::INTRO."\n\n".'دستاورد تازه هنرستان جوار شرکت پالایش نفت بندرعباس، کسب رتبه اول کشوری در سال تحصیلی ۱۴۰۳ است. این موفقیت در کنار رکورد سه‌ساله هنرستان که شامل بیش از ۲۰۰ رتبه سه‌رقمی، بیش از ۵۰ رتبه دو‌رقمی و ۳ رتبه تک‌رقمی کشوری است، جایگاه این هنرستان را در میان برترین هنرستان‌های کشور تثبیت می‌کند.',
                'category' => PostCategory::Achievement,
                'cover_image' => null,
                'published_at' => now()->subDays(3),
                'is_published' => true,
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->posts() as $post) {
            Post::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post,
            );
        }
    }
}
