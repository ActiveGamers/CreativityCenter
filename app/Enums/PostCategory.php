<?php

namespace App\Enums;

enum PostCategory: string
{
    case News = 'news';

    case Announcement = 'announcement';

    case Event = 'event';

    case Achievement = 'achievement';

    /**
     * The Persian label of the post category.
     */
    public function label(): string
    {
        return match ($this) {
            self::News => 'اخبار',
            self::Announcement => 'اطلاعیه',
            self::Event => 'رویداد',
            self::Achievement => 'افتخارآفرینی',
        };
    }
}
