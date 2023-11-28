<?php

namespace App\Manager;

use App\Entity\DataEnum;
use App\Entity\Page;
use App\Repository\DataEnumRepository;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;

class DataEnumManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly DataEnumRepository $dataEnumRepository,
        private readonly PageRepository $PageRepository,
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws \Exception
     */
    public function getDataEnumValue(
        int $dataEnum
    ): bool|string|null
    {
        $dataEnum = $this->dataEnumRepository->findOneBy(['devKey' => $dataEnum]);

        if (!$dataEnum) {
            throw new \Exception('DataEnum not found');
        }

        $value = $dataEnum->getValue();

        if ('false' === $value || 'true' === $value) {
            $value = 'true' === $value;
        }

        return $value;
    }

    public function getPagebyDataDevKey(
        int $pageDevKey
    ): Page {
        return $this->PageRepository->findOneBy(['devKey' => $pageDevKey]);
    }

    public function createFromArray(
        array $params = []
    ): DataEnum {
        $dataEnum = new DataEnum();

        // ID
        if (\array_key_exists('id', $params)) {
            $dataEnum->setId($params['id']);
        }

        // Name
        if (\array_key_exists('name', $params)) {
            $dataEnum->setName($params['name']);
        }

        // Category
        if (\array_key_exists('category', $params)) {
            $dataEnum->setCategory($params['category']);
        }

        // Value
        if (\array_key_exists('value', $params)) {
            $dataEnum->setValue($params['value']);
        }

        // DevKey
        if (\array_key_exists('dev_key', $params)) {
            $dataEnum->setDevKey($params['dev_key']);
        }

        $this->save($dataEnum);

        return $dataEnum;
    }
}