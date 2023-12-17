<?php

namespace App\Controller;

use App\Repository\PageRepository;
use App\Repository\UserBackendRepository;
use App\Service\Utils\SiteMapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteMapController extends AbstractController
{
    #[Route('/sitemaps.xml', name: 'sitemap', defaults: ['_format' => 'xml'])]
    public function index(Request $request, siteMapService $siteMapService): Response
    {
        $urls = array_merge(
            // Editorial pages
            $siteMapService->findAllEditorialPages(),
            // User backend profiles
            $siteMapService->findAllUserBackendProfiles()
        );

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'urls' => $urls,
                'hostname' => $request->getSchemeAndHttpHost(),
            ]),
            200
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
