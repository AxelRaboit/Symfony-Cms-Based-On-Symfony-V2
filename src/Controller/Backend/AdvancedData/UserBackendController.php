<?php

namespace App\Controller\Backend\AdvancedData;

use App\Manager\Backend\AdvancedData\UserBackend\UserBackendManager;
use App\Repository\UserBackendRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendController extends AbstractController
{
    #[Route('/backend/user/backend/list', name: 'app_backend_user_backend_list')]
    public function userList(UserBackendRepository $userBackendRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $query = $userBackendRepository->findByCriteria($search);
        } else {
            $query = $userBackendRepository->findAll();
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/dashboard/advancedData/userBackend/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/backend/user/backend/{id}/delete', name: 'app_backend_user_backend_delete')]
    public function userDelete(UserBackendRepository $userBackendRepository, Request $request, UserBackendManager $userBackendManager): Response
    {
        $user = $userBackendRepository->find($request->get('id'));

        if ($user) {
            $userBackendManager->userBackendDelete($user->getId());
        }

        return $this->redirectToRoute('app_backend_user_backend_list');
    }
}