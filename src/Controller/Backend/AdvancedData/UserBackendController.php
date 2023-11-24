<?php

namespace App\Controller\Backend\AdvancedData;

use App\Repository\UserBackendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendController extends AbstractController
{
    #[Route('/backend/user/backend/list', name: 'app_backend_user_backend_list')]
    public function userList(UserBackendRepository $userBackendRepository): Response
    {
        $users = $userBackendRepository->findAll();

        return $this->render('backend/dashboard/advancedData/userBackend/list.html.twig', [
            'users' => $users,
        ]);
    }
}