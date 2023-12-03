<?php

namespace App\Service\Dashboard;

use App\Enum\PageStateEnum;
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
            'rgb(200, 200, 200)',
            'rgb(210, 210, 210)',
            'rgb(220, 220, 220)',
            'rgb(225, 225, 225)',
            'rgb(230, 230, 230)',
            'rgb(235, 235, 235)',
            'rgb(240, 240, 240)',
            'rgb(245, 245, 245)'
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
            'rgb(200, 200, 200)',
            'rgb(210, 210, 210)',
            'rgb(220, 220, 220)',
            'rgb(225, 225, 225)',
            'rgb(230, 230, 230)',
            'rgb(235, 235, 235)',
            'rgb(240, 240, 240)',
            'rgb(245, 245, 245)'
        ];


        $chartPublishedPagesByPageTypes = $this->chartBuilder->createChart(Chart::TYPE_PIE);

        $pageTypes = $this->pageTypeRepository->findAll();

        $dataPublishedPagesByPageTypes = [];
        $labelsPublishedPagesByPageTypes = [];

        foreach ($pageTypes as $pageType) {
            $publishedPages = $this->pageRepository->count(['pageType' => $pageType, 'state' => PageStateEnum::PUBLISHED]);

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
