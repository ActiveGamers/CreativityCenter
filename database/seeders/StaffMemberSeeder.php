<?php

namespace Database\Seeders;

use App\Models\StaffMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffMemberSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The management team of the school.
     *
     * @var array<int, array<string, mixed>>
     */
    public const STAFF = [
        [
            'name' => 'جناب دکتر نوری زاده',
            'role' => 'مدیریت',
            'subject' => null,
            'photo' => null,
            'phone' => '09177653185',
            'bio' => 'مدیر هنرستان جوار شرکت پالایش نفت بندرعباس و هدایت‌کننده مجموعه آموزشی و پرورشی این مجموعه.',
            'order' => 1,
        ],
        [
            'name' => 'مهندس ذاکری',
            'role' => 'معاونت',
            'subject' => null,
            'photo' => null,
            'phone' => '09171662606',
            'bio' => 'معاون هنرستان جوار شرکت پالایش نفت بندرعباس و مسئول هماهنگی امور آموزشی دانش‌آموزان.',
            'order' => 2,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::STAFF as $member) {
            StaffMember::query()->updateOrCreate(
                ['name' => $member['name'], 'role' => $member['role']],
                $member,
            );
        }
    }
}
