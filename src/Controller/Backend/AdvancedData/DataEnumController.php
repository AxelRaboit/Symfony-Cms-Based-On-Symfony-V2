<?php

namespace App\Controller\Backend\AdvancedData;

use App\Repository\DataEnumRepository;
use App\Service\StringUtilsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataEnumController extends AbstractController
{
    #[Route('/backend/admin/data/list', name: 'app_backend_data_list')]
    public function dataList(DataEnumRepository $dataEnumRepository, Request $request, PaginatorInterface $paginator, StringUtilsService $stringUtilsService): Response
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $search = $stringUtilsService->protectQueryString($search);
            if ($stringUtilsService->stringContainsEmail($search)) {
                $query = $dataEnumRepository->findByCriteria(
                    $stringUtilsService->extractEmailFromString($search)
                );
            } else {
                $query = $dataEnumRepository->findByCriteria($search, 'DESC');
            }
        } else {
            $query = $dataEnumRepository->findAllOrderBy('DESC');
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/advancedData/dataEnum/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/backend/admin/dataenum/backend/ajax-search', name: 'app_backend_data_enum_backend_ajax_search')]
    public function ajaxSearch(Request $request, DataEnumRepository $dataEnumRepository): JsonResponse
    {
        $searchTerm = $request->query->get('term');

        $dataEnums = $dataEnumRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($dataEnums as $dataEnum) {
            $responseData[] = [
                'id' => $dataEnum->getId(),
                'label' => $dataEnum->getName()
            ];
        }

        return new JsonResponse($responseData);
    }
}