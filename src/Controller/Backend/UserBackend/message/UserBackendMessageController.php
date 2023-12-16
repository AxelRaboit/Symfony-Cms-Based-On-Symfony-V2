<?php

namespace App\Controller\Backend\UserBackend\message;

use App\Entity\BackendMessage;
use App\Entity\UserBackend;
use App\Form\backend\admin\dashboard\userBackend\message\BackendMessageType;
use App\Manager\Backend\UserBackend\Profile\BackendMessageManager;
use App\Repository\BackendMessageRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBackendMessageController extends AbstractController
{
    /**
     * Lists messages in the backend user's profile.
     *
     * @param Request                  $request                  The request object
     * @param UserBackend              $userBackend              The backend user object
     * @param BackendMessageRepository $backendMessageRepository The repository for backend messages
     * @param PaginatorInterface       $paginator                The paginator object
     *
     * @return Response The response object
     *
     * @throws \Exception
     */
    #[Route('/backend/admin/user-backend/profile/{id}/message/list', name: 'app_backend_user_backend_profile_message_list')]
    public function userBackendProfileMessageList(Request $request, UserBackend $userBackend, BackendMessageRepository $backendMessageRepository, PaginatorInterface $paginator): Response
    {
        if ($this->getUser() instanceof UserBackend) {
            $query = $backendMessageRepository->findAllMessageReceivedByReceiver($userBackend);
        } else {
            $query = null;
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1) // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/userBackend/profile/message/received/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Show the received message for the user in the backend profile.
     * Marks the message as read.
     *
     * @param UserBackend           $userBackend           The user backend entity
     * @param BackendMessage        $backendMessage        The backend message entity
     * @param BackendMessageManager $backendMessageManager The backend message manager
     *
     * @return Response The response object
     *
     * @throws \Exception If an error occurs
     */
    #[Route('/backend/admin/user-backend/profile/{userId}/message/received/{messageId}/show', name: 'app_backend_user_backend_profile_message_received_show')]
    #[ParamConverter('userBackend', options: ['id' => 'userId'])]
    #[ParamConverter('backendMessage', options: ['id' => 'messageId'])]
    public function userBackendProfileMessageShow(UserBackend $userBackend, BackendMessage $backendMessage, BackendMessageManager $backendMessageManager): Response
    {
        $backendMessageManager->messageRead($backendMessage);

        return $this->render('backend/admin/dashboard/userBackend/profile/message/received/show.html.twig', [
            'backendMessage' => $backendMessage,
        ]);
    }

    /**
     * Handle the request to display the list of messages sent by a user in the backend profile.
     */
    #[Route('/backend/admin/user-backend/profile/{id}/message/sent/list', name: 'app_backend_user_backend_profile_message_sent_list')]
    public function userBackendProfileMessageSent(Request $request, UserBackend $userBackend, BackendMessageRepository $backendMessageRepository, PaginatorInterface $paginator): Response
    {
        if ($this->getUser() instanceof UserBackend) {
            $query = $backendMessageRepository->findAllMessageSentBySender($userBackend);
        } else {
            $query = null;
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1) // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/userBackend/profile/message/sent/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Handle the request to create a new message in the backend profile.
     *
     * @param Request               $request               the HTTP request object
     * @param UserBackend           $userBackend           the backend user associated with the profile
     * @param BackendMessageManager $backendMessageManager the backend message manager
     *
     * @return Response the HTTP response object
     *
     * @throws \Exception
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

    /**
     * Handle the request to delete a received message from the backend profile.
     *
     * @param UserBackend           $userBackend           the user backend entity
     * @param BackendMessage        $backendMessage        the backend message entity
     * @param BackendMessageManager $backendMessageManager the backend message manager
     *
     * @return Response the response
     */
    #[Route('/backend/admin/user-backend/profile/{userId}/message/received/{messageId}/delete', name: 'app_backend_user_backend_profile_message_received_delete')]
    #[ParamConverter('userBackend', options: ['id' => 'userId'])]
    #[ParamConverter('backendMessage', options: ['id' => 'messageId'])]
    public function userBackendProfileMessageReceivedDelete(UserBackend $userBackend, BackendMessage $backendMessage, BackendMessageManager $backendMessageManager): Response
    {
        $backendMessageManager->messageDeleteFromReceiver($backendMessage);

        $this->addFlash('success', 'Le message a été supprimé avec succès.');

        return $this->redirectToRoute('app_backend_user_backend_profile_message_list', ['id' => $userBackend->getId()]);
    }

    /**
     * Handle the request to delete a message sent by a user in the backend profile.
     *
     * @param UserBackend           $userBackend           the user who sent the message
     * @param BackendMessage        $backendMessage        the message to be deleted
     * @param BackendMessageManager $backendMessageManager the backend message manager
     */
    #[Route('/backend/admin/user-backend/profile/{userId}/message/sent/{messageId}/delete', name: 'app_backend_user_backend_profile_message_sent_delete')]
    #[ParamConverter('userBackend', options: ['id' => 'userId'])]
    #[ParamConverter('backendMessage', options: ['id' => 'messageId'])]
    public function userBackendProfileMessageSentDelete(UserBackend $userBackend, BackendMessage $backendMessage, BackendMessageManager $backendMessageManager): Response
    {
        $backendMessageManager->messageDeleteFromSender($backendMessage);

        $this->addFlash('success', 'Le message a été supprimé avec succès.');

        return $this->redirectToRoute('app_backend_user_backend_profile_message_sent_list', ['id' => $userBackend->getId()]);
    }
}
