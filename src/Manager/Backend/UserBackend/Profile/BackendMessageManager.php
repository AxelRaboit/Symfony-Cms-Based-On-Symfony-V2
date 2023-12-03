<?php

namespace App\Manager\Backend\UserBackend\Profile;

use App\Entity\BackendMessage;
use App\Manager\AbstractManager;
use App\Repository\UserBackendRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class BackendMessageManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UserBackendRepository $userBackendRepository,
    )
    {
        parent::__construct($em);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws \Exception
     */
    public function messageCreate(BackendMessage $backendMessage, string $sender): void
    {
        $userBackend = $this->findUserBackendByEmail($sender);
        $backendMessage->setSender($userBackend);
        $this->save($backendMessage);
    }

    public function findUserBackendByEmail(string $email): object
    {
        return $this->userBackendRepository->findOneBy(['email' => $email]);
    }
}