<?php

namespace App\Manager\Backend\Content\Page;

use App\Entity\Page;
use App\Manager\AbstractManager;
use App\Repository\ImageRepository;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class PageManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly PageRepository $pageRepository,
        private readonly ImageRepository $imageRepository
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws \Exception
     */
    public function pageCreate(Page $page, int $bannerId, int $thumbnailId): void
    {
        // Create and set dev key
        $devKey = $this->pageRepository->createDevKey();
        $page->setDevKey($devKey);

        // Set banner
        $banner = $this->imageRepository->find($bannerId);
        $page->setBanner($banner);

        // Set thumbnail
        $thumbnail = $this->imageRepository->find($thumbnailId);
        $page->setThumbnail($thumbnail);

        $this->save($page);
    }

    public function pageDelete(Page $page): void
    {
        $this->remove($page);
    }

    public function pageEdit(Page $page): void
    {
        $this->save($page);
    }
}