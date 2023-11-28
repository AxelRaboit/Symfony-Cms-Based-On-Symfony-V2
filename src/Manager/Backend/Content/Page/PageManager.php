<?php

namespace App\Manager\Backend\Content\Page;

use App\Entity\Page;
use App\Manager\AbstractManager;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class PageManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly PageRepository $pageRepository,
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws \Exception
     */
    public function pageCreate(Page $page): void
    {
        // Create dev key
        $devKey = $this->pageRepository->createDevKey();
        $page->setDevKey($devKey);

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