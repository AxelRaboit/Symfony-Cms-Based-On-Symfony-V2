<?php

declare(strict_types=1);

namespace App\Enum;

class DataEnum
{
    public const DATA_PAGE_BACKEND_LOGIN_DEV_KEY = 10000;
    public const DATA_PAGE_BACKEND_REGISTER_DEV_KEY = 10001;
    public const DATA_RECAPTCHA_SECRET_DEV_KEY = 10002;
    public const DATA_RECAPTCHA_PUBLIC_DEV_KEY = 10003;
    public const DATA_FRONTEND_MENU_DEV_KEY = 10004;
    public const DATA_FRONTEND_FOOTER_DEV_KEY = 10005;
    public const DATA_APPLICATION_MENU_DEV_KEY = 10006;
    public const DATA_ADMIN_MENU_DEV_KEY = 10007;

    public static array $data = [
        self::DATA_PAGE_BACKEND_LOGIN_DEV_KEY => [
            'name' => 'DATA_PAGE_BACKEND_LOGIN_DEV_KEY',
            'dev_key' => self::DATA_PAGE_BACKEND_LOGIN_DEV_KEY,
            'value' => null,
            'category' => 'page',
        ],
        self::DATA_PAGE_BACKEND_REGISTER_DEV_KEY => [
            'name' => 'DATA_PAGE_BACKEND_REGISTER_DEV_KEY',
            'dev_key' => self::DATA_PAGE_BACKEND_REGISTER_DEV_KEY,
            'value' => null,
            'category' => 'page',
        ],
        self::DATA_RECAPTCHA_SECRET_DEV_KEY => [
            'name' => 'DATA_RECAPTCHA_SECRET_DEV_KEY',
            'dev_key' => self::DATA_RECAPTCHA_SECRET_DEV_KEY,
            'value' => null,
            'category' => 'google',
        ],
        self::DATA_RECAPTCHA_PUBLIC_DEV_KEY => [
            'name' => 'DATA_RECAPTCHA_PUBLIC_DEV_KEY',
            'dev_key' => self::DATA_RECAPTCHA_PUBLIC_DEV_KEY,
            'value' => null,
            'category' => 'google',
        ],
        self::DATA_FRONTEND_MENU_DEV_KEY => [
            'name' => 'DATA_FRONTEND_MENU_DEV_KEY',
            'dev_key' => self::DATA_FRONTEND_MENU_DEV_KEY,
            'value' => 'frontend',
            'category' => 'menu',
        ],
        self::DATA_FRONTEND_FOOTER_DEV_KEY => [
            'name' => 'DATA_FRONTEND_FOOTER_DEV_KEY',
            'dev_key' => self::DATA_FRONTEND_FOOTER_DEV_KEY,
            'value' => 'footer',
            'category' => 'footer',
        ],
        self::DATA_APPLICATION_MENU_DEV_KEY => [
            'name' => 'DATA_APPLICATION_MENU_DEV_KEY',
            'dev_key' => self::DATA_APPLICATION_MENU_DEV_KEY,
            'value' => 'application',
            'category' => 'menu',
        ],
        self::DATA_ADMIN_MENU_DEV_KEY => [
            'name' => 'DATA_ADMIN_MENU_DEV_KEY',
            'dev_key' => self::DATA_ADMIN_MENU_DEV_KEY,
            'value' => 'admin',
            'category' => 'menu',
        ],
    ];

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