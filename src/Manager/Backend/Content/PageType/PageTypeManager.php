<?php

namespace App\Manager\Backend\Content\PageType;

use App\Entity\PageType;
use App\Manager\AbstractManager;
use App\Repository\PageTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;

class PageTypeManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly PageTypeRepository $pageTypeRepository,
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws Exception
     */
    public function pageTypeCreate(PageType $pageType): void
    {
        // Create and set dev key
        $devKey = $this->pageTypeRepository->createDevKey();
        $pageType->setDevKey($devKey);

        $this->save($pageType);
    }

    public function pageTypeDelete(PageType $pageType): void
    {
        $this->remove($pageType);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function pageTypeEdit(PageType $pageType): void
    {
        // Create and set dev key
        $devKey = $this->pageTypeRepository->createDevKey();
        $pageType->setDevKey($devKey);

        $this->save($pageType);
    }
}