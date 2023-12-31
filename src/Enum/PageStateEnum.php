<?php

declare(strict_types=1);

namespace App\Enum;

class PageStateEnum
{
    public const DRAFT_AND_PUBLISHED = 0;
    public const DRAFT = 1;
    public const PUBLISHED = 2;
    public const DELETED = 3;

    /**
     * @var array<int, string>
     */
    public static array $names = [
        self::DRAFT => 'draft',
        self::PUBLISHED => 'published',
        self::DELETED => 'deleted',
    ];

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
