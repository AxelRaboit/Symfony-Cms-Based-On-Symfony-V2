<?php

namespace App\Service\Dashboard;

use App\Repository\PageRepository;
use App\Repository\PageTypeRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardService
{
    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
        private readonly PageRepository        $pageRepository,
        private readonly PageTypeRepository $pageTypeRepository
    ){}

    public function createChartPageTypes(): Chart
    {
        $colors = [
            'rgb(50, 50, 50)',
            'rgb(70, 70, 70)',
            'rgb(90, 90, 90)',
            'rgb(110, 110, 110)',
            'rgb(130, 130, 130)',
            'rgb(150, 150, 150)',
            'rgb(170, 170, 170)',
            'rgb(190, 190, 190)',
        ];

        $chartPageTypes = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $pageTypes = $this->pageTypeRepository->findAll();

        $dataPageTypes = [];
        $labelsPageTypes = [];

        foreach ($pageTypes as $pageType) {
            $countPages = $this->pageRepository->count(['pageType' => $pageType]);

            if ($countPages > 0) {
                $labelsPageTypes[] = $pageType->getName();
                $dataPageTypes[] = $countPages;
            }
        }

        $chartPageTypes->setData([
            'labels' => $labelsPageTypes,
            'datasets' => [
                [
                    'label' => '',
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'data' => $dataPageTypes,
                ],
            ],
        ]);

        $chartPageTypes->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        return $chartPageTypes;
    }

    public function createChartPublishedPagesByPageTypes(): Chart
    {
        $colors = [
            'rgb(50, 50, 50)',
            'rgb(70, 70, 70)',
            'rgb(90, 90, 90)',
            'rgb(110, 110, 110)',
            'rgb(130, 130, 130)',
            'rgb(150, 150, 150)',
            'rgb(170, 170, 170)',
            'rgb(190, 190, 190)',
        ];

        $chartPublishedPagesByPageTypes = $this->chartBuilder->createChart(Chart::TYPE_PIE);

        $pageTypes = $this->pageTypeRepository->findAll();

        $dataPublishedPagesByPageTypes = [];
        $labelsPublishedPagesByPageTypes = [];

        foreach ($pageTypes as $pageType) {
            $publishedPages = $this->pageRepository->count(['pageType' => $pageType, 'isPublished' => true]);

            if ($publishedPages > 0) {
                $dataPublishedPagesByPageTypes[] = $publishedPages;
                $labelsPublishedPagesByPageTypes[] = $pageType->getName();
            }
        }

        $chartPublishedPagesByPageTypes->setData([
            'labels' => $labelsPublishedPagesByPageTypes,
            'datasets' => [
                [
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                    'data' => $dataPublishedPagesByPageTypes,
                ],
            ],
        ]);

        $chartPublishedPagesByPageTypes->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ]);

        return $chartPublishedPagesByPageTypes;
    }
}
