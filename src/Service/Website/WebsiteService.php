<?php

namespace App\Service\Website;

use App\Entity\Website;
use App\Enum\WebsiteEnum;
use App\Repository\WebsiteRepository;

readonly class WebsiteService
{
    public function __construct(
        private WebsiteRepository $websiteRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function getCurrentWebsite(): Website|null
    {
        $website = $this->websiteRepository->findOneBy(['name' => WebsiteEnum::WEBSITE_NAME_DEFAULT]);

        if (!$website) {
            throw new \Exception('Website not found');
        }

        return $website;
    }
}
