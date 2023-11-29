<?php

namespace App\Controller\Backend\Content;

use App\Entity\Image;
use App\Form\backend\admin\dashboard\content\media\MediaImageCreateType;
use App\Manager\Backend\Content\Media\MediaManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route('/backend/admin/content/media/list', name: 'app_backend_content_media_list')]
    public function mediaList()
    {
        return $this->render('backend/admin/dashboard/content/media/list.html.twig', []);
    }

    /**
     * @throws \Exception
     */
    #[Route('/backend/admin/content/media/image/create', name: 'app_backend_content_media_image_create', methods: ['GET', 'POST'])]
    public function mediaImageCreate(Request $request, MediaManager $mediaManager): Response
    {
        $image = new Image();
        $form = $this->createForm(MediaImageCreateType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaManager->mediaImageCreate($image);

            $imageName = $image->getName();

            $this->addFlash('success', "L'image {$imageName} a été créé avec succès.");

            return $this->redirectToRoute('app_backend_content_media_list');
        }

        return $this->render('backend/admin/dashboard/content/media/image/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}