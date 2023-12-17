<?php

namespace App\Service\Utils;

use App\Entity\Image;
use App\Repository\PageRepository;
use App\Repository\UserBackendRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class SiteMapService
{
    public function __construct(
        private PageRepository $pageRepository,
        private UserBackendRepository $userBackendRepository,
        private UrlGeneratorInterface $urlGenerator,
        private string $imageDirectoryNoBeginningSlash,
    ) {
    }

    /**
     * Retrieves all editorial pages for the site map.
     *
     * @return array<mixed> an array of editorial pages for the site map, with each page's URL, last modification date, change frequency, priority, banner image, and thumbnail image
     */
    public function findAllEditorialPages(): array
    {
        $pages = $this->pageRepository->findAllEditorialPublishedPagesForSiteMap();

        $urls = [];

        foreach ($pages as $page) {
            $banner = $this->getImageData($page['page']->getBanner());
            $thumbnail = $this->getImageData($page['page']->getThumbnail());

            $urls[] = [
                'loc' => $page['url'],
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

    /**
     * Retrieves all backend profiles for users.
     *
     * @return array<mixed> returns an array of backend profile URLs
     */
    public function findAllUserBackendProfiles(): array
    {
        $userBackends = $this->userBackendRepository->findAll();

        $urls = [];

        foreach ($userBackends as $userBackend) {
            $urls[] = [
                'loc' => $this->urlGenerator->generate('app_backend_user_backend_profile', ['id' => $userBackend->getId()]),
                'lastmod' => $userBackend->getUpdatedAt() ? $userBackend->getUpdatedAt()->format('Y-m-d') : null,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        }

        return $urls;
    }

    /**
     * Retrieves the image data.
     *
     * @param Image|null $image the image object to retrieve data from
     *
     * @return array<mixed>|null Returns an array with the image data including the name, title, and caption.
     *                           If the image is null, null is returned.
     */
    private function getImageData(?Image $image): ?array
    {
        if (null === $image) {
            return null;
        }

        $imageName = $this->imageDirectoryNoBeginningSlash.$image->getName();
        $imageTitle = $image->getTitle();
        $imageCaption = $image->getDescription();

        return [
            'name' => $imageName,
            'title' => $imageTitle,
            'caption' => $imageCaption,
        ];
    }
}
