<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class BackendRedirectListener
{
    public function __construct(private readonly Security $security, private readonly UrlGeneratorInterface $urlGenerator){}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->getPathInfo() == '/backend') {
            if ($this->security->isGranted('ROLE_BACKEND')) {
                $response = new RedirectResponse($this->urlGenerator->generate('app_backend_dashboard'));
            } else {
                $response = new RedirectResponse($this->urlGenerator->generate('app_backend_login'));
            }
            $event->setResponse($response);
        }
    }
}