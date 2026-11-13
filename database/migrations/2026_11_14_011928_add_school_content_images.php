<?php

use App\Models\GalleryImage;
use App\Models\StaffMember;
use Database\Seeders\GalleryImageSeeder;
use Database\Seeders\StaffMemberSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The stale educational placeholders that the real achievement portraits
     * replace. They are removed so the gallery never shows both.
     *
     * @var array<int, string>
     */
    private const SUPERSEDED_PLACEHOLDERS = [
        '/images/gallery/placeholder-4.svg',
        '/images/gallery/placeholder-5.svg',
        '/images/gallery/placeholder-6.svg',
    ];

    /**
     * Seed the school content images on an already-seeded database.
     *
     * The deploy pipeline seeds only on the very first release, so a content
     * change reaches a live database through this migration rather than the
     * seeders. It is idempotent: it keys on the image path and the staff name,
     * and it is safe to run before the seeders on a fresh install.
     */
    public function up(): void
    {
        $achievementImages = collect(GalleryImageSeeder::IMAGES)
            ->where('album', 'افتخارات')
            ->all();

        /* The deploy pipeline seeds only on the very first release, so the
           content is patched here for a database that has already been
           seeded. A fresh database is still empty at this point and is left
           to the seeders, which keeps an unseeded test database untouched. */
        if (GalleryImage::query()->exists()) {
            foreach ($achievementImages as $image) {
                GalleryImage::query()->updateOrCreate(
                    ['path' => $image['path']],
                    $image,
                );
            }

            GalleryImage::query()
                ->whereIn('path', self::SUPERSEDED_PLACEHOLDERS)
                ->delete();
        }

        $manager = collect(StaffMemberSeeder::STAFF)->firstWhere('role', 'مدیریت');

        if (filled($manager['photo'] ?? null) && StaffMember::query()->exists()) {
            StaffMember::query()
                ->where('name', $manager['name'])
                ->where('role', $manager['role'])
                ->update(['photo' => $manager['photo'], 'updated_at' => DB::raw('updated_at')]);
        }
    }

    /**
     * Restore the placeholders and drop the portraits the migration added.
     */
    public function down(): void
    {
        $portraitPaths = collect(GalleryImageSeeder::IMAGES)
            ->where('album', 'افتخارات')
            ->pluck('path')
            ->all();

        GalleryImage::query()->whereIn('path', $portraitPaths)->delete();

        StaffMember::query()
            ->where('role', 'مدیریت')
            ->update(['photo' => null, 'updated_at' => DB::raw('updated_at')]);
    }
};
