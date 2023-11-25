<?php

namespace App\Manager\Backend\AdvancedData\UserBackend;

use App\Manager\AbstractManager;
use App\Repository\UserBackendRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserBackendManager extends AbstractManager
{

    public function __construct(
        EntityManagerInterface $em,
        private readonly UserBackendRepository $userBackendRepository)
    {
        parent::__construct($em);
    }

    public function userBackendDelete(int $id): void
    {
        $user = $this->userBackendRepository->find($id);

        if ($user) {
            $this->remove($user);
        }
    }
}