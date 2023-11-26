<?php

namespace App\Service;

class UtilsService
{
    public function extractEmailFromString(string $string): ?string
    {
        $email = null;
        if (preg_match('/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z]+/', $string, $matches)) {
            $email = $matches[0];
        }

        return $email;
    }
}