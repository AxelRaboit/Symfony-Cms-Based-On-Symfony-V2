<?php

namespace App\Service\Page;

use App\Entity\Page;
use App\Enum\DataEnum;
use App\Enum\UtilsEnum;
use App\Repository\PageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\DomCrawler\Crawler;

class PageService
{
    public function __construct(private readonly PageRepository $pageRepository)
    {
    }

    /**
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function page404NotFound(): Page
    {
        $page = $this->pageRepository->getPageFromDataDevKey(DataEnum::DATA_PAGE_ERROR_404_DEV_KEY);

        if (null === $page) {
            throw new \Exception('The page 404 not found is not defined.');
        }

        return $page;
    }

    public function getPageByDevCodeRouteName(string $devCodeRouteName): ?Page
    {
        return $this->pageRepository->findOneBy(['devCodeRouteName' => $devCodeRouteName]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getPageElements(Page $page): array
    {
        $template = $page->getTemplate() ?: UtilsEnum::PAGE_DEFAULT_TEMPLATE;

        $pageElements = [];
        $pageElementsFormatted = [];

        if (null !== $page->getBanner()) {
            $pageElements['bannerName'] = $page->getBanner()->getName();
        }
        if (null !== $page->getBanner()) {
            $pageElements['bannerAlt'] = $page->getBanner()->getAlt();
        }
        $pageElements['metaDescription'] = $page->getMetaDescription();
        $pageElements['bannerTitle'] = $page->getBannerTitle();
        $pageElements['ctaTitle'] = $page->getCtaTitle();
        $pageElements['name'] = $page->getName();
        $pageElements['title'] = $page->getTitle();
        $pageElements['description'] = $page->getDescription();
        $pageElements['contentPrimary'] = $page->getContentPrimary();
        $pageElements['contentSecondary'] = $page->getContentSecondary();
        $pageElements['contentTertiary'] = $page->getContentTertiary();
        $pageElements['contentQuaternary'] = $page->getContentQuaternary();

        foreach ($pageElements as $key => $element) {
            if (null !== $element && '' !== $element && !is_numeric($element)) {
                $pageElementsFormatted[$key] = $this->parseRelativeUrlWithLocale($element);
            } else {
                $pageElementsFormatted[$key] = $element;
            }
        }

        return [
            'page' => $page,
            'pageElements' => $pageElementsFormatted,
            'template' => $template,
        ];
    }

    private function parseRelativeUrlWithLocale(string $content): string
    {
        $crawler = new Crawler($content);

        $relativeLinks = $crawler->filterXPath('//a[starts-with(@href, "/")]')->each(function (Crawler $node) {
            return [
                '_text' => $node->text(),
                'href' => $node->attr('href'),
                'class' => $node->attr('class'),
                'element' => $node->outerHtml(),
            ];
        });

        if (!empty($relativeLinks)) {
            foreach ($relativeLinks as $urlParts) {
                $relativeUrl = $urlParts['href'];
                $classes = $urlParts['class'];
                $oldElement = $urlParts['element'];

                preg_match('#^/([a-z]{2})/#', $relativeUrl, $matches);

                if (empty($matches)) {
                    if ('' !== $classes) {
                        $newElement = '<a href="/'.$relativeUrl.'" class="'.$classes.'">'.$urlParts['_text'].'</a>';
                    } else {
                        $newElement = '<a href="/'.$relativeUrl.'">'.$urlParts['_text'].'</a>';
                    }

                    $content = str_replace($oldElement, $newElement, $content);
                }
            }
        }

        return $content;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getChildrenFromPage(Page $page): array
    {
        $children = $this->pageRepository->getChildren($page);

        $childrenFormatted = [];

        foreach ($children as $child) {
            $childrenFormatted[] = $this->getPageElements($child);
        }

        return $childrenFormatted;
    }

    public function isSlugExist(string $slug): bool
    {
        $page = $this->pageRepository->findOneBy(['slug' => $slug]);

        if (null === $page) {
            return false;
        }

        return true;
    }

    public function isDevKeyUsed(int $devKey): bool
    {
        $page = $this->pageRepository->findOneBy(['devKey' => $devKey]);

        if (null === $page) {
            return false;
        }

        return true;
    }
}
