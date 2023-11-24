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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        path: '{uri}',
        name: self::PAGE_DEFAULT,
        requirements: [
            'uri' => '.+',
        ],
    )]
    public function index(string $uri, Request $request): Response
    {
        $page = $this->pageRepository->getPageBySlug($uri);
        $routes = $this->routeService->getRoutes();

        if (null !== $page) {
            if ($page->getDevCodeRouteName()) {
                $route = $routes[$page->getDevCodeRouteName()];

                // Check if the route's path does not contain '{uri}' or '{id}' placeholders.
                // If true, it means the route does not require these dynamic parameters,
                // and we can directly forward the request to the specified controller
                // with the page details.
                if (!mb_strstr($route->getPath(), '{uri}') && !mb_strstr($route->getPath(), '{id}')) {
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

        if ($route = $this->redirectOnRoute($request, $routes)) {
            return $this->forward($route->getDefaults()['_controller']);
        }

        throw new NotFoundHttpException("Page not found");
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