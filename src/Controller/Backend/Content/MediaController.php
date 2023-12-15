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
     * @param Request $request The current request.
     * @param MediaService $mediaService The service responsible for media operations.
     * @param ImageRepository $imageRepository The repository for image entities.
     * @param PaginatorInterface $paginator The paginator interface for pagination.
     * @return Response The response object.
     * @throws Exception If an error occurs.
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
            if ($mediaService->prepareMediaForUpload($image)) {
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
     * Edits a media image.
     *
     * @param Image $image The image to be edited.
     * @param Request $request The HTTP request object.
     * @param MediaService $mediaService The media service.
     *
     * @return Response The HTTP response object.
     * @throws Exception If an error occurs.
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

    /**
     * Deletes a page from the backend admin content media.
     *
     * @param Image $image The image object to be deleted.
     * @param MediaManager $mediaManager The media manager object used for deleting the image.
     * @return Response Returns a Response object.
     */
    #[Route('/backend/admin/content/media/{id}/delete', name: 'app_backend_content_media_delete')]
    public function pageDelete(Image $image, MediaManager $mediaManager): Response
    {
        $mediaManager->mediaImageDelete($image);

        $imageName = $image->getName();

        $this->addFlash('success', "L'image $imageName a été supprimée avec succès.");

        return $this->redirectToRoute('app_backend_content_media_list');
    }
}
