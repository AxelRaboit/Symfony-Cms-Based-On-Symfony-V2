<?php

namespace App\Service\Utils;

use Symfony\Component\Routing\RouterInterface;

class RouteService
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {}

    public function getRoutes(): array
    {
        $routes = [];
        foreach ($this->router->getRouteCollection()->all() as $routeName => $route) {
            $routes[$routeName] = $route;
        }

        return $routes;
    }
}