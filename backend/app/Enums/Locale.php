<?php

namespace App\Enums;

enum Locale: string
{
    case EN = 'en';
    case RU = 'ru';

    /**
     * Get all locale values as array
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if locale value is valid
     *
     * @param string|null $locale
     * @return bool
     */
    public static function isValid(?string $locale): bool
    {
        if ($locale === null) {
            return false;
        }

        return in_array($locale, self::values(), true);
    }
}
