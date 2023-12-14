<?php

namespace App\Controller\Backend\AdvancedData;

use App\Entity\UserBackend;
use App\Form\backend\admin\dashboard\advancedData\userBackend\UserBackendCreateType;
use App\Form\backend\admin\dashboard\advancedData\userBackend\UserBackendEditType;
use App\Manager\Backend\AdvancedData\UserBackend\Information\UserBackendInformationManager;
use App\Manager\Backend\AdvancedData\UserBackend\UserBackendManager;
use App\Repository\UserBackendRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendController extends AbstractController
{
    public function __construct(private readonly UserBackendRepository $userBackendRepository){}

    #[Route('/backend/admin/advanced-data/user-backend/list', name: 'app_backend_advanced_data_user_backend_list')]
    public function userList(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var string|null $search */
        $search = $request->query->get('search');

        $query = $this->getQueryResults($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/advancedData/userBackend/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Create a new user for the backend.
     *
     * @param Request $request The HTTP request object.
     * @param UserBackendManager $userBackendManager The user backend manager.
     *
     * @return Response          The response object.
     */
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

            $this->addFlash('success', "L'utilisateur $userIdentity a été créé avec succès.");

            return $this->redirectToRoute('app_backend_advanced_data_user_backend_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/userBackend/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/advanced-data/user-backend/{id}/edit', name: 'app_backend_advanced_data_user_backend_edit', methods: ['GET', 'POST'])]
    public function userEdit(UserBackend $userBackend, Request $request, UserBackendManager $userBackendManager, UserBackendInformationManager $userBackendInformationManager): Response
    {
        $form = $this->createForm(UserBackendEditType::class, $userBackend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordData = $form->get('password');

            if (isset($passwordData['password']) && $passwordData['password'] instanceof FormInterface) {
                $passwordForm = $passwordData['password'];

                $passwords = [
                    'first' => $passwordForm->has('first') ? $passwordForm->get('first')->getData() : null,
                    'second' => $passwordForm->has('second') ? $passwordForm->get('second')->getData() : null,
                ];

                $userBackendManager->userBackendEdit($userBackend, $passwords);
            }

            if ($form->has('deletePictureProfile')) {
                $deletePictureProfile = $form->get('deletePictureProfile');

                if ($deletePictureProfile->getData()) {
                    $userBackendInformation = $userBackend->getInformation();

                    if ($userBackendInformation !== null) {
                        $userBackendInformationManager->userBackendPictureProfileDelete($userBackendInformation);
                    }
                }
            }

            $userIdentity = $userBackend->getUsername() ?? $userBackend->getEmail();
            $this->addFlash('success', "L'utilisateur $userIdentity a été modifié avec succès.");

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

        $this->addFlash('success', "L'utilisateur $userIdentity a été supprimé avec succès.");

        return $this->redirectToRoute('app_backend_advanced_data_user_backend_list');
    }

    #[Route('/backend/admin/advanced-data/user-backend/ajax-search', name: 'app_backend_advanced_data_user_ajax_search')]
    public function ajaxSearch(Request $request, UserBackendRepository $userBackendRepository): JsonResponse
    {
        /** @var string $searchTerm */
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

    // Private methods

    /**
     * Get the query results.
     *
     * @param string|null $search The search criteria (optional).
     *
     * @return UserBackend[] The query results.
     */
    private function getQueryResults(?string $search): array
    {
        if (!empty($search)) {
            return $this->userBackendRepository->findByCriteria($search, 'DESC');
        }

        return $this->userBackendRepository->findAllOrderBy('DESC');
    }
}