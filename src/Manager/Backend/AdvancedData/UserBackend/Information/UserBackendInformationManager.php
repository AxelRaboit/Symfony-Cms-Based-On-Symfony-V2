<?php

namespace App\Manager\Backend\AdvancedData\UserBackend\Information;

use App\Entity\UserBackendInformation;
use App\Manager\AbstractManager;
use App\Service\UserBackend\Information\UserBackendInformationService;
use Doctrine\ORM\EntityManagerInterface;

class UserBackendInformationManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $em,
        private readonly UserBackendInformationService $userBackendInformationService
    )
    {
        parent::__construct($em);
    }

    public function userBackendInformationEdit(UserBackendInformation $userBackendInformation): void
    {
        $this->save($userBackendInformation);
    }

    public function userBackendPictureProfileDelete(UserBackendInformation $userBackendInformation): void
    {
        $this->userBackendInformationService->userBackendInformationPictureProfileFileDelete($userBackendInformation->getPictureProfileName());
        $userBackendInformation->setPictureProfileName(null);
        $userBackendInformation->setPictureProfileSize(null);


        $this->save($userBackendInformation);
    }
}