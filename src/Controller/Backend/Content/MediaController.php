<?php

namespace App\Controller\Backend\Content;

use App\Entity\Image;
use App\Form\backend\admin\dashboard\content\media\MediaImageCreateType;
use App\Manager\Backend\Content\Media\MediaManager;
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
    public function mediaList(Request $request, MediaManager $mediaManager): Response
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

        return $this->render('backend/admin/dashboard/content/media/list.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}