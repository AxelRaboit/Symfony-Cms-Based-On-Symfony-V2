<?php

namespace App\Service\Utils;

use App\Repository\PageRepository;
use App\Repository\UserBackendRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class SiteMapService
{
    public function __construct(
        private PageRepository        $pageRepository,
        private UserBackendRepository $userBackendRepository,
        private UrlGeneratorInterface $urlGenerator,
        private string                $imageDirectoryNoBeginningSlash,
    ) {
    }

    public function findAllEditorialPages(): array
    {
        $pages = $this->pageRepository->findAllPagesForSiteMap();

        $urls = [];

        foreach ($pages as $page) {
            $banner = $this->getImageData($page['page']->getBanner());
            $thumbnail = $this->getImageData($page['page']->getThumbnail());

            $urls[] = [
                'loc' => $page['path'],
                'lastmod' => $page['updatedAt'],
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'images' => [
                    'banner' => $banner,
                    'thumbnail' => $thumbnail,
                ],
            ];
        }

        return $urls;
    }

    public function findAllUserBackendProfiles(): array
    {
        $userBackends = $this->userBackendRepository->findAll();

        $urls = [];

        foreach ($userBackends as $userBackend) {
            $urls[] = [
                'loc' => $this->urlGenerator->generate('app_backend_user_backend_profile', ['id' => $userBackend->getId()]),
                'lastmod' => $userBackend->getUpdatedAt()->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        }

        return $urls;
    }

    private function getImageData($image): ?array
    {
        if (null === $image) {
            return null;
        }

        $imageName = $this->imageDirectoryNoBeginningSlash . $image->getName();
        $imageTitle = $image->getTitle();
        $imageCaption = $image->getDescription();

        return [
            'name' => $imageName,
            'title' => $imageTitle,
            'caption' => $imageCaption,
        ];
    }
}
