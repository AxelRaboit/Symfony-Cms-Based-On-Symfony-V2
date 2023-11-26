<?php

namespace App\Controller\Backend\AdvancedData;

use App\Repository\DataEnumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataEnumController extends AbstractController
{
    #[Route('/backend/admin/data/list', name: 'app_backend_data_list')]
    public function dataList(DataEnumRepository $dataEnumRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $dataEnums = $dataEnumRepository->findAll();

        $pagination = $paginator->paginate(
            $dataEnums,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/advancedData/dataEnum/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}