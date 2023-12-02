<?php

namespace App\Service\UserBackend\Information;

use App\Entity\Page;
use App\Enum\DataEnum;
use App\Enum\UtilsEnum;
use App\Repository\PageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\DomCrawler\Crawler;

class UserBackendInformationService
{
    public function __construct(
        private readonly string $pictureProfileDirectoryNoSlash
    ){}

    public function userBackendInformationPictureProfileFileDelete(string $pictureProfileName): void
    {
        $pictureProfilePath = $this->pictureProfileDirectoryNoSlash . '/' . $pictureProfileName;

        if (file_exists($pictureProfilePath)) {
            unlink($pictureProfilePath);
        }
    }

}