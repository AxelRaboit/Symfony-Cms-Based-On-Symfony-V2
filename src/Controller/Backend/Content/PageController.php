<?php

namespace App\Controller\Backend\Content;

use App\Repository\PageRepository;
use App\Service\StringUtilsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/backend/admin/page/list', name: 'app_backend_page_list')]
    public function pageList(
        PageRepository $pageRepository,
        Request $request,
        PaginatorInterface $paginator,
        StringUtilsService $stringUtilsService
    ): Response
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $search = $stringUtilsService->protectQueryString($search);
            $query = $pageRepository->findByCriteria($search, 'DESC');
        } else {
            $query = $pageRepository->findAllOrderBy('DESC');
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/page/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/backend/admin/page/ajax-search', name: 'app_backend_page_ajax_search')]
    public function ajaxSearch(Request $request, PageRepository $pageRepository): JsonResponse
    {
        $searchTerm = $request->query->get('term');

        $pages = $pageRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($pages as $page) {
            $responseData[] = [
                'id' => $page->getId(),
                'label' => $page->getName()
            ];
        }

        return new JsonResponse($responseData);
    }
}