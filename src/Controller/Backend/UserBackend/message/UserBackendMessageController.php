<?php

namespace App\Controller\Backend\UserBackend\message;

use App\Entity\BackendMessage;
use App\Entity\UserBackend;
use App\Form\backend\admin\dashboard\userBackend\message\BackendMessageType;
use App\Manager\Backend\UserBackend\Profile\BackendMessageManager;
use App\Repository\BackendMessageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendMessageController extends AbstractController
{
    #[Route('/backend/admin/user-backend/profile/{id}/message/list', name: 'app_backend_user_backend_profile_message_list')]
    public function userBackendProfileMessageList(Request $request, UserBackend $userBackend, BackendMessageRepository $backendMessageRepository, PaginatorInterface $paginator): Response
    {
        if ($this->getUser() instanceof UserBackend) {
            $query = $backendMessageRepository->findAllMessageReceivedByReceiverId($userBackend);
        } else {
            $query = null;
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/userBackend/profile/message/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/user-backend/profile/{id}/message/create', name: 'app_backend_user_backend_profile_message_create', methods: ['GET', 'POST'])]
    public function userBackendProfileMessageCreate(Request $request, UserBackend $userBackend, BackendMessageManager $backendMessageManager): Response
    {
        $backendMessage = new BackendMessage();
        $form = $this->createForm(BackendMessageType::class, $backendMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sender = $form->get('sender')->getData();

            $backendMessageManager->messageCreate($backendMessage, $sender);

            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('app_backend_user_backend_profile', ['id' => $userBackend->getId()]);
        }

        return $this->render('backend/admin/dashboard/userBackend/profile/message/create.html.twig', [
            'form' => $form->createView(),
            'userBackend' => $userBackend,
        ]);
    }
}