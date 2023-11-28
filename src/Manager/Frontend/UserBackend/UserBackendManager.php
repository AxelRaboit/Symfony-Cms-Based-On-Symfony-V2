<?php

namespace App\Manager\Frontend\UserBackend;

use App\Entity\UserBackend;
use App\Manager\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBackendManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
        parent::__construct($em);
    }

    public function userBackendCreate(string $password, UserBackend $userBackend): void
    {
        $userBackend->setPassword(
            $this->userPasswordHasher->hashPassword(
                $userBackend,
                $password
            )
        );
        $userBackend->setRoles(['ROLE_BACKEND']);

        $this->save($userBackend);
    }
}