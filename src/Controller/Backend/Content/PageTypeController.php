<?php

namespace App\Controller\Backend\Content;

use App\Repository\PageTypeRepository;
use App\Service\Utils\StringUtilsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageTypeController extends AbstractController
{
    #[Route('/backend/admin/content/page-type/list', name: 'app_backend_content_page_type_list')]
    public function pageTypeList(
        PageTypeRepository     $pageTypeRepository,
        Request            $request,
        PaginatorInterface $paginator,
        StringUtilsService $stringUtilsService
    ): Response
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $search = $stringUtilsService->protectQueryString($search);
            $query = $pageTypeRepository->findByCriteria($search, 'DESC');
        } else {
            $query = $pageTypeRepository->findAllOrderBy('DESC');
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/pageType/list/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/backend/admin/content/page-type/ajax-search', name: 'app_backend_content_page_type_ajax_search')]
    public function ajaxSearch(Request $request, PageTypeRepository $pageTypeRepository): JsonResponse
    {
        $searchTerm = $request->query->get('term');

        $pageTypes = $pageTypeRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($pageTypes as $pageType) {
            $responseData[] = [
                'id' => $pageType->getId(),
                'label' => $pageType->getName()
            ];
        }

        return new JsonResponse($responseData);
    }
}