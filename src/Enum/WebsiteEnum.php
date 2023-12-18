<?php

declare(strict_types=1);

namespace App\Enum;

class WebsiteEnum
{
    public const CONTACT_EMAIL_DEFAULT = 'axel.raboit@gmail.com';
    public const WEBSITE_NAME_DEFAULT = 'myWebsite';

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
