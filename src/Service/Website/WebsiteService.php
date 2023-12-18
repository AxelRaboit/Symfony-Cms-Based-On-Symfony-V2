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
        /** @var Website|null $website */
        $website = $this->websiteRepository->findOneBy(['name' => WebsiteEnum::WEBSITE_NAME_DEFAULT]);

        if (null == $website) {
            throw new \Exception('Website not foundsss');
        }

        return $website;
    }
}
