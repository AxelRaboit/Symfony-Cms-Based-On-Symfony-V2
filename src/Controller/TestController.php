<?php

namespace App\Controller;

use App\Entity\Page;
use App\Service\Page\PageGalleryService;
use App\Service\Page\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    public function __construct(
        private readonly PageService        $pageService,
        private readonly PageGalleryService $pageGalleryService,
    ) {}

    #[Route('/test', name: 'app_test')]
    public function test(Page $page): Response
    {
        $elements = $this->pageService->getPageElements($page);
        $elements['children'] = $this->pageService->getChildrenFromPage($page);
        $elements['gallery'] = $this->pageGalleryService->getPageGalleryElements($page);

        return $this->render($elements['template'], $elements);
    }
}