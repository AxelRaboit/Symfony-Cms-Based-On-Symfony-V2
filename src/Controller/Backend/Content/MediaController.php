<?php

namespace App\Controller\Backend\Content;

use App\Entity\Image;
use App\Form\backend\admin\dashboard\content\media\MediaImageCreateType;
use App\Manager\Backend\Content\Media\MediaManager;
use App\Repository\ImageRepository;
use App\Service\Media\MediaService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/backend/admin/content/media/list', name: 'app_backend_content_media_list', methods: ['GET', 'POST'])]
    public function mediaList(Request $request, MediaService $mediaService, ImageRepository $imageRepository, PaginatorInterface $paginator): Response
    {
        $image = new Image();
        $form = $this->createForm(MediaImageCreateType::class, $image);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $imageRepository->findAll(),
            $request->query->getInt('page', 1),
            10 // Override default limit per page
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaService->prepareImageForUpload($image);

            $imageName = $image->getName();

            $this->addFlash('success', "L'image {$imageName} a été créé avec succès.");

            return $this->redirectToRoute('app_backend_content_media_list');
        }

        return $this->render('backend/admin/dashboard/content/media/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

}