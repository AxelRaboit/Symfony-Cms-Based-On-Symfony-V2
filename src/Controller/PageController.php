<?php

namespace App\Controller;

use App\Entity\Page;
use App\Enum\PageStateEnum;
use App\Enum\UtilsEnum;
use App\Repository\PageRepository;
use App\Service\Page\PageGalleryService;
use App\Service\Page\PageService;
use App\Service\Utils\RouteService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public const PAGE_DEFAULT = 'page_index';

    public function __construct(
        private readonly PageRepository     $pageRepository,
        private readonly PageGalleryService $pageGalleryService,
        private readonly RouteService       $routeService,
        private readonly PageService        $pageService,
    ) {}

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
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
        /** @var Page|null $page */
        $page = $this->pageRepository->getPageByTypeAndSlug($uri);

        $routes = $this->routeService->getRoutes();

        if (null !== $page) {
            if($page->getState() == PageStateEnum::DRAFT) {
                return $this->render('frontend/page/page-not-published.html.twig', [
                    'page' => $page,
                ]);
            }

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

            if(null !== $page->getPageType()) {
                if (null !== $page->getPageType()->getTemplate()) {
                    $elements['template'] = $page->getPageType()->getTemplate();
                } else {
                    $elements['template'] = UtilsEnum::PAGE_DEFAULT_TEMPLATE;
                }
            }

            return $this->render($elements['template'], $elements);
        }

        if ($route = $this->redirectOnRouteSiteMap($request, $routes)) {
            return $this->forward($route->getDefaults()['_controller']);
        }

        throw new NotFoundHttpException("Page not found");
    }

    /**
     * @param Request $request
     * @param array<string, mixed> $routes
     * @return mixed
     */
    private function redirectOnRouteSiteMap(Request $request, array $routes): mixed
    {
        if (mb_strstr($request->getRequestUri(), 'sitemap')) {
            return $routes['sitemap'];
        }

        return null;
    }
}