<?php

namespace App\Controller\Backend\Content;

use App\Entity\Image;
use App\Form\backend\admin\dashboard\content\media\MediaImageCreateType;
use App\Form\backend\admin\dashboard\content\media\MediaImageEditType;
use App\Manager\Backend\Content\Media\MediaManager;
use App\Repository\ImageRepository;
use App\Service\Media\MediaService;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @throws Exception
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
            if($mediaService->prepareMediaForUpload($image)) {
                $this->addFlash('success', "Le media {$image->getName()} a été importé avec succès.");

                return $this->redirectToRoute('app_backend_content_media_list');
            } else {
                $this->addFlash('danger', "Le media {$image->getName()} n'a pas pu être importé.");
            }

            return $this->redirectToRoute('app_backend_content_media_list');
        }

        return $this->render('backend/admin/dashboard/content/media/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/backend/admin/content/media/{id}/edit', name: 'app_backend_content_media_edit', methods: ['GET', 'POST'])]
    public function mediaEdit(Image $image, Request $request, MediaService $mediaService): Response
    {
        $form = $this->createForm(MediaImageEditType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaService->prepareMediaForUpload($image);

            $imageName = $image->getName();
            $this->addFlash('success', "L'image $imageName a été modifiée avec succès.");

            return $this->redirectToRoute('app_backend_content_media_list');
        }

        return $this->render('backend/admin/dashboard/content/media/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/admin/content/media/{id}/delete', name: 'app_backend_content_media_delete')]
    public function pageDelete(Image $image, MediaManager $mediaManager): Response
    {
        $mediaManager->mediaImageDelete($image);

        $imageName = $image->getName();

        $this->addFlash('success', "L'image $imageName a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_media_list');
    }

}