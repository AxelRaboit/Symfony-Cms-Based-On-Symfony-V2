<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class BackendRedirectListener
{
    public function __construct(private Security $security, private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ('/backend' == $request->getPathInfo()) {
            if ($this->security->isGranted('ROLE_BACKEND')) {
                $response = new RedirectResponse($this->urlGenerator->generate('app_backend_dashboard'));
            } else {
                $response = new RedirectResponse($this->urlGenerator->generate('app_backend_login'));
            }
            $event->setResponse($response);
        }
    }
}
