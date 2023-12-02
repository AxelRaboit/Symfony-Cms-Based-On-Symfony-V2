<?php

namespace App\Manager\Backend\AdvancedData\UserBackend\Information;

use App\Entity\UserBackendInformation;
use App\Manager\AbstractManager;
use Doctrine\ORM\EntityManagerInterface;

class UserBackendInformationManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
    )
    {
        parent::__construct($em);
    }

    public function userBackendInformationEdit(UserBackendInformation $userBackendInformation): void
    {
        $this->save($userBackendInformation);
    }
}