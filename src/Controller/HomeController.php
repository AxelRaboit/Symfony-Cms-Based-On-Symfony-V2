<?php

namespace App\Controller;

use App\Repository\PageRepository;
use App\Service\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PageService $pageService, PageRepository $pageRepository): Response
    {
        $elements = $pageService->getPageElements($pageRepository->findOneBy(['title' => 'Accueil']));

        return $this->render($elements['template'], $elements);
    }
}