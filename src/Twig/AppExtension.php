<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Page;
use App\Entity\PageType;
use App\Entity\UserBackend;
use App\Entity\Website;
use App\Enum\PageStateEnum;
use App\Manager\DataEnumManager;
use App\Repository\BackendMessageRepository;
use App\Repository\MenuItemRepository;
use App\Repository\PageRepository;
use App\Repository\PageTypeRepository;
use App\Service\Page\PageService;
use App\Service\Website\WebsiteService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
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
        private readonly BackendMessageRepository $backendMessageRepository,
        /** @phpstan-ignore-next-line */
        private readonly string $appEnv,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fileExists', [$this, 'fileExistsFunction']),
            new TwigFunction('findPagesByCategory', [$this, 'findPagesByCategoryFunction']),
            new TwigFunction('findPagesBySlug', [$this, 'findPagesBySlugFunction']),
            new TwigFunction('getAllPages', [$this, 'getAllPagesFunction']),
            new TwigFunction('getSchemeAndHttpHost', [$this, 'getSchemeAndHttpHostFunction']),
            new TwigFunction('getCanonicalUrl', [$this, 'getCanonicalUrlFunction']),
            new TwigFunction('getCanonicalUrlWithLink', [$this, 'getCanonicalUrlWithLinkFunction']),
            new TwigFunction('getCurrentHour', [$this, 'getCurrentHourFunction']),
            new TwigFunction('getDataEnumValue', [$this, 'getDataEnumValueFunction']),
            new TwigFunction('getMenuItems', [$this, 'getMenuItemsFunction']),
            new TwigFunction('getPageTypes', [$this, 'getPageTypesFunction']),
            new TwigFunction('getPageUrl', [$this, 'getPageUrlFunction']),
            new TwigFunction('getUrlAbsoluteFinal', [$this, 'getUrlAbsoluteFinalFunction']),
            new TwigFunction('getUserBackendMessageCount', [$this, 'getUserBackendMessageCountFunction']),
            new TwigFunction('getUrlRelativeFinal', [$this, 'getUrlRelativeFinalFunction']),
            new TwigFunction('getWebsite', [$this, 'getWebsiteFunction']),
            new TwigFunction('isPagePublished', [$this, 'isPagePublishedFunction']),
            new TwigFunction('returnReferer', [$this, 'returnRefererFunction']),
            new TwigFunction('urlParser', [$this, 'urlParserFunction']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('htmlEntityDecodeAndTruncate', [$this, 'htmlEntityDecodeAndTruncateFilter']),
            new TwigFilter('truncate', [$this, 'truncateFilter']),
            new TwigFilter('applyMd5', [$this, 'applyMd5Filter']),
        ];
    }

    public function fileExistsFunction(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function findPagesByCategoryFunction(string $category): array
    {
        $pages = $this->pageRepository->findBy(['category' => $category]);

        return $this->getPageElementsFormatted($pages);
    }

    /**
     * @param string[] $slugs
     *
     * @return array<array<string, mixed>>
     *
     * @throws \Exception
     */
    public function findPagesBySlugFunction(array $slugs): array
    {
        $pages = $this->pageRepository->findBy(['slug' => $slugs]);

        if (\count($slugs) !== \count($pages)) {
            throw new \Exception('At least one page is missing, please check the slug that you\'ve provided');
        }

        return $this->getPageElementsFormatted($pages);
    }

    /**
     * @return Page[] $pages
     */
    public function getAllPagesFunction(): array
    {
        return $this->pageRepository->findAll();
    }

    /**
     * Retrieves the scheme and HTTP host of the current request.
     *
     * @return string|null the scheme and HTTP host if available, otherwise null
     */
    public function getSchemeAndHttpHostFunction(): ?string
    {
        return $this->request?->getSchemeAndHttpHost();
    }

    public function getCanonicalUrlFunction(Page $page): ?string
    {
        $website = $page->getWebsite();

        if (null !== $website) {
            $domain = $website->getDomain();
            $protocol = $website->getProtocol();
            $canonicalUrl = $page->getCanonicalUrl();

            return $protocol.$domain.'/'.$canonicalUrl;
        } else {
            return null;
        }
    }

    /**
     * @throws \Exception
     */
    public function getCanonicalUrlWithLinkFunction(Page $page): object
    {
        if (null === $this->request) {
            throw new \Exception('Request is null');
        }

        $website = $this->websiteService->getCurrentWebsite();

        if ($website instanceof Website) {
            $url = $website->getDomain();

            if ('/' === $page->getSlug()) {
                $url .= '/';
            } elseif (null !== $page->getCanonicalUrl() && '' !== $page->getCanonicalUrl()) {
                $url .= '/'.$page->getCanonicalUrl();
            } else {
                $url .= '/'.$page->getSlug();
            }

            $url = str_replace('//', '/', $url);
            $url = $website->getProtocol().$url;

            return new Markup(sprintf('<link rel="canonical" href="%s">', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')), 'UTF-8');
        } else {
            throw new \Exception('Website is null or not of the correct type');
        }
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

    /**
     * @throws \Exception
     */
    public function getDataEnumValueFunction(int $devKey): string|int|bool
    {
        $dataEnumValue = $this->dataEnumManager->getDataEnumValue($devKey);

        if (null === $dataEnumValue) {
            throw new \Exception('DataEnum value is null');
        }

        return $dataEnumValue;
    }

    /**
     * @return array<int|string, array<string, mixed>>
     *
     * @throws \Exception
     */
    public function getMenuItemsFunction(int $devKey): array
    {
        /** @var string|null $dataEnumValue */
        $dataEnumValue = $this->dataEnumManager->getDataEnumValue($devKey);

        if (null === $dataEnumValue) {
            throw new \Exception('DataEnum value is null');
        }

        return $this->menuItemRepository->getMenuItemsSortedByWeight($dataEnumValue);
    }

    /**
     * @return array<PageType>
     */
    public function getPageTypesFunction(): array
    {
        return $this->pageTypeRepository->findAll();
    }

    public function getPageUrlFunction(Page $page): string
    {
        $pageType = $page->getPageType();

        if (null !== $pageType) {
            $pageTypeUrlPrefix = $pageType->getUrlPrefix();

            if ('/' === $pageTypeUrlPrefix) {
                if ('/' === $page->getSlug()) {
                    return '/';
                }

                return '/'.$page->getSlug();
            }

            return $pageTypeUrlPrefix.'/'.$page->getSlug();
        }

        return '/'.$page->getSlug();
    }

    /**
     * @throws \Exception
     */
    public function getUrlAbsoluteFinalFunction(Page $page): ?string
    {
        if (null === $this->request) {
            throw new \Exception('Request is null');
        }

        $baseUrl = $this->request->getSchemeAndHttpHost();

        $pageType = $page->getPageType();

        if (null !== $pageType) {
            $pageTypeUrlPrefix = $pageType->getUrlPrefix();

            if ('/' === $pageTypeUrlPrefix) {
                if ('/' === $page->getSlug()) {
                    return $baseUrl;
                }

                return $baseUrl.'/'.$page->getSlug();
            }

            return $baseUrl.$pageTypeUrlPrefix.'/'.$page->getSlug();
        }

        return $baseUrl.'/'.$page->getSlug();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getUserBackendMessageCountFunction(UserBackend $userBackend): int
    {
        return $this->backendMessageRepository->findCountMessageNotReadByReceiver($userBackend);
    }

    public function getUrlRelativeFinalFunction(Page $page): ?string
    {
        $pageType = $page->getPageType();

        if (null !== $pageType) {
            $pageTypeUrlPrefix = $pageType->getUrlPrefix();

            if ('/' === $pageTypeUrlPrefix) {
                if ('/' === $page->getSlug()) {
                    return '/';
                }

                return '/'.$page->getSlug();
            }

            return $pageTypeUrlPrefix.'/'.$page->getSlug();
        }

        return '/'.$page->getSlug();
    }

    /**
     * @return Website|array<Website>|null
     *
     * @throws \Exception
     */
    public function getWebsiteFunction(): Website|array|null
    {
        $website = $this->websiteService->getCurrentWebsite();

        if (null === $website) {
            throw new \Exception('Website is null');
        }

        return $website;
    }

    public function isPagePublishedFunction(Page $page): bool
    {
        return PageStateEnum::PUBLISHED === $page->getState();
    }

    public function returnRefererFunction(string $urlPath, int|string $id = null): string
    {
        $requestStackRequest = $this->requestStack->getCurrentRequest();
        if (null !== $requestStackRequest) {
            $currentUrl = $requestStackRequest->getUri();
            $referer = $requestStackRequest->headers->get('referer');

            if (null === $referer || $referer === $currentUrl) {
                if (null !== $id) {
                    return $this->urlGenerator->generate($urlPath, ['id' => $id]);
                } else {
                    return $this->urlGenerator->generate($urlPath);
                }
            }

            return $referer;
        }

        return '';
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
        if (null !== $charactersLimit && null !== $string) {
            if (mb_strlen($string) > $charactersLimit) {
                return mb_substr($string, 0, $charactersLimit).'...';
            }
        }

        return $string;
    }

    /**
     * @param Page[] $pages
     *
     * @return array<array<string, mixed>>
     */
    private function getPageElementsFormatted(array $pages): array
    {
        $pageFormatted = [];

        foreach ($pages as $page) {
            $pageFormatted[] = $this->pageService->getPageElements($page);
        }

        return $pageFormatted;
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
    /**
     * @throws \Exception
     */
    private function transformUrl(string $url): string
    {
        if (null === $this->request) {
            throw new \Exception('Request is null');
        }

        $locale = $this->request->getLocale();

        $parsedUrl = parse_url($url);
        if (empty($parsedUrl['scheme'])) {
            $url = $this->request->getBaseUrl().'/'.$locale.$url;
        }

        return $url;
    }
}
