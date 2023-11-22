<?php

namespace App\Manager;

use App\Entity\DataEnum;
use App\Entity\Page;
use App\Repository\DataEnumRepository;
use App\Repository\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DataEnumManager extends BaseManager
{
    public function __construct(
        private readonly DataEnumRepository $dataEnumRepository,
        private readonly PageRepository $PageRepository,
        private readonly EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private ParameterBagInterface $parameters,
        private RequestStack $requestStack,
        private TokenStorageInterface $tokenStorage,
    ) {
        parent::__construct(
            $em,
            $validator,
            $logger,
            $translator,
            $parameters,
            $requestStack,
            $tokenStorage
        );
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

        return $this->save($dataEnum);
    }

    public function save(
        DataEnum $dataEnum
    ): DataEnum {
        $em = $this->em();
        $em->persist($dataEnum);
        $em->flush();

        return $dataEnum;
    }

    // Base

    public function em(
        bool $refresh = false
    ): EntityManagerInterface {
        if (false === $this->em->getConnection()->isConnected()) {
            $this->em->getConnection()->close();
            $this->em->getConnection()->connect();
        }

        return $this->em;
    }
}