<?php

declare(strict_types=1);

namespace App\Enum;

class MediaEnum
{
    public const MEDIA_EXTENSION_PNG = 'png';
    public const MEDIA_EXTENSION_JPG = 'jpg';
    public const MEDIA_EXTENSION_JPEG = 'jpeg';
    public const MEDIA_EXTENSION_WEBP = 'webp';
    public const MEDIA_EXTENSION_ZIP = 'zip';

    public const MEDIA_TEMP_DIRECTORY = 'tmp';
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