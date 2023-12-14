<?php

namespace App\Service\Page;

use App\Entity\Image;
use App\Entity\Page;
use App\Repository\PageGalleryRepository;

class PageGalleryService
{
    public function __construct(
        private readonly PageGalleryRepository $pageGalleryRepository,
    ) {}

    /**
     * Retrieves gallery elements for a given page.
     *
     * @param Page $page
     * @return array<int|string, array{
     *     elements: array{
     *         gallery: array<string, mixed>,
     *         imageElements: array<string, mixed>
     *     }
     * }>
     */
    public function getPageGalleryElements(Page $page): array
    {
        $gallery = $this->pageGalleryRepository->getPageGallery($page);

        $pageElements = [];

        foreach ($gallery as $key => $item) {
            /** @var Image $image */
            $image = $item['image'];
            $itemElement = [];

            if (null !== $image) {
                if (null !== $image->getName()) {
                    $itemElement['imageName'] = $image->getName();
                }

                if (null !== $image->getAlt()) {
                    $itemElement['imageAlt'] = $image->getAlt();
                }
            }

            $pageElements[$key] = [
                'elements' => [
                    'gallery' => $item,
                    'imageElements' => $itemElement,
                ],
            ];
        }

        return $pageElements;
    }
}