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
     * NOTE: The educational entries point to neutral SVG placeholders; the
     * school has to send the real photographs of the classrooms, laboratories
     * and workshops. The achievements entries are the real portraits of the
     * students whose national results are recorded in the achievements page.
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
            'title' => 'علی پرویز — رتبه اول کنکور کشور',
            'caption' => 'رتبه اول کنکور کشور در رشته مکاترونیک.',
            'path' => '/images/gallery/ali-parviz.jpg',
            'album' => 'افتخارات',
            'order' => 4,
            'is_published' => true,
        ],
        [
            'title' => 'محمدرسول کریمی — پژوهشگر برتر کشوری',
            'caption' => 'پژوهشگر برتر کشوری در سال ۱۴۰۴.',
            'path' => '/images/gallery/mohammad-rasool-karimi.jpg',
            'album' => 'افتخارات',
            'order' => 5,
            'is_published' => true,
        ],
        [
            'title' => 'احمدرضا آوندی — رتبه اول کنکور کشور',
            'caption' => 'رتبه اول کنکور کشور در حوزه رسانه و فضای مجازی.',
            'path' => '/images/gallery/ahmad-reza-avandi.jpg',
            'album' => 'افتخارات',
            'order' => 6,
            'is_published' => true,
        ],
        [
            'title' => 'حجت‌الله محبوبی — عضو تیم ملی والیبال ساحلی',
            'caption' => 'عضو تیم ملی والیبال ساحلی در مسابقات جهانی.',
            'path' => '/images/gallery/hojjat-allah-mahbobi.jpg',
            'album' => 'افتخارات',
            'order' => 7,
            'is_published' => true,
        ],
        [
            'title' => 'عرفان دهقانی — رکورددار روبیک ایران',
            'caption' => 'رکورددار حل مکعب روبیک ایران با زمان ۵/۸۰ ثانیه.',
            'path' => '/images/gallery/erfan-dehghani.jpg',
            'album' => 'افتخارات',
            'order' => 8,
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
