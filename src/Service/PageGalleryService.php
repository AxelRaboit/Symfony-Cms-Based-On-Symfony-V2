<?php

namespace App\Service;

use App\Entity\Page;
use App\Repository\PageGalleryRepository;
use Symfony\Component\HttpFoundation\Request;

class PageGalleryService
{
    public function __construct(
        private readonly PageGalleryRepository $pageGalleryRepository,
    ) {}

    public function getPageGalleryElements(Page $page): array
    {
        $gallery = $this->pageGalleryRepository->getPageGallery($page);

        $pageElements = [];

        foreach ($gallery as $key => $item) {
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