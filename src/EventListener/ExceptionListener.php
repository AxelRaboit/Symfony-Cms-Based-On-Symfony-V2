<?php

namespace App\EventListener;

use App\Service\Page\PageService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class ExceptionListener
{
    public function __construct(private Environment $twig, private PageService $pageService){}

    /**
     * @throws SyntaxError
     * @throws NonUniqueResultException
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() == Response::HTTP_NOT_FOUND) {
            // CODE 404
            $page = $this->pageService->page404NotFound();
            $elements = $this->pageService->getPageElements($page);
            $elements['exception'] = $exception;
            $content = $this->twig->render($elements['template'], $elements);
            $response = new Response($content, Response::HTTP_NOT_FOUND);
        } elseif ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() == Response::HTTP_METHOD_NOT_ALLOWED) {
            // CODE 405
            $content = $this->twig->render('exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_INTERNAL_SERVER_ERROR);
        } elseif ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() == Response::HTTP_FORBIDDEN) {
            // CODE 403
            $content = $this->twig->render('exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_FORBIDDEN);
        } elseif ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
            // CODE 401
            $content = $this->twig->render('exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_UNAUTHORIZED);
        } elseif ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() == Response::HTTP_BAD_REQUEST) {
            // CODE 400
            $content = $this->twig->render('exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() == Response::HTTP_INTERNAL_SERVER_ERROR) {
            // CODE 500
            $content = $this->twig->render('exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $content = $this->twig->render('exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}