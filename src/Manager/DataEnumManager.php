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
    ) {
        parent::__construct($em);
    }

    /**
     * @throws \Exception
     */
    public function getDataEnumValue(
        int $dataEnum
    ): bool|string|null {
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

    /**
     * @throws \Exception
     */
    public function getPagebyDataDevKey(
        int $pageDevKey
    ): Page {
        $page = $this->PageRepository->findOneBy(['devKey' => $pageDevKey]);

        if (!$page) {
            throw new \Exception('Page not found');
        }

        return $page;
    }

    /**
     * Creates a DataEnum object from an array of parameters.
     *
     * @param array<string, mixed> $params
     */
    public function createFromArray(
        array $params = []
    ): DataEnum {
        $dataEnum = new DataEnum();

        if (array_key_exists('id', $params) && is_int($params['id'])) {
            $dataEnum->setId($params['id']);
        }

        if (array_key_exists('name', $params) && is_string($params['name'])) {
            $dataEnum->setName($params['name']);
        }

        if (array_key_exists('category', $params) && (is_string($params['category']) || is_null($params['category']))) {
            $dataEnum->setCategory($params['category']);
        }

        if (array_key_exists('value', $params) && (is_string($params['value']) || is_null($params['value']))) {
            $dataEnum->setValue($params['value']);
        }

        if (array_key_exists('dev_key', $params) && is_int($params['dev_key'])) {
            $dataEnum->setDevKey($params['dev_key']);
        }

        if (array_key_exists('is_system', $params) && is_bool($params['is_system'])) {
            $dataEnum->setIsSystem($params['is_system']);
        }

        $this->save($dataEnum);

        return $dataEnum;
    }
}
