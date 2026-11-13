<?php

namespace Database\Seeders;

use App\Enums\GradeLevel;
use App\Models\Major;
use App\Models\MajorCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorCourseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The courses of each major, grouped by grade level.
     *
     * NOTE: The titles below are plausible general curriculum titles and must
     * be confirmed against the official Ministry of Education curriculum
     * before the website is published.
     *
     * @var array<string, array<string, array<int, array<string, mixed>>>>
     */
    public const COURSES = [
        'network-and-software' => [
            GradeLevel::Tenth->value => [
                ['title' => 'مبانی شبکه و سخت‌افزار', 'weekly_hours' => 3, 'description' => 'شناخت اجزای رایانه، سخت‌افزار، انواع شبکه و اصول اولیه ارتباط در شبکه.'],
                ['title' => 'تفکر الگوریتمی و برنامه‌نویسی مقدماتی', 'weekly_hours' => 3, 'description' => 'حل مسئله با الگوریتم و آشنایی با مبانی زبان‌های برنامه‌نویسی.'],
                ['title' => 'برنامه‌نویسی وب', 'weekly_hours' => 3, 'description' => 'طراحی صفحات وب با زبان‌های نشانه‌گذاری و کار با پایگاه داده ساده.'],
                ['title' => 'ریاضیات فنی و کاربرد آن در رایانه', 'weekly_hours' => 3, 'description' => 'کاربرد مفاهیم ریاضی در حل مسائل فنی رشته رایانه.'],
            ],
            GradeLevel::Eleventh->value => [
                ['title' => 'طراحی و پیاده‌سازی شبکه', 'weekly_hours' => 4, 'description' => 'طراحی توپولوژی، پیکربندی تجهیزات شبکه و عیب‌یابی اتصال.'],
                ['title' => 'برنامه‌نویسی وب پیشرفته', 'weekly_hours' => 3, 'description' => 'توسعه وب‌سایت‌های پویا با پایگاه داده و معماری سه‌لایه.'],
                ['title' => 'امنیت شبکه', 'weekly_hours' => 3, 'description' => 'آشنایی با تهدیدهای رایانه‌ای و روش‌های مقابله با آن‌ها.'],
                ['title' => 'پایگاه داده و مدیریت داده', 'weekly_hours' => 3, 'description' => 'طراحی پایگاه داده رابطه‌ای و نوشتن پرس‌وجوهای بهینه.'],
                ['title' => 'سیستم‌عامل سرور و سرویس‌های شبکه', 'weekly_hours' => 3, 'description' => 'نصب، پیکربندی و مدیریت سرویس‌های شبکه روی سرور.'],
            ],
            GradeLevel::Twelfth->value => [
                ['title' => 'مدیریت شبکه و سیستم‌عامل سرور', 'weekly_hours' => 4, 'description' => 'مدیریت منابع، کاربران و سیاست‌های دسترسی در شبکه سازمانی.'],
                ['title' => 'امنیت و پایش شبکه', 'weekly_hours' => 3, 'description' => 'پایش ترافیک، ثبت رخدادها و مقابله با نفوذ در شبکه.'],
                ['title' => 'پیاده‌سازی سرویس‌های سازمانی', 'weekly_hours' => 3, 'description' => 'راه‌اندازی سرویس‌های اشتراکی و ابری در محیط واقعی کار.'],
                ['title' => 'کارگاه پروژه پایانی نرم‌افزار و شبکه', 'weekly_hours' => 4, 'description' => 'طراحی و ارائه یک پروژه کامل شبکه و نرم‌افزار از ابتدا تا انتها.'],
            ],
        ],
        'mechatronics' => [
            GradeLevel::Tenth->value => [
                ['title' => 'مبانی مکاترونیک', 'weekly_hours' => 3, 'description' => 'شناخت اجزای سامانه‌های مکاترونیکی و ارتباط میان مکانیک، الکترونیک و کنترل.'],
                ['title' => 'مدار الکتریکی و اندازه‌گیری', 'weekly_hours' => 4, 'description' => 'کار با مدارهای الکتریکی، اندازه‌گیری جریان و ولتاژ و استفاده از ابزار دقیق.'],
                ['title' => 'مبانی برنامه‌نویسی و کنترل', 'weekly_hours' => 3, 'description' => 'نوشتن برنامه‌های ساده برای کنترل فرایندهای صنعتی.'],
                ['title' => 'ریاضیات فنی و کاربرد آن در مکاترونیک', 'weekly_hours' => 3, 'description' => 'کاربرد مفاهیم ریاضی در تحلیل مدار و سامانه‌های کنترلی.'],
            ],
            GradeLevel::Eleventh->value => [
                ['title' => 'کنترل صنعتی و PLC', 'weekly_hours' => 4, 'description' => 'برنامه‌نویسی کنترل‌کننده‌های منطقی و پیاده‌سازی فرایندهای صنعتی.'],
                ['title' => 'سیستم‌های مکاترونیکی', 'weekly_hours' => 3, 'description' => 'طراحی و ساخت سامانه‌های مکاترونیکی و تحلیل عملکرد آن‌ها.'],
                ['title' => 'مدارهای آنالوگ و دیجیتال', 'weekly_hours' => 3, 'description' => 'تحلیل و طراحی مدارهای آنالوگ و دیجیتال در تجهیزات صنعتی.'],
                ['title' => 'رباتیک و اتوماسیون', 'weekly_hours' => 3, 'description' => 'آشنایی با بازوهای رباتیک و کاربرد اتوماسیون در خط تولید.'],
                ['title' => 'اندازه‌گیری و ابزار دقیق', 'weekly_hours' => 3, 'description' => 'کاربرد حسگرها و ابزارهای اندازه‌گیری دقیق در فرایندهای صنعتی.'],
            ],
            GradeLevel::Twelfth->value => [
                ['title' => 'طراحی و ساخت سیستم‌های مکاترونیکی', 'weekly_hours' => 4, 'description' => 'طراحی کامل یک سامانه مکاترونیکی از تحلیل تا ساخت و راه‌اندازی.'],
                ['title' => 'کنترل پیشرفته و اتوماسیون صنعتی', 'weekly_hours' => 3, 'description' => 'به‌کارگیری روش‌های پیشرفته کنترل برای خودکارسازی فرایندها.'],
                ['title' => 'رباتیک و اتوماسیون پیشرفته', 'weekly_hours' => 3, 'description' => 'پیاده‌سازی پروژه‌های رباتیک و سامانه‌های خودکار در مقیاس صنعتی.'],
                ['title' => 'کارگاه پروژه پایانی مکاترونیک', 'weekly_hours' => 4, 'description' => 'ساخت و ارائه یک پروژه عملی مکاترونیکی همراه با مستندات فنی.'],
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * The courses are keyed on their major, their grade level and their
     * order, so correcting a title updates the row instead of adding a
     * second one. The courses of a major and a grade level that are not in
     * the list any more are removed.
     */
    public function run(): void
    {
        foreach (self::COURSES as $slug => $grades) {
            $major = Major::query()->where('slug', $slug)->first();

            if ($major === null) {
                continue;
            }

            foreach ($grades as $grade => $courses) {
                $orders = [];

                foreach ($courses as $index => $course) {
                    $order = $index + 1;
                    $orders[] = $order;

                    MajorCourse::query()->updateOrCreate(
                        [
                            'major_id' => $major->id,
                            'grade' => $grade,
                            'order' => $order,
                        ],
                        [
                            'title' => $course['title'],
                            'description' => $course['description'],
                            'weekly_hours' => $course['weekly_hours'],
                        ],
                    );
                }

                MajorCourse::query()
                    ->where('major_id', $major->id)
                    ->where('grade', $grade)
                    ->whereNotIn('order', $orders)
                    ->delete();
            }
        }
    }
}
