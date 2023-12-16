<?php

namespace App\Controller\Backend\Content;

use App\Entity\Menu;
use App\Form\backend\admin\dashboard\content\menu\edit\MenuEditType;
use App\Manager\Backend\Content\Menu\MenuManager;
use App\Repository\MenuRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    public function __construct(private readonly MenuRepository $menuRepository)
    {
    }

    /**
     * Retrieve a paginated list of menu items.
     *
     * @param Request            $request   the request object
     * @param PaginatorInterface $paginator the paginator object
     *
     * @return Response the response object
     *
     * @Route('/backend/admin/content/menu/list', name='app_backend_content_menu_list')
     */
    #[Route('/backend/admin/content/menu/list', name: 'app_backend_content_menu_list')]
    public function menuList(
        Request $request,
        PaginatorInterface $paginator,
    ): Response {
        /** @var string|null $search */
        $search = $request->query->get('search');

        $query = $this->getQueryResults($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1) // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/menu/list/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Creates a new menu.
     *
     * @param Request     $request     The current request object
     * @param MenuManager $menuManager The menu manager service
     *
     * @return Response The response object
     *
     * @throws \Exception
     */
    #[Route('/backend/admin/content/menu/create', name: 'app_backend_content_menu_create', methods: ['GET', 'POST'])]
    public function menuCreate(Request $request, MenuManager $menuManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuEditType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuManager->menuCreate($menu);

            $menuName = $menu->getName();
            $this->addFlash('success', "Le menu $menuName a été créé avec succès.");

            return $this->redirectToRoute('app_backend_content_menu_list');
        }

        return $this->render('backend/admin/dashboard/content/menu/create/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a menu.
     *
     * @param Menu        $menu        The menu to be edited
     * @param Request     $request     The HTTP request
     * @param MenuManager $menuManager The menu manager
     *
     * @return Response The HTTP response
     *
     * @throws \Exception
     */
    #[Route('/backend/admin/content/menu/{id}/edit', name: 'app_backend_content_menu_edit', methods: ['GET', 'POST'])]
    public function menuEdit(Menu $menu, Request $request, MenuManager $menuManager): Response
    {
        $form = $this->createForm(MenuEditType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menuManager->menuEdit($menu);

            $menuName = $menu->getName();
            $this->addFlash('success', "Le menu $menuName a été modifiée avec succès.");

            return $this->redirectToRoute('app_backend_content_menu_list');
        }

        return $this->render('backend/admin/dashboard/content/menu/edit/edit.html.twig', [
            'form' => $form->createView(),
            'menu' => $menu,
        ]);
    }

    /**
     * Deletes a menu.
     *
     * @param Menu        $menu        the menu to be deleted
     * @param MenuManager $menuManager the menu manager to handle the deletion
     *
     * @return Response a response object representing the redirection to the menu list page
     *
     * @throws \Exception if an error occurs during the deletion process
     */
    #[Route('/backend/admin/content/menu/{id}/delete', name: 'app_backend_content_menu_delete')]
    public function menuDelete(Menu $menu, MenuManager $menuManager): Response
    {
        $menuManager->menuDelete($menu);

        $menuName = $menu->getName();

        $this->addFlash('success', "Le menu $menuName a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_menu_list');
    }

    /**
     * Search for menus based on a given search term.
     *
     * @param Request        $request        the HTTP request object
     * @param MenuRepository $menuRepository the repository for menus
     *
     * @return JsonResponse the JSON response with the menu data
     *
     * @throws \Exception
     */
    #[Route('/backend/admin/content/menu/ajax-search', name: 'app_backend_content_menu_ajax_search')]
    public function ajaxSearch(Request $request, MenuRepository $menuRepository): JsonResponse
    {
        /** @var string $searchTerm */
        $searchTerm = $request->query->get('term');

        $menus = $menuRepository->findByCriteria($searchTerm);

        $responseData = [];

        foreach ($menus as $menu) {
            $responseData[] = [
                'id' => $menu->getId(),
                'label' => $menu->getName(),
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
     * @return Menu[] the query results
     */
    private function getQueryResults(?string $search): array
    {
        if (!empty($search)) {
            return $this->menuRepository->findByCriteria($search, 'DESC');
        }

        return $this->menuRepository->findAllOrderBy('DESC');
    }
}
