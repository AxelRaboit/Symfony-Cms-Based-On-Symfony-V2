<?php

namespace App\Service\Website;

use App\Entity\Website;
use App\Repository\WebsiteRepository;
use Exception;
use Symfony\Component\HttpKernel\KernelInterface;

class WebsiteService
{
    public function __construct(
        private readonly WebsiteRepository $websiteRepository,
        private readonly KernelInterface $kernel,
    ) {}

    public const DEV_HOSTNAME_NUMBERS = '127.0.0.1';
    public const DEV_HOSTNAME_LOCALHOST = 'localhost';

    /**
     * @throws Exception
     */
    public function getCurrentWebsite(string $hostname, bool $isArray = false): Website|array|null
    {
        /* If it's the dev env */
        if (self::DEV_HOSTNAME_NUMBERS === $hostname || self::DEV_HOSTNAME_LOCALHOST === $hostname) {
            $website = $this->websiteRepository->findOneBy(['hostname' => self::DEV_HOSTNAME_NUMBERS]);

            if (!$website) {
                $website = $this->websiteRepository->findOneBy(['hostname' => self::DEV_HOSTNAME_LOCALHOST]);
            }

            if (!$website) {
                throw new Exception('Website not found, or make sure to provide a valid hostname for dev environment');
            }
            /* If it's a production env (staging, preprod, prod) */
        } else {
            if (false === mb_strpos($hostname, 'www.')) {
                if ('prod' === $this->kernel->getEnvironment()) {
                    $hostname = 'www.'.$hostname;
                }
            }

            $website = $this->websiteRepository->findOneBy(['hostname' => $hostname]);

            if (!$website) {
                throw new Exception('Website not found Provided hostnaname: '.$hostname);
            }
        }

        if ($isArray) {
            $website = $website->toArray();
        }

        return $website;
    }

    /**
     * @throws Exception
     */
    public function getWebsite(): Website
    {
        $website = $this->websiteRepository->findOneBy([]);

        if (!$website) {
            throw new Exception('Website not found');
        }

        return $website;
    }
}