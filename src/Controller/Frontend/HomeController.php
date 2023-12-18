<?php

namespace App\Controller\Frontend;

use App\Entity\Page;
use App\Enum\DataEnum;
use App\Repository\PageRepository;
use App\Service\Page\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PageService $pageService, PageRepository $pageRepository): Response
    {
        /** @var Page $page */
        $page = $pageRepository->getPageFromDataNameDevKey((string) DataEnum::$data[DataEnum::DATA_PAGE_HOMEPAGE_DEV_KEY]['name']);

        $elements = $pageService->getPageElements($page);

        return $this->render($elements['template'], $elements);
    }
}
