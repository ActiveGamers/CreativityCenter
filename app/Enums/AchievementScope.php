<?php

namespace App\Enums;

enum AchievementScope: string
{
    case National = 'national';

    case Province = 'province';

    case Regional = 'regional';

    case School = 'school';

    /**
     * The Persian label of the achievement scope.
     */
    public function label(): string
    {
        return match ($this) {
            self::National => 'کشوری',
            self::Province => 'استانی',
            self::Regional => 'منطقه‌ای',
            self::School => 'مدرسه‌ای',
        };
    }
}
