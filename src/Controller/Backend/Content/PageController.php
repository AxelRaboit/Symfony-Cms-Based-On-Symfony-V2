<?php

namespace App\Controller\Backend\Content;

use App\Entity\Page;
use App\Entity\PageType;
use App\Form\backend\admin\dashboard\content\page\PageCreateType;
use App\Form\backend\admin\dashboard\content\page\PageEditType;
use App\Manager\Backend\Content\Page\PageManager;
use App\Repository\ImageRepository;
use App\Repository\PageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(
        private readonly string $imageDirectory,
        private readonly PageRepository $pageRepository
    ) {
    }

    /**
     * Retrieves a list of pages based on the search and state parameters.
     *
     * @param Request $request The HTTP request object.
     * @param PaginatorInterface $paginator The paginator object used for pagination.
     *
     * @return Response The HTTP response object containing the rendered list of pages.
     */
    #[Route('/backend/admin/content/page/list', name: 'app_backend_content_page_list')]
    public function pageList(
        Request $request,
        PaginatorInterface $paginator,
    ): Response {
        /** @var string|null $search */
        $search = $request->query->get('search');
        /** @var int|null $state */
        $state = $request->query->get('state');

        $query = $this->getQueryResults($search, $state);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1) // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/page/list/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Retrieves a list of pages based on the specified page type and search criteria.
     *
     * @param PageRepository $pageRepository The repository to retrieve pages from.
     * @param Request $request The request object containing the search and state parameters.
     * @param PaginatorInterface $paginator The paginator to paginate the query result.
     * @param PageType $pageType The page type object to filter pages by.
     *
     * @return Response The rendered template with the paginated results and page type information.
     */
    #[Route('/backend/admin/content/page/page-type/{id}/list', name: 'app_backend_content_page_page_type_list')]
    public function pagePageTypeList(
        PageRepository $pageRepository,
        Request $request,
        PaginatorInterface $paginator,
        PageType $pageType
    ): Response {
        /** @var string|null $search */
        $search = $request->query->get('search');
        /** @var int|null $state */
        $state = $request->query->get('state');

        if (!empty($search)) {
            $query = $pageRepository->findByCriteriaByPageType($search, $pageType, 'DESC');
        } elseif (!empty($state)) {
            $query = $pageRepository->findByStateAndPageType($pageType, $state, 'DESC');
        } else {
            $query = $pageRepository->findAllByPageTypeOrderBy($pageType, 'DESC');
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1) // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/page/pageType/list.html.twig', [
            'pagination' => $pagination,
            'pageType' => $pageType,
        ]);
    }

    /**
     * Creates a new page and handles the form submission.
     *
     * @param Request $request The request object.
     * @param PageManager $pageManager The page manager object.
     * @param ImageRepository $imageRepository The image repository object.
     * @param PaginatorInterface $paginator The paginator object.
     * @return Response The response object.
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/content/page/create', name: 'app_backend_content_page_create', methods: ['GET', 'POST'])]
    public function pageCreate(Request $request, PageManager $pageManager, ImageRepository $imageRepository, PaginatorInterface $paginator): Response
    {
        $page = new Page();
        $form = $this->createForm(PageCreateType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bannerImageId = (int) $request->request->get('selected_banner_image_id');
            $thumbnailImageId = (int) $request->request->get('selected_thumbnail_image_id');
            $pageManager->pageCreate($page, $bannerImageId, $thumbnailImageId);

            $pageName = $page->getName();
            $this->addFlash('success', "La page $pageName a été créé avec succès.");

            return $this->redirectToRoute('app_backend_content_page_list');
        }

        $currentPage = $request->query->getInt('page', 1);
        $queryBuilder = $imageRepository->createQueryBuilder('i')->orderBy('i.id', 'ASC');
        $pagination = $paginator->paginate($queryBuilder, $currentPage, 18);

        return $this->render('backend/admin/dashboard/content/page/create/create.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * Edits a page and displays the edit form or processes the submitted form.
     *
     * @param Page $page The page object to edit.
     * @param Request $request The request object.
     * @param PageManager $pageManager The page manager object.
     * @param ImageRepository $imageRepository The image repository object.
     * @param PaginatorInterface $paginator The paginator object.
     * @return Response The response object.
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/content/page/{id}/edit', name: 'app_backend_content_page_edit', methods: ['GET', 'POST'])]
    public function dataEnumEdit(Page $page, Request $request, PageManager $pageManager, ImageRepository $imageRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(PageEditType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bannerImageId = (int) $request->request->get('selected_banner_image_id');
            $thumbnailImageId = (int) $request->request->get('selected_thumbnail_image_id');

            $pageManager->pageEdit($page, $bannerImageId, $thumbnailImageId);

            $pageName = $page->getName();
            $this->addFlash('success', "La page $pageName a été modifiée avec succès.");

            return $this->redirectToRoute('app_backend_content_page_list');
        }

        $currentPage = $request->query->getInt('page', 1);
        $queryBuilder = $imageRepository->createQueryBuilder('i')->orderBy('i.id', 'ASC');
        $pagination = $paginator->paginate($queryBuilder, $currentPage, 18);

        return $this->render('backend/admin/dashboard/content/page/edit/edit.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'page' => $page,
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param Page $page The page object to delete.
     * @param PageManager $pageManager The page manager object.
     * @return Response The response after deleting the page.
     */
    #[Route('/backend/admin/content/page/{id}/delete', name: 'app_backend_content_page_delete')]
    public function pageDelete(Page $page, PageManager $pageManager): Response
    {
        $pageManager->pageDelete($page);

        $pageName = $page->getName();

        $this->addFlash('success', "La page $pageName a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_page_list');
    }

    /**
     * Performs an AJAX search for pages based on the given search term.
     *
     * @param Request $request The request object.
     * @param PageRepository $pageRepository The page repository object.
     * @return JsonResponse The JSON response containing the search results.
     */
    #[Route('/backend/admin/content/page/ajax-search', name: 'app_backend_content_page_ajax_search')]
    public function pageAjaxSearch(Request $request, PageRepository $pageRepository): JsonResponse
    {
        /** @var string $searchTerm */
        $searchTerm = $request->query->get('term');

        $pages = $pageRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($pages as $page) {
            $responseData[] = [
                'id' => $page->getId(),
                'label' => $page->getName(),
            ];
        }

        return new JsonResponse($responseData);
    }

    /**
     * Performs an AJAX search for pages belonging to a specific page type based on the given search term.
     *
     * @param PageType $pageType The page type object.
     * @param Request $request The request object.
     * @param PageRepository $pageRepository The page repository object.
     * @return JsonResponse The JSON response containing the search results.
     */
    #[Route('/backend/admin/content/page/page-type/{id}/ajax-search', name: 'app_backend_content_page_page_type_ajax_search')]
    public function pageTypeAjaxSearch(PageType $pageType, Request $request, PageRepository $pageRepository): JsonResponse
    {
        /** @var string $searchTerm */
        $searchTerm = $request->query->get('term');

        $pages = $pageRepository->findByCriteriaByPageType($searchTerm, $pageType);

        $responseData = [];

        foreach ($pages as $page) {
            $responseData[] = [
                'id' => $page->getId(),
                'label' => $page->getName(),
            ];
        }

        return new JsonResponse($responseData);
    }

    /**
     * Retrieves a list of images to be displayed in a gallery through AJAX.
     *
     * @param Request $request The request object.
     * @param ImageRepository $imageRepository The repository to retrieve images from.
     * @param PaginatorInterface $paginator The paginator to paginate the query result.
     *
     * @return JsonResponse The JSON response with the image data and the next page number if available.
     */
    #[Route('/backend/admin/content/page/gallery/ajax', name: 'app_backend_content_page_gallery_ajax')]
    public function galleryAjax(Request $request, ImageRepository $imageRepository, PaginatorInterface $paginator): JsonResponse
    {
        $queryBuilder = $imageRepository->createOrderedQueryBuilder();
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 18;

        /** @var SlidingPagination $pagination */
        $pagination = $paginator->paginate($queryBuilder, $page, $limit);

        $imagesData = [];

        foreach ($pagination->getItems() as $image) {
            $imagesData[] = [
                'id' => $image->getId(),
                'url' => $this->imageDirectory.$image->getName(),
                'alt' => $image->getAlt(),
            ];
        }

        return new JsonResponse([
            'images' => $imagesData,
            'nextPage' => $page < $pagination->getPageCount() ? $page + 1 : null,
        ]);
    }

    // Private methods

    /**
     * Retrieves query results based on optional search string and state.
     *
     * @param string|null $search the search string to be used for filtering the results
     * @param int|null    $state  the state to be used for filtering the results
     *
     * @return Page[] an array of query results
     */
    private function getQueryResults(?string $search, ?int $state): array
    {
        if (!empty($search)) {
            return $this->pageRepository->findByCriteria($search, 'DESC');
        }

        if (!empty($state)) {
            return $this->pageRepository->findByState($state, 'DESC');
        }

        return $this->pageRepository->findAllOrderBy('DESC');
    }
}
