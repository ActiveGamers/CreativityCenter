<?php

namespace App\Enums;

enum AdmissionStatus: string
{
    case New = 'new';

    case Reviewing = 'reviewing';

    case Accepted = 'accepted';

    case Rejected = 'rejected';

    /**
     * The Persian label of the admission status.
     */
    public function label(): string
    {
        return match ($this) {
            self::New => 'ثبت‌شده',
            self::Reviewing => 'در حال بررسی',
            self::Accepted => 'پذیرفته‌شده',
            self::Rejected => 'پذیرفته‌نشده',
        };
    }
}
