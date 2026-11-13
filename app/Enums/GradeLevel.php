<?php

namespace App\Enums;

enum GradeLevel: string
{
    case Tenth = 'tenth';

    case Eleventh = 'eleventh';

    case Twelfth = 'twelfth';

    /**
     * The Persian label of the grade level.
     */
    public function label(): string
    {
        return match ($this) {
            self::Tenth => 'دهم',
            self::Eleventh => 'یازدهم',
            self::Twelfth => 'دوازدهم',
        };
    }

    /**
     * The curriculum order of the grade level (دهم = ۱).
     */
    public function order(): int
    {
        return match ($this) {
            self::Tenth => 1,
            self::Eleventh => 2,
            self::Twelfth => 3,
        };
    }

    /**
     * All grade levels ordered by curriculum order.
     *
     * @return array<int, self>
     */
    public static function ordered(): array
    {
        $levels = self::cases();

        usort($levels, fn (self $a, self $b): int => $a->order() <=> $b->order());

        return $levels;
    }
}
