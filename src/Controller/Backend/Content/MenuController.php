<?php

namespace App\Controller\Backend\Content;

use App\Entity\Menu;
use App\Form\backend\admin\dashboard\content\menu\edit\MenuEditType;
use App\Manager\Backend\Content\Menu\MenuManager;
use App\Repository\MenuRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/backend/admin/content/menu/list', name: 'app_backend_content_menu_list')]
    public function menuList(
        MenuRepository     $menuRepository,
        Request            $request,
        PaginatorInterface $paginator,
    ): Response
    {
        /** @var string|null $search */
        $search = $request->query->get('search');

        if (!empty($search)) {
            $query = $menuRepository->findByCriteria($search, 'DESC');
        } else {
            $query = $menuRepository->findAllOrderBy('DESC');
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            null // Override default limit per page
        );

        return $this->render('backend/admin/dashboard/content/menu/list/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws Exception
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
     * @throws Exception
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
            'menu' => $menu
        ]);
    }

    #[Route('/backend/admin/content/menu/{id}/delete', name: 'app_backend_content_menu_delete')]
    public function menuDelete(Menu $menu, MenuManager $menuManager): Response
    {
        $menuManager->menuDelete($menu);

        $menuName = $menu->getName();

        $this->addFlash('success', "Le menu $menuName a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_menu_list');
    }

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
                'label' => $menu->getName()
            ];
        }

        return new JsonResponse($responseData);
    }
}