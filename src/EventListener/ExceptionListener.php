<?php

namespace App\EventListener;

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Service\PageService;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ExceptionListener
{
    public function __construct(private readonly Environment $twig, private readonly PageService $pageService)
    {
    }

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
            $page = $this->pageService->page404NotFound();
            $elements = $this->pageService->getPageElements($page);
            $elements['exception'] = $exception;
            $content = $this->twig->render($elements['template'], $elements);
            $response = new Response($content, Response::HTTP_NOT_FOUND);
        } else {
            $content = $this->twig->render('page/exceptions/page-exception.html.twig', ['exception' => $exception]);
            $response = new Response($content, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}