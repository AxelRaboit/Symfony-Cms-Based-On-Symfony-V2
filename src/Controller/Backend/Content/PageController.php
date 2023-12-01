<?php

namespace App\Controller\Backend\Content;

use App\Entity\Page;
use App\Form\backend\admin\dashboard\content\page\PageCreateType;
use App\Form\backend\admin\dashboard\content\page\PageEditType;
use App\Manager\Backend\Content\Page\PageManager;
use App\Repository\ImageRepository;
use App\Repository\PageRepository;
use App\Service\Utils\StringUtilsService;
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
    public function __construct(private readonly string $imageDirectory)
    {
    }

    #[Route('/backend/admin/content/page/list', name: 'app_backend_content_page_list')]
    public function pageList(
        PageRepository     $pageRepository,
        Request            $request,
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

        return $this->render('backend/admin/dashboard/content/page/list/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
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

            $bannerImageId = (int)$request->request->get('selected_banner_image_id');
            $thumbnailImageId = (int)$request->request->get('selected_thumbnail_image_id');
            $pageManager->pageCreate($page, $bannerImageId, $thumbnailImageId);

            $pageName = $page->getName();
            $this->addFlash('success', "La page {$pageName} a été créé avec succès.");

            return $this->redirectToRoute('app_backend_content_page_list');
        }

        $currentPage = $request->query->getInt('page', 1);
        $queryBuilder = $imageRepository->createQueryBuilder('i')->orderBy('i.id', 'ASC');
        $pagination = $paginator->paginate($queryBuilder, $currentPage, 18);

        return $this->render('backend/admin/dashboard/content/page/create/create.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/backend/admin/content/page/{id}/edit', name: 'app_backend_content_page_edit', methods: ['GET', 'POST'])]
    public function dataEnumEdit(Page $page, Request $request, PageManager $pageManager, ImageRepository $imageRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(PageEditType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bannerImageId = (int)$request->request->get('selected_banner_image_id');
            $thumbnailImageId = (int)$request->request->get('selected_thumbnail_image_id');

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
            'page' => $page
        ]);
    }

    #[Route('/backend/admin/content/page/{id}/delete', name: 'app_backend_content_page_delete')]
    public function pageDelete(Page $page, PageManager $pageManager): Response
    {
        $pageManager->pageDelete($page);

        $pageName = $page->getName();

        $this->addFlash('success', "La paeg {$pageName} a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_page_list');
    }

    #[Route('/backend/admin/content/page/ajax-search', name: 'app_backend_content_page_ajax_search')]
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
                'url' => $this->imageDirectory . $image->getName(),
                'alt' => $image->getAlt()
            ];
        }

        return new JsonResponse([
            'images' => $imagesData,
            'nextPage' => $page < $pagination->getPageCount() ? $page + 1 : null
        ]);
    }

}