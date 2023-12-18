<?php

declare(strict_types=1);

namespace App\Enum;

class GoogleEnum
{
    public const RECAPTCHA_SCORE_LIMIT = 0.5;

    /**
     * @return array<int, string>
     */
    public static function getAvailable(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return array_values($reflectionClass->getConstants());
    }

    /**
     * @return array<string, mixed>
     */
    public static function getConstants(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return $reflectionClass->getConstants();
    }
}
