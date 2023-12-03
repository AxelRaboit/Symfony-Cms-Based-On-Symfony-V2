<?php

namespace App\Controller\Backend;

use App\Enum\PageStateEnum;
use App\Manager\Backend\UserBackend\Profile\BackendMessageManager;
use App\Repository\BackendMessageRepository;
use App\Repository\DataEnumRepository;
use App\Repository\ImageRepository;
use App\Repository\PageRepository;
use App\Repository\UserApplicationRepository;
use App\Repository\UserBackendRepository;
use App\Service\Dashboard\DashboardService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/dashboard', name: 'app_backend_dashboard')]
    public function dashboard(
        PageRepository            $pageRepository,
        UserBackendRepository     $userBackendRepository,
        UserApplicationRepository $userApplicationRepository,
        ImageRepository           $imageRepository,
        DataEnumRepository        $dataEnumRepository,
        DashboardService          $dashboardService,
        BackendMessageRepository  $backendMessageRepository
    ): Response
    {
        $chartPageTypes = $dashboardService->createChartPageTypes();
        $chartPublishedPagesByPageTypes = $dashboardService->createChartPublishedPagesByPageTypes();

        return $this->render('backend/admin/dashboard/dashboard.html.twig', [
            'pageCount' => $pageRepository->count([]),
            'publishedPageCount' => $pageRepository->count(['state' => PageStateEnum::PUBLISHED]),
            'imageCount' => $imageRepository->count([]),
            'userBackendCount' => $userBackendRepository->count([]),
            'userApplicationCount' => $userApplicationRepository->count([]),
            'dataEnumCount' => $dataEnumRepository->count([]),
            'messageDeletedBySenderAndReceiverCount' => $backendMessageRepository->messageDeletedBySenderAndReceiverCount(),
            'chartPageTypes' => $chartPageTypes,
            'chartPublishedPagesByPageTypes' => $chartPublishedPagesByPageTypes,
        ]);
    }

    #[Route('/backend/admin/dashboard/messages/delete', name: 'app_backend_dashboard_delete_messages_deleted_by_sender_and_receiver')]
    public function purgeMessageDeletedBySenderAndReceiver(BackendMessageManager $backendMessageManager): Response
    {
        $backendMessageManager->deleteMessagesDeletedBySenderAndReceiver();

        $this->addFlash('success', 'Les messages supprimés par l\'expéditeur et le destinataire ont été supprimés avec succès.');

        return $this->redirectToRoute('app_backend_dashboard');
    }
}
