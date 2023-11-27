<?php

namespace App\Controller;

use App\Entity\UserBackend;
use App\Form\RegistrationFormType;
use App\Manager\Frontend\UserBackend\UserBackendManager;
use App\Repository\UserBackendRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationBackendController extends AbstractController
{
    #[Route('/backend/inscription', name: 'app_backend_register')]
    public function register(
        Request $request,
        UserBackendRepository $userBackendRepository,
        UserBackendManager $userBackendManager,
    ): Response
    {
        $userBackend = new UserBackend();
        $form = $this->createForm(RegistrationFormType::class, $userBackend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $userBackendRepository->findOneBy(['email' => $userBackend->getEmail()]);
            if ($existingUser) {
                $this->addFlash('danger', 'Un utilisateur avec cette adresse email existe déjà.');
                return $this->redirectToRoute('app_backend_register');
            }

            $password = $form->get('plainPassword')->getData();

            $userBackendManager->userBackendCreate($password, $userBackend);

            // do anything else you need here, like send an email

            $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_backend_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}