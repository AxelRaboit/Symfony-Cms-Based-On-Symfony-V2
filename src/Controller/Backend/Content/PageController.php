<?php

namespace App\Controller\Backend\Content;

use App\Entity\Page;
use App\Form\backend\admin\dashboard\content\page\PageCreateType;
use App\Manager\Backend\Content\Page\PageManager;
use App\Repository\PageRepository;
use App\Service\StringUtilsService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/backend/admin/content/page/list', name: 'app_backend_admin_content_page_list')]
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

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/content/page/create', name: 'app_backend_content_page_create', methods: ['GET', 'POST'])]
    public function pageCreate(Request $request, PageManager $pageManager): Response
    {
        $page = new Page();
        $form = $this->createForm(PageCreateType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageManager->pageCreate($page);

            $pageName = $page->getName();

            $this->addFlash('success', "La page {$pageName} a été créé avec succès.");

            return $this->redirectToRoute('app_backend_admin_content_page_list');
        }

        return $this->render('backend/admin/dashboard/content/page/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/content/page/ajax-search', name: 'app_backend_admin_content_page_ajax_search')]
    public function ajaxSearch(Request $request, PageRepository $pageRepository): JsonResponse
    {
        $searchTerm = $request->query->get('term');

        $pages = $pageRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($pages as $page) {
            $responseData[] = [
                'id' => $page->getId(),
                'label' =>  $page->getName()
            ];
        }

        return new JsonResponse($responseData);
    }
}