<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    #[Route('/backend/dashboard', name: 'app_backend_dashboard')]
    public function index(): Response
    {
        return $this->render('backend/dashboard/dashboard.html.twig', [
            'controller_name' => 'BackendController',
        ]);
    }
}
