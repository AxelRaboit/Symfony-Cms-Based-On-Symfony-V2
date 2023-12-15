<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityBackendController extends AbstractController
{
    #[Route(path: '/backend/connexion', name: 'app_backend_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_backend_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Custom error message
        $badCredentials = null;
        if ($error instanceof BadCredentialsException) {
            $badCredentials = 'Mauvais identifiants de connexion';
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/backend/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'badCredentials' => $badCredentials,
        ]);
    }

    #[Route(path: '/backend/logout', name: 'app_backend_logout', priority: 100)]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
