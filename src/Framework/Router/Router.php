<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    protected RouteCollection $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request): null|ResultRoute
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($controller = $route->match($request)) {
                return $controller;
            }
        }
        return null;
    }

    public function generate(string $name, array $params): string|null
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($call = $route->generate($name, $params)) {
                return $call;
            }
        }
        return null;
    }
}