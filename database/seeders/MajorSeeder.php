<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MajorSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The majors offered by the school.
     *
     * @var array<int, array<string, mixed>>
     */
    public const MAJORS = [
        [
            'title' => 'شبکه و نرم‌افزار رایانه',
            'slug' => 'network-and-software',
            'subtitle' => 'دانش بنیان در حوزه فناوری اطلاعات',
            'summary' => 'طراحی، پیاده‌سازی و مدیریت شبکه‌های رایانه‌ای به همراه توسعه نرم‌افزارهای تحت وب.',
            'description' => 'رشته شبکه و نرم‌افزار رایانه دانش‌آموزان را برای طراحی، پیاده‌سازی و مدیریت زیرساخت‌های شبکه و همچنین توسعه نرم‌افزارهای تحت وب آماده می‌کند. آموزش در این رشته از مبانی سخت‌افزار و سیستم‌عامل آغاز می‌شود و در پایه دوازدهم به مباحث پیشرفته مانند امنیت شبکه، مدیریت سرور و پروژه‌های عملی می‌رسد.',
            'icon' => 'network',
            'accent' => 'brand',
            'seats' => 30,
            'order' => 1,
            'is_active' => true,
        ],
        [
            'title' => 'مکاترونیک',
            'slug' => 'mechatronics',
            'subtitle' => 'تلاقی مکانیک، الکترونیک و کنترل',
            'summary' => 'طراحی و کنترل سامانه‌های صنعتی هوشمند با تکیه بر الکترونیک، کنترل و اتوماسیون.',
            'description' => 'رشته مکاترونیک با ترکیب دانش مکانیک و الکترونیک، زمینه ساخت و کنترل سامانه‌های صنعتی هوشمند را فراهم می‌کند. دانش‌آموزان در این رشته با مدارهای آنالوگ و دیجیتال، کنترل صنعتی، رباتیک و اتوماسیون آشنا می‌شوند و در کارگاه‌های عملی، سیستم‌های مکاترونیکی واقعی را طراحی و راه‌اندازی می‌کنند.',
            'icon' => 'mechatronics',
            'accent' => 'accent',
            'seats' => 30,
            'order' => 2,
            'is_active' => true,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * The seeder is keyed on the slug of the major, so a re-run corrects the
     * rows that are already in the database instead of adding new ones.
     */
    public function run(): void
    {
        foreach (self::MAJORS as $major) {
            Major::query()->updateOrCreate(
                ['slug' => $major['slug']],
                $major,
            );
        }
    }
}
