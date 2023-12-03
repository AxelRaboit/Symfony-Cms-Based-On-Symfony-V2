<?php

namespace App\Controller\Backend\UserBackend\profile;

use App\Entity\UserBackend;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendController extends AbstractController
{
    #[Route('/backend/admin/user-backend/{id}/profile', name: 'app_backend_user_backend_profile', methods: ['GET'])]
    public function userBackendProfile(UserBackend $userBackend): Response
    {
        return $this->render('backend/admin/dashboard/userBackend/profile/userBackendProfile.html.twig', [
            'userBackend' => $userBackend,
        ]);
    }
}