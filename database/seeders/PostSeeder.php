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
     * The introductory paragraph of the school.
     *
     * The placeholder posts announce no fact, so they do not repeat it; the
     * constant stays here as the single reference of the school introduction.
     */
    public const INTRO = SchoolContent::INTRO;

    /**
     * The sample posts of the website.
     *
     * NOTE: These posts are placeholders for the layout only. The school has
     * not sent any news yet, so every one of them says plainly that it is a
     * sample and that it announces no fact. The real news replaces them as
     * soon as the school confirms it; until then the page shows the layout
     * without publishing a made-up announcement.
     *
     * @return array<int, array<string, mixed>>
     */
    public function posts(): array
    {
        return [
            [
                'title' => 'نمونه — جای خبرهای هنرستان',
                'slug' => 'start-of-new-school-year',
                'excerpt' => 'این متن فقط نمونه‌ای برای بررسی چیدمان بخش اخبار است و هیچ خبری را اعلام نمی‌کند.',
                'body' => 'این مطلب صرفاً نمونه‌ای برای بررسی چیدمان بخش اخبار است و هیچ خبر، رویداد یا اطلاعیه‌ای را اعلام نمی‌کند.'."\n\n".'اخبار، اطلاعیه‌ها و گزارش‌های واقعی هنرستان پس از تأیید کادر آموزشی در همین بخش منتشر خواهد شد.',
                'category' => PostCategory::News,
                'cover_image' => null,
                'published_at' => now()->subDays(18),
                'is_published' => true,
            ],
            [
                'title' => 'نمونه — جای اطلاعیه‌های ثبت‌نام',
                'slug' => 'registration-notice-for-tenth-grade',
                'excerpt' => 'این متن فقط نمونه‌ای برای بررسی چیدمان اطلاعیه‌هاست و هیچ مهلت یا شرایطی را اعلام نمی‌کند.',
                'body' => 'این اطلاعیه صرفاً نمونه‌ای برای بررسی چیدمان بخش اطلاعیه‌هاست و هیچ مهلت، شرایط یا شیوهٔ ثبت‌نامی را اعلام نمی‌کند.'."\n\n".'اطلاعیهٔ رسمی ثبت‌نام پس از تأیید کادر آموزشی منتشر می‌شود. تا آن زمان، برای پرسش دربارهٔ ثبت‌نام می‌توانید از فرم مشاوره و تماس همین وب‌سایت استفاده کنید.',
                'category' => PostCategory::Announcement,
                'cover_image' => null,
                'published_at' => now()->subDays(9),
                'is_published' => true,
            ],
            [
                'title' => 'نمونه — جای گزارش‌های افتخارآفرینی',
                'slug' => 'first-national-rank-in-1403',
                'excerpt' => 'این متن فقط نمونه‌ای برای بررسی چیدمان گزارش‌های افتخارآفرینی است و دستاوردی را اعلام نمی‌کند.',
                'body' => 'این مطلب صرفاً نمونه‌ای برای بررسی چیدمان گزارش افتخارآفرینی است و دستاوردی را اعلام نمی‌کند.'."\n\n".'رتبه‌های ثبت‌شدهٔ هنرستان در بخش «افتخارات» همین وب‌سایت آمده است و گزارش کامل هر موفقیت پس از تأیید کادر آموزشی در همین بخش منتشر می‌شود.',
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
