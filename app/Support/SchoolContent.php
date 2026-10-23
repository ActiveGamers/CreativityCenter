<?php

namespace App\Support;

/**
 * The long-form marketing copy of the school, shared between the about page
 * and the seeded posts so the wording stays identical everywhere.
 */
final class SchoolContent
{
    /**
     * The introductory paragraph of the school.
     */
    public const INTRO = 'هنرستان جوار شرکت پالایش نفت بندرعباس با مدیریت دکتر نوری‌زاده یکی از برترین هنرستان‌های کشور ایران است. این هنرستان هوشمند با کسب مقام‌های متعدد در حوزه‌های شبکه و صنایع مکاترونیکی و با کسب بیش از ۲۰۰ رتبه ۳ رقمی، بیش از ۵۰ رتبه ۲ رقمی و ۳ رتبه تک رقمی در سه سال اخیر، به یکی از مهم‌ترین هنرستان‌های شهر بندرعباس تبدیل شده است.';

    /**
     * The introductory paragraph of the school.
     */
    public static function intro(): string
    {
        return self::INTRO;
    }
}
