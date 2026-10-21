<?php

namespace App\Support;

/**
 * The digit normalisation of the Persian forms.
 *
 * Visitors type their mobile number with the digits of their own keyboard:
 * the Persian digits (۰-۹) or the Arabic-Indic ones (٠-٩). The browser
 * validator converts them before its own check, so the server has to do the
 * same; otherwise a valid number is rejected by the form.
 */
final class PersianDigits
{
    /**
     * The Persian and the Arabic-Indic digits, mapped to their Latin ones.
     *
     * @var array<string, string>
     */
    private const DIGITS = [
        '۰' => '0',
        '۱' => '1',
        '۲' => '2',
        '۳' => '3',
        '۴' => '4',
        '۵' => '5',
        '۶' => '6',
        '۷' => '7',
        '۸' => '8',
        '۹' => '9',
        '٠' => '0',
        '١' => '1',
        '٢' => '2',
        '٣' => '3',
        '٤' => '4',
        '٥' => '5',
        '٦' => '6',
        '٧' => '7',
        '٨' => '8',
        '٩' => '9',
    ];

    /**
     * Convert the Persian and the Arabic-Indic digits of the given value
     * into their Latin ones.
     */
    public static function toLatin(string $value): string
    {
        return strtr($value, self::DIGITS);
    }
}
