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
    public const DATA_PAGE_ERROR_404_DEV_KEY = 10008;
    public const DATA_PAGE_HOMEPAGE_DEV_KEY = 10009;
    public const DATA_PAGE_CONTACT_DEV_KEY = 10010;
    public const DATA_TEMPLATE_EMAIL_CONTACT_DEV_KEY = 10011;

    /**
     * @var array<int, array<string, bool|int|string|null>>
     */
    public static array $data = [
        self::DATA_TEMPLATE_EMAIL_CONTACT_DEV_KEY => [
            'name' => 'DATA_TEMPLATE_EMAIL_CONTACT_DEV_KEY',
            'dev_key' => self::DATA_TEMPLATE_EMAIL_CONTACT_DEV_KEY,
            'value' => 'email/template/default-contact.html.twig',
            'category' => 'email',
            'is_system' => true,
        ],
        self::DATA_PAGE_CONTACT_DEV_KEY => [
            'name' => 'DATA_PAGE_CONTACT_DEV_KEY',
            'dev_key' => self::DATA_PAGE_CONTACT_DEV_KEY,
            'value' => null,
            'category' => 'page',
            'is_system' => true,
        ],
        self::DATA_PAGE_HOMEPAGE_DEV_KEY => [
            'name' => 'DATA_PAGE_HOMEPAGE_DEV_KEY',
            'dev_key' => self::DATA_PAGE_HOMEPAGE_DEV_KEY,
            'value' => null,
            'category' => 'page',
            'is_system' => true,
        ],
        self::DATA_PAGE_BACKEND_LOGIN_DEV_KEY => [
            'name' => 'DATA_PAGE_BACKEND_LOGIN_DEV_KEY',
            'dev_key' => self::DATA_PAGE_BACKEND_LOGIN_DEV_KEY,
            'value' => null,
            'category' => 'page',
            'is_system' => true,
        ],
        self::DATA_PAGE_BACKEND_REGISTER_DEV_KEY => [
            'name' => 'DATA_PAGE_BACKEND_REGISTER_DEV_KEY',
            'dev_key' => self::DATA_PAGE_BACKEND_REGISTER_DEV_KEY,
            'value' => null,
            'category' => 'page',
            'is_system' => true,
        ],
        self::DATA_RECAPTCHA_SECRET_DEV_KEY => [
            'name' => 'DATA_RECAPTCHA_SECRET_DEV_KEY',
            'dev_key' => self::DATA_RECAPTCHA_SECRET_DEV_KEY,
            'value' => null,
            'category' => 'google',
            'is_system' => true,
        ],
        self::DATA_RECAPTCHA_PUBLIC_DEV_KEY => [
            'name' => 'DATA_RECAPTCHA_PUBLIC_DEV_KEY',
            'dev_key' => self::DATA_RECAPTCHA_PUBLIC_DEV_KEY,
            'value' => null,
            'category' => 'google',
            'is_system' => true,
        ],
        self::DATA_FRONTEND_MENU_DEV_KEY => [
            'name' => 'DATA_FRONTEND_MENU_DEV_KEY',
            'dev_key' => self::DATA_FRONTEND_MENU_DEV_KEY,
            'value' => 'frontend',
            'category' => 'menu',
            'is_system' => true,
        ],
        self::DATA_FRONTEND_FOOTER_DEV_KEY => [
            'name' => 'DATA_FRONTEND_FOOTER_DEV_KEY',
            'dev_key' => self::DATA_FRONTEND_FOOTER_DEV_KEY,
            'value' => 'footer',
            'category' => 'footer',
            'is_system' => true,
        ],
        self::DATA_APPLICATION_MENU_DEV_KEY => [
            'name' => 'DATA_APPLICATION_MENU_DEV_KEY',
            'dev_key' => self::DATA_APPLICATION_MENU_DEV_KEY,
            'value' => 'application',
            'category' => 'menu',
            'is_system' => true,
        ],
        self::DATA_ADMIN_MENU_DEV_KEY => [
            'name' => 'DATA_ADMIN_MENU_DEV_KEY',
            'dev_key' => self::DATA_ADMIN_MENU_DEV_KEY,
            'value' => 'admin',
            'category' => 'menu',
            'is_system' => true,
        ],
        self::DATA_PAGE_ERROR_404_DEV_KEY => [
            'name' => 'DATA_PAGE_ERROR_404_DEV_KEY',
            'dev_key' => self::DATA_PAGE_ERROR_404_DEV_KEY,
            'value' => null,
            'category' => 'page',
            'is_system' => true,
        ],
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
