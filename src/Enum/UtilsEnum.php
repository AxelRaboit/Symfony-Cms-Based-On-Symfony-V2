<?php

declare(strict_types=1);

namespace App\Enum;

class UtilsEnum
{
    public const PAGE_DEFAULT_TEMPLATE = 'frontend/page/page-default.html.twig';
    public static function getAvailable(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return array_values((array) $reflectionClass->getConstants());
    }

    public static function getConstants(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        return (array) $reflectionClass->getConstants();
    }

}