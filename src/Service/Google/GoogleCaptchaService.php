<?php

declare(strict_types=1);

/*
 * (c) No name
 */

namespace App\Service\Google;

use App\Enum\DataEnum;
use App\Manager\DataEnumManager;

readonly class GoogleCaptchaService
{
    public function __construct(private DataEnumManager $dataEnumManager)
    {
    }

    /**
     * @return array<mixed>
     *
     * @throws \Exception
     */
    public function verify(
        string $response
    ): array {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $this->dataEnumManager->getDataEnumValue(DataEnum::DATA_RECAPTCHA_SECRET_DEV_KEY),
            'response' => $response,
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'content' => http_build_query($data),
                'header' => 'Content-type: application/x-www-form-urlencoded',
            ],
        ];
        $context = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success = (object) json_decode((string) $verify);

        if (false === $captcha_success->success) {
            return [
                'success' => false,
                'score' => 0,
            ];
        }
        if (true === $captcha_success->success) {
            return [
                'success' => true,
                'score' => $captcha_success->score,
            ];
        }

        return [
            'success' => false,
            'score' => 0,
        ];
    }
}
