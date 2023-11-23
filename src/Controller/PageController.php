<?php

namespace App\Controller;

use App\Repository\PageRepository;
use App\Service\PageGalleryService;
use App\Service\PageService;
use App\Service\RouteService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public const PAGE_DEFAULT = 'page_index';

    public function __construct(
        private readonly PageRepository     $pageRepository,
        private readonly PageService        $pageService,
        private readonly PageGalleryService $pageGalleryService,
        private readonly RouteService       $routeService,
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    #[Route(
        path: '{slug}',
        name: self::PAGE_DEFAULT,
        requirements: [
            'slug' => '.+',
        ],
    )]
    public function index(string $slug, Request $request): Response
    {
        $page = $this->pageRepository->getPageBySlug($slug);
        $routes = $this->routeService->getRoutes();
        $route = $this->redirectOnRoute($request, $routes);

        if (null !== $page) {
            if ($page->getDevCodeRouteName()) {
                $route = $route[$page->getDevCodeRouteName()];

                if (!mb_strstr($route->getPath(), '{slug}') && !mb_strstr($route->getPath(), '{id}')) {
                    return $this->forward($route->getDefaults()['_controller'], ['page' => $page]);
                }

                $params['slug'] = $page->getSlug();
                $params['page'] = $page;

                return $this->forward($route->getDefaults()['_controller'], $params);
            }

            $elements = $this->pageService->getPageElements($page);

            $elements['children'] = $this->pageService->getChildrenFromPage($page);
            $elements['gallery'] = $this->pageGalleryService->getPageGalleryElements($page);

            return $this->render($elements['template'], $elements);
        }

        if (null !== $route) {
            return $this->forward($route->getDefaults()['_controller']);
        }

        $page = $this->pageService->page404NotFound();
        $elements = $this->pageService->getPageElements($page);
        $response = $this->render($elements['template'], $elements);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);

        return $response;
    }

    private function redirectOnRoute(Request $request, array $routes): mixed
    {
        if (mb_strstr($request->getRequestUri(), 'sitemap')) {
            return $routes['sitemap'];
        }

        if (mb_strstr($request->getRequestUri(), 'admin')) {
            return $routes['admin'];
        }

        return null;
    }
}