<?php

namespace App\Service\Utils;

class StringUtilsService
{
    public function extractEmailFromString(string $string): ?string
    {
        $email = null;
        if (preg_match('/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z]+/', $string, $matches)) {
            $email = $matches[0];
        }

        return $email;
    }

    public function stringContainsEmail(string $string): bool
    {
        return preg_match('/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z]+/', $string, $matches);
    }

    public function protectQueryString(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}