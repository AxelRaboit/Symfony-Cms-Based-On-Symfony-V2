<?php

namespace App\Controller\Backend\AdvancedData;

use App\Entity\DataEnum;
use App\Form\backend\admin\dashboard\advancedData\dataEnum\DataEnumCreateType;
use App\Form\backend\admin\dashboard\advancedData\dataEnum\DataEnumEditType;
use App\Manager\Backend\AdvancedData\DataEnum\DataEnumManager;
use App\Repository\DataEnumRepository;
use App\Service\StringUtilsService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataEnumController extends AbstractController
{
    #[Route('/backend/admin/dataenum/list', name: 'app_backend_data_enum_list')]
    public function dataEnumList(DataEnumRepository $dataEnumRepository, Request $request, PaginatorInterface $paginator, StringUtilsService $stringUtilsService): Response
    {
        $search = $request->query->get('search');

        if (!empty($search)) {
            $search = $stringUtilsService->protectQueryString($search);
            $query = $dataEnumRepository->findByCriteria($search, 'DESC');
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

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/dataenum/create', name: 'app_backend_data_enum_create', methods: ['GET', 'POST'])]
    public function dataEnumCreate(Request $request, DataEnumManager $dataEnumManager): Response
    {
        $dataEnum = new DataEnum();
        $form = $this->createForm(DataEnumCreateType::class, $dataEnum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataEnumManager->dataEnumCreate($dataEnum);

            $dataEnumName = $dataEnum->getName();

            $this->addFlash('success', "La donnée {$dataEnumName} a été créé avec succès.");

            return $this->redirectToRoute('app_backend_data_enum_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/dataEnum/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/dataenum/{id}/delete', name: 'app_backend_data_enum_delete')]
    public function dataEnumDelete(DataEnum $dataEnum, DataEnumManager $dataEnumManager): Response
    {
        $dataEnumManager->dataEnumDelete($dataEnum);

        $dataEnumName = $dataEnum->getName();

        $this->addFlash('success', "La donnée {$dataEnumName} a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_data_enum_list');
    }

    #[Route('/backend/admin/dataenum/{id}/edit', name: 'app_backend_data_enum_edit', methods: ['GET', 'POST'])]
    public function dataEnumEdit(DataEnum $dataEnum, Request $request, DataEnumManager $dataEnumManager): Response
    {
        $form = $this->createForm(DataEnumEditType::class, $dataEnum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataEnumManager->dataEnumEdit($dataEnum);

            $dataEnumName = $dataEnum->getName();

            $this->addFlash('success', "La donnée {$dataEnumName} a été modifiée avec succès.");

            return $this->redirectToRoute('app_backend_data_enum_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/dataEnum/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/dataenum/backend/ajax-search', name: 'app_backend_data_enum_ajax_search')]
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