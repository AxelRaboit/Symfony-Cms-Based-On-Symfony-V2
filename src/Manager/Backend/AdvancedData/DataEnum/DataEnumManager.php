<?php

namespace App\Manager\Backend\AdvancedData\DataEnum;

use App\Entity\DataEnum;
use App\Manager\AbstractManager;
use App\Repository\DataEnumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class DataEnumManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly DataEnumRepository $dataEnumRepository
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function dataEnumCreate(DataEnum $dataEnum): void
    {
        $lastDevKey = $this->dataEnumRepository->getLastDevKey();
        $dataEnum->setDevKey($lastDevKey + 1);
        $dataEnum->setIsSystem(false);
        $this->save($dataEnum);
    }

    public function dataEnumDelete(DataEnum $dataEnum): void
    {
        $this->remove($dataEnum);
    }

    public function dataEnumEdit(DataEnum $dataEnum): void
    {
        $this->save($dataEnum);
    }
}