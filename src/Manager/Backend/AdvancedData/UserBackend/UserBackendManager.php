<?php

namespace App\Manager\Backend\AdvancedData\UserBackend;

use App\Entity\UserBackend;
use App\Manager\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
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

    public function userBackendCreate(FormInterface $form, UserBackend $userBackend): void
    {
        $userBackend->setPassword(
            $this->userPasswordHasher->hashPassword(
                $userBackend,
                $form->get('password')->getData()
            )
        );
        $userBackend->setRoles(['ROLE_BACKEND']);
        $userBackend->setCreatedAt(new \DateTimeImmutable());
        $userBackend->setUpdatedAt(new \DateTimeImmutable());

        $this->save($userBackend);
    }

    public function userBackendDelete(UserBackend $userBackend): void
    {
        $this->remove($userBackend);
    }

    public function userBackendEdit(UserBackend $userBackend): void
    {
        $this->save($userBackend);
    }
}