<?php

namespace App\Controller\Backend;

use App\Repository\DataEnumRepository;
use App\Repository\PageRepository;
use App\Repository\UserApplicationRepository;
use App\Repository\UserBackendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    #[Route('/backend/admin/dashboard', name: 'app_backend_dashboard')]
    public function index(
        PageRepository $pageRepository,
        UserBackendRepository $userBackendRepository,
        UserApplicationRepository $userApplicationRepository,
        DataEnumRepository $dataEnumRepository
    ): Response
    {
        return $this->render('backend/admin/dashboard/dashboard.html.twig', [
            'pageCount' => $pageRepository->count([]),
            'userBackendCount' => $userBackendRepository->count([]),
            'userApplicationCount' => $userApplicationRepository->count([]),
            'dataEnumCount' => $dataEnumRepository->count([]),
        ]);
    }
}
