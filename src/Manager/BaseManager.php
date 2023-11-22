<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private ParameterBagInterface $parameters,
        private RequestStack $requestStack,
        private TokenStorageInterface $tokenStorage,
    ) {
    }
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