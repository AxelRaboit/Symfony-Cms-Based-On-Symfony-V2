<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Page;
use App\Entity\PageType;
use App\Entity\Website;
use App\Manager\DataEnumManager;
use App\Repository\MenuItemRepository;
use App\Repository\PageRepository;
use App\Repository\PageTypeRepository;
use App\Service\Page\PageService;
use App\Service\Website\WebsiteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private ?Request $request;

    public function __construct(
        private readonly DataEnumManager $dataEnumManager,
        private readonly MenuItemRepository $menuItemRepository,
        private readonly PageRepository $pageRepository,
        private readonly RequestStack $requestStack,
        private readonly WebsiteService $websiteService,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly PageService $pageService,
        private readonly PageTypeRepository $pageTypeRepository,
        private readonly string $appEnv,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getMenuItems', [$this, 'getMenuItemsFunction']),
            new TwigFunction('getDataEnumValue', [$this, 'getDataEnumValueFunction']),
            new TwigFunction('getAllPages', [$this, 'getAllPagesFunction']),
            new TwigFunction('findPagesBySlug', [$this, 'findPagesBySlugFunction']),
            new TwigFunction('findPagesByCategory', [$this, 'findPagesByCategoryFunction']),
            new TwigFunction('urlParser', [$this, 'urlParserFunction']),
            new TwigFunction('getCanonicalUrlWithLink', [$this, 'getCanonicalUrlWithLinkFunction']),
            new TwigFunction('getCanonicalUrl', [$this, 'getCanonicalUrlFunction']),
            new TwigFunction('getWebsite', [$this, 'getWebsiteFunction']),
            new TwigFunction('getUrlAbsoluteFinal', [$this, 'getUrlAbsoluteFinalFunction']),
            new TwigFunction('fileExists', [$this, 'fileExistsFunction']),
            new TwigFunction('returnReferer', [$this, 'returnRefererFunction']),
            new TwigFunction('getCurrentHour', [$this, 'getCurrentHourFunction']),
            new TwigFunction('getUrlRelativeFinal', [$this, 'getUrlRelativeFinalFunction']),
            new TwigFunction('getPageTypes', [$this, 'getPageTypesFunction']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('htmlEntityDecodeAndTruncate', [$this, 'htmlEntityDecodeAndTruncateFilter']),
            new TwigFilter('truncate', [$this, 'truncateFilter']),
            new TwigFilter('applyMd5', [$this, 'applyMd5Filter']),
        ];
    }

    public function returnRefererFunction(
        string $urlPath
    ): string {
        $requestStackRequest = $this->requestStack->getCurrentRequest();
        if (null !== $requestStackRequest) {
            $currentUrl = $requestStackRequest->getUri();
            $referer = $requestStackRequest->headers->get('referer');

            if (null === $referer || $referer === $currentUrl) {
                return $this->urlGenerator->generate($urlPath);
            }

            return $referer;
        }

        return '';
    }

    public function getPageTypesFunction(): array
    {
        return $this->pageTypeRepository->findAll();
    }

    /**
     * @throws \Exception
     */
    public function getCurrentHourFunction(string $timezone): string
    {
        $timezone = new \DateTimeZone($timezone);
        $date = new \DateTime('now', $timezone);
        return $date->format('H:i:s');
    }

    public function fileExistsFunction(string $path): bool
    {
        return file_exists($path);
    }

    public function applyMd5Filter(string $string): string
    {
        return md5($string);
    }

    public function htmlEntityDecodeAndTruncateFilter(
        ?string $string,
        int $charactersLimit = null,
        bool $decodeHtmlEntity = true
    ): ?string {
        /* Html entity decode */
        if (true === $decodeHtmlEntity && null !== $string) {
            $string = html_entity_decode($string);
        }

        /* Truncate */
        if (null !== $charactersLimit) {
            $string = strip_tags((string) $string);

            if (mb_strlen($string) > $charactersLimit) {
                return mb_substr($string, 0, $charactersLimit).'...';
            }
        }

        return $string;
    }

    public function truncateFilter(
        ?string $string,
        int $charactersLimit = null,
    ): ?string {
        /* Truncate */
        if (null !== $charactersLimit && '' !== $charactersLimit) {
            if (mb_strlen($string) > $charactersLimit) {
                return mb_substr($string, 0, $charactersLimit).'...';
            }
        }

        return $string;
    }

    /**
     * @throws \Exception
     */
    public function getWebsiteFunction(): Website
    {
        return $this->websiteService->getCurrentWebsite($this->request->getHost());
    }

    /**
     * @throws \Exception
     */
    public function getMenuItemsFunction(int $devKey): array
    {
        return $this->menuItemRepository->getMenuItemsSortedByWeight(
            $this->dataEnumManager->getDataEnumValue($devKey),
        );
    }

    /**
     * @throws \Exception
     */
    public function getDataEnumValueFunction(int $devKey): string|int|bool
    {
        return $this->dataEnumManager->getDataEnumValue($devKey);
    }

    public function getAllPagesFunction(): array
    {
        return $this->pageRepository->findAll();
    }

    /**
     * @param Page[] $pages
     */
    private function getPageElementsFormated($pages): array
    {
        $pageFormated = [];

        foreach ($pages as $page) {
            $pageFormated[] = $this->pageService->getPageElements($page, $this->request);
        }

        return $pageFormated;
    }

    public function findPagesBySlugFunction(array $slugs): array
    {
        $pages = $this->pageRepository->findBy(['slug' => $slugs]);

        if (\count($slugs) !== \count($pages)) {
            throw new \Exception('At least one page is missing, please check the slug that you\'ve provided');
        }

        return $this->getPageElementsFormated($pages);
    }

    public function findPagesByCategoryFunction(string $category): array
    {
        $pages = $this->pageRepository->findBy(['category' => $category]);

        return $this->getPageElementsFormated($pages);
    }

    /* THIS FUNCTION IS NOT USED */
    public function urlParserFunction(string $content): string
    {
        $pattern = "/<a\\s+(?:[^>]*?\\s+)?href=([\"'])(.*?)\\1/";

        preg_match_all($pattern, $content, $matches);

        if (empty($matches[0])) {
            return $content;
        }

        $links = $matches[0];
        $urls = $matches[2];

        foreach ($urls as $index => $url) {
            $transformedUrl = $this->transformUrl($url);
            $content = str_replace($links[$index], '<a href="'.$transformedUrl.'"', $content);
        }

        return $content;
    }

    /* THIS FUNCTION IS NOT USED */
    private function transformUrl(string $url): string
    {
        $locale = $this->request->getLocale();

        $parsedUrl = parse_url($url);
        if (empty($parsedUrl['scheme'])) {
            $url = $this->request->getBaseUrl().'/'.$locale.$url;
        }

        return $url;
    }

    /**
     * @throws \Exception
     */
    public function getCanonicalUrlWithLinkFunction(Page $page): object
    {
        // Get the hostname
        $hostname = $this->request->getHost();

        // Get the current website
        $website = $this->websiteService->getCurrentWebsite($hostname);

        // If homepage
        if ('/' === $page->getSlug()) {
            $url = $website->getDomain().'/';
            // If canonical url is set
        } elseif (null !== $page->getCanonicalUrl() && '' !== $page->getCanonicalUrl()) {
            $url = $website->getDomain().'/'.$page->getCanonicalUrl();
            // If slug is set (The slug is mandatory)
        } else {
            $url = $website->getDomain().'/'.$page->getSlug();
        }

        // Prevent double slash on the url
        $url = str_replace('//', '/', $url);

        // Add protocol
        $url = $website->getProtocol().$url;

        // Return the absolute url
        return new \Twig\Markup(sprintf('<link rel="canonical" href="%s">', $url), 'UTF-8');
    }

    public function getCanonicalUrlFunction(Page $page): ?string
    {
        return $page->getWebsite()->getProtocol().$page->getWebsite()->getDomain().'/'.$page->getCanonicalUrl();
    }

    public function getUrlAbsoluteFinalFunction(Page $page): ?string
    {
        $baseUrl = $this->request->getSchemeAndHttpHost();

        $url = $baseUrl. '/' . $page->getSlug();
        /* This is used to prevent double slash on the homepage */
        $url = str_replace('//', '/', $url);

        return $url;
    }

    public function getUrlRelativeFinalFunction(Page $page): ?string
    {
        return '/' . $page->getSlug();
    }
}