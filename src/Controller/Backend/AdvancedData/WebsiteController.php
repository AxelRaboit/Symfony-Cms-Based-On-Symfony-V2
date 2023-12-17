<?php

namespace App\Controller\Backend\AdvancedData;

use App\Entity\Website;
use App\Form\backend\admin\dashboard\advancedData\website\WebsiteCreateType;
use App\Form\backend\admin\dashboard\advancedData\website\WebsiteEditType;
use App\Manager\Backend\AdvancedData\Website\WebsiteManager;
use App\Repository\WebsiteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebsiteController extends AbstractController
{
    public function __construct(private readonly WebsiteRepository $websiteRepository)
    {
    }

    /**
     * Perform a search on the website list and return the paginated results.
     *
     * @param Request            $request   the request object containing the search query parameter
     * @param PaginatorInterface $paginator the paginator object used to paginate the query results
     *
     * @return Response the response object containing the rendered website list template with the paginated results
     */
    #[Route('/backend/admin/advanced-data/website/list', name: 'app_backend_advanced_data_website_list')]
    public function websiteList(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var string|null $search */
        $search = $request->query->get('search');

        $query = $this->getQueryResults($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1) // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/advancedData/website/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Create a new website for the backend.
     *
     * @param Request        $request        the HTTP request object
     * @param WebsiteManager $websiteManager the website manager
     *
     * @return Response the response object
     */
    #[Route('/backend/admin/advanced-data/website/create', name: 'app_backend_advanced_data_website_create', methods: ['GET', 'POST'])]
    public function websiteCreate(Request $request, WebsiteManager $websiteManager): Response
    {
        $website = new Website();
        $form = $this->createForm(WebsiteCreateType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $websiteManager->websiteCreate($website);

            $websiteName = $website->getName();

            $this->addFlash('success', "Le site $websiteName a été créé avec succès.");

            return $this->redirectToRoute('app_backend_advanced_data_website_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/website/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/advanced-data/website/{id}/edit', name: 'app_backend_advanced_data_website_edit', methods: ['GET', 'POST'])]
    public function websiteEdit(Website $website, Request $request, WebsiteManager $websiteManager): Response
    {
        $form = $this->createForm(WebsiteEditType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $websiteManager->websiteEdit($website);

            $websiteName = $website->getName();
            $this->addFlash('success', "Le site $websiteName a été modifié avec succès.");

            return $this->redirectToRoute('app_backend_advanced_data_website_list');
        }

        return $this->render('backend/admin/dashboard/advancedData/website/edit.html.twig', [
            'form' => $form->createView(),
            'website' => $website,
        ]);
    }

    #[Route('/backend/admin/advanced-data/website/{id}/delete', name: 'app_backend_advanced_data_website_delete')]
    public function websiteDelete(Website $website, WebsiteManager $websiteManager): Response
    {
        $websiteManager->websiteDelete($website);

        $websiteName = $website->getName();

        $this->addFlash('success', "Le site $websiteName a été supprimé avec succès.");

        return $this->redirectToRoute('app_backend_advanced_data_website_list');
    }

    #[Route('/backend/admin/advanced-data/website/ajax-search', name: 'app_backend_advanced_data_website_ajax_search')]
    public function ajaxSearch(Request $request, WebsiteRepository $websiteRepository): JsonResponse
    {
        /** @var string $searchTerm */
        $searchTerm = $request->query->get('term');

        $websites = $websiteRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($websites as $website) {
            $responseData[] = [
                'id' => $website->getId(),
                'label' => $website->getName(),
            ];
        }

        return new JsonResponse($responseData);
    }

    // Private methods

    /**
     * Get the query results.
     *
     * @param string|null $search the search criteria (optional)
     *
     * @return Website[] the query results
     */
    private function getQueryResults(?string $search): array
    {
        if (!empty($search)) {
            return $this->websiteRepository->findByCriteria($search, 'DESC');
        }

        return $this->websiteRepository->findAllOrderBy('DESC');
    }
}
