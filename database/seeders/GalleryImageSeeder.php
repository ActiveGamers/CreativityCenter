<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The gallery images of the website.
     *
     * NOTE: Every entry points to a neutral SVG placeholder. The school has to
     * send the real photographs of the classrooms, laboratories and events.
     *
     * @var array<int, array<string, mixed>>
     */
    public const IMAGES = [
        [
            'title' => 'کلاس درس شبکه',
            'caption' => 'تصویر نمونه از کلاس درس شبکه.',
            'path' => '/images/gallery/placeholder-1.svg',
            'album' => 'آموزشی',
            'order' => 1,
            'is_published' => true,
        ],
        [
            'title' => 'آزمایشگاه رایانه',
            'caption' => 'تصویر نمونه از آزمایشگاه رایانه.',
            'path' => '/images/gallery/placeholder-2.svg',
            'album' => 'آموزشی',
            'order' => 2,
            'is_published' => true,
        ],
        [
            'title' => 'کارگاه مکاترونیک',
            'caption' => 'تصویر نمونه از کارگاه مکاترونیک.',
            'path' => '/images/gallery/placeholder-3.svg',
            'album' => 'آموزشی',
            'order' => 3,
            'is_published' => true,
        ],
        [
            'title' => 'مراسم تجلیل از دانش‌آموزان برتر',
            'caption' => 'تصویر نمونه از مراسم تجلیل از دانش‌آموزان برتر.',
            'path' => '/images/gallery/placeholder-4.svg',
            'album' => 'افتخارات',
            'order' => 4,
            'is_published' => true,
        ],
        [
            'title' => 'بازدید از پروژه‌های دانش‌آموزی',
            'caption' => 'تصویر نمونه از بازدید از پروژه‌های دانش‌آموزی.',
            'path' => '/images/gallery/placeholder-5.svg',
            'album' => 'افتخارات',
            'order' => 5,
            'is_published' => true,
        ],
        [
            'title' => 'ارزیابی پایانی سال تحصیلی',
            'caption' => 'تصویر نمونه از ارزیابی پایانی سال تحصیلی.',
            'path' => '/images/gallery/placeholder-6.svg',
            'album' => 'افتخارات',
            'order' => 6,
            'is_published' => true,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::IMAGES as $image) {
            GalleryImage::query()->updateOrCreate(
                ['path' => $image['path']],
                $image,
            );
        }
    }
}
