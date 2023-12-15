<?php

namespace App\Manager\Backend\AdvancedData\UserBackend;

use App\Entity\UserBackend;
use App\Entity\UserBackendInformation;
use App\Manager\AbstractManager;
use App\Manager\Backend\AdvancedData\UserBackend\Information\UserBackendInformationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBackendManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserBackendInformationManager $userBackendInformationManager
    ) {
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

    public function userBackendDelete(UserBackend $userBackend): void
    {
        $this->remove($userBackend);
    }

    /**
     * @param array<string, string> $passwords
     */
    public function userBackendEdit(UserBackend $userBackend, array $passwords = []): void
    {
        if (!empty($passwords['first']) && $passwords['first'] === $passwords['second']) {
            $hashedPassword = $this->userPasswordHasher->hashPassword(
                $userBackend,
                $passwords['first']
            );

            $userBackend->setPassword($hashedPassword);
        }

        /** @var UserBackendInformation $userInformation */
        $userInformation = $userBackend->getInformation();
        $this->userBackendInformationManager->userBackendInformationEdit($userInformation);

        $this->save($userBackend);
    }
}
