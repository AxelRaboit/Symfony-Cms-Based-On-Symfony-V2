<?php

namespace App\Controller\Backend\AdvancedData;

use App\Entity\UserBackend;
use App\Form\backend\admin\dashboard\advancedData\userBackend\UserBackendCreateType;
use App\Form\backend\admin\dashboard\advancedData\userBackend\UserBackendEditType;
use App\Manager\Backend\AdvancedData\UserBackend\UserBackendManager;
use App\Repository\UserBackendRepository;
use App\Service\Utils\StringUtilsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendController extends AbstractController
{
    #[Route('/backend/admin/advanced-data/user-backend/list', name: 'app_backend_advanced_data_user_backend_list')]
    public function userList(UserBackendRepository $userBackendRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $query = $userBackendRepository->findByCriteria($search, 'DESC');
        } else {
            $query = $userBackendRepository->findAllOrderBy('DESC');
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/advancedData/userBackend/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/backend/admin/advanced-data/user-backend/create', name: 'app_backend_advanced_data_user_backend_create', methods: ['GET', 'POST'])]
    public function userCreate(Request $request, UserBackendManager $userBackendManager): Response
    {
        $userBackend = new UserBackend();
        $form = $this->createForm(UserBackendCreateType::class, $userBackend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();

            $userBackendManager->userBackendCreate($password, $userBackend);

            $userIdentity = $userBackend->getUsername() ?? $userBackend->getEmail();

            $this->addFlash('success', "L'utilisateur {$userIdentity} a été créé avec succès.");

            return $this->redirectToRoute('app_backend_advanced_data_user_backend_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/userBackend/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/advanced-data/user-backend/{id}/edit', name: 'app_backend_advanced_data_user_backend_edit', methods: ['GET', 'POST'])]
    public function userEdit(UserBackend $userBackend, Request $request, UserBackendManager $userBackendManager): Response
    {
        $form = $this->createForm(UserBackendEditType::class, $userBackend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordData = $form->get('password');

            $passwords = [
                'first' => $passwordData['password']['first']->getData(),
                'second' => $passwordData['password']['second']->getData()
            ];

            $userBackendManager->userBackendEdit($userBackend, $passwords);

            $userIdentity = $userBackend->getUsername() ?? $userBackend->getEmail();
            $this->addFlash('success', "L'utilisateur {$userIdentity} a été modifié avec succès.");

            return $this->redirectToRoute('app_backend_advanced_data_user_backend_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/userBackend/edit.html.twig', [
            'form' => $form->createView(),
            'userBackend' => $userBackend,
        ]);
    }

    #[Route('/backend/admin/advanced-data/user-backend/{id}/delete', name: 'app_backend_advanced_data_user_backend_delete')]
    public function userDelete(UserBackend $userBackend, UserBackendManager $userBackendManager): Response
    {
        $userBackendManager->userBackendDelete($userBackend);

        $userIdentity = $userBackend->getUsername() ?? $userBackend->getEmail();

        $this->addFlash('success', "L'utilisateur {$userIdentity} a été supprimé avec succès.");

        return $this->redirectToRoute('app_backend_advanced_data_user_backend_list');
    }

    #[Route('/backend/admin/advanced-data/user-backend/ajax-search', name: 'app_backend_advanced_data_user_ajax_search')]
    public function ajaxSearch(Request $request, UserBackendRepository $userBackendRepository): JsonResponse
    {
        $searchTerm = $request->query->get('term');

        $users = $userBackendRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($users as $user) {
            $responseData[] = [
                'id' => $user->getId(),
                'label' => $user->getUsername() ? $user->getUsername() : $user->getEmail()
            ];
        }

        return new JsonResponse($responseData);
    }
}