<?php

declare(strict_types=1);

namespace App\Enum;

use ReflectionClass;

class UtilsEnum
{
    public const PAGE_DEFAULT_TEMPLATE = 'frontend/page/page-default.html.twig';

    /**
     * @return array<int, string>
     */
    public static function getAvailable(): array
    {
        $reflectionClass = new ReflectionClass(self::class);

        return array_values($reflectionClass->getConstants());
    }

    /**
     * @return array<string, mixed>
     */
    public static function getConstants(): array
    {
        $reflectionClass = new ReflectionClass(self::class);

        return $reflectionClass->getConstants();
    }

}