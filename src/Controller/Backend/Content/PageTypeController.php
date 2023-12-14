<?php

namespace App\Controller\Backend\Content;

use App\Entity\PageType;
use App\Form\backend\admin\dashboard\content\pageType\edit\PageTypeEditType;
use App\Manager\Backend\Content\PageType\PageTypeManager;
use App\Repository\PageTypeRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageTypeController extends AbstractController
{
    public function __construct(private readonly PageTypeRepository $pageTypeRepository){}

    #[Route('/backend/admin/content/page-type/list', name: 'app_backend_content_page_type_list')]
    public function pageTypeList(
        Request            $request,
        PaginatorInterface $paginator,
    ): Response
    {
        /** @var string|null $search */
        $search = $request->query->get('search');

        $query = $this->getQueryResults($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/pageType/list/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/content/page-type/create', name: 'app_backend_content_page_type_create', methods: ['GET', 'POST'])]
    public function pageTypeCreate(Request $request, PageTypeManager $pageTypeManager): Response
    {
        $pageType = new PageType();
        $form = $this->createForm(PageTypeEditType::class, $pageType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageTypeManager->pageTypeCreate($pageType);

            $pageTypeName = $pageType->getName();
            $this->addFlash('success', "Le page type $pageTypeName a été créé avec succès.");

            return $this->redirectToRoute('app_backend_content_page_type_list');
        }

        return $this->render('backend/admin/dashboard/content/pageType/create/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/content/page-type/{id}/edit', name: 'app_backend_content_page_type_edit', methods: ['GET', 'POST'])]
    public function pageTypeEdit(PageType $pageType, Request $request, PageTypeManager $pageTypeManager): Response
    {
        $form = $this->createForm(PageTypeEditType::class, $pageType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageTypeManager->pageTypeEdit($pageType);

            $pageTypeName = $pageType->getName();
            $this->addFlash('success', "Le type de page $pageTypeName a été modifiée avec succès.");

            return $this->redirectToRoute('app_backend_content_page_type_list');
        }

        return $this->render('backend/admin/dashboard/content/pageType/edit/edit.html.twig', [
            'form' => $form->createView(),
            'pageType' => $pageType
        ]);
    }

    #[Route('/backend/admin/content/page-type/{id}/delete', name: 'app_backend_content_page_type_delete')]
    public function pageTypeDelete(PageType $pageType, PageTypeManager $pageTypeManager): Response
    {
        $pageTypeManager->pageTypeDelete($pageType);

        $pageTypeName = $pageType->getName();

        $this->addFlash('success', "Le type de page $pageTypeName a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_page_type_list');
    }

    #[Route('/backend/admin/content/page-type/ajax-search', name: 'app_backend_content_page_type_ajax_search')]
    public function ajaxSearch(Request $request, PageTypeRepository $pageTypeRepository): JsonResponse
    {
        /** @var string $searchTerm */
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

    // Private methods

    // Private methods

    /**
     * Get the query results.
     *
     * @param string|null $search The search criteria (optional).
     *
     * @return PageType[] The query results.
     */
    private function getQueryResults(?string $search): array
    {
        if (!empty($search)) {
            return $this->pageTypeRepository->findByCriteria($search, 'DESC');
        }

        return $this->pageTypeRepository->findAllOrderBy('DESC');
    }
}