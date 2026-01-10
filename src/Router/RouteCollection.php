<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router;

class RouteCollection
{
    /** @var array|RouteInterface[] */
    private array $routes = [];

    public function addRoute(RouteInterface $route)
    {
        $this->routes[] = $route;
    }

    public function any(string $name, string $pattern, $controller, array $methods = [], array $options = [])
    {
        $this->addRoute(new Route($name, $pattern, $controller, $methods, $options));
    }

    public function get(string $name, string $pattern, $controller, array $options =[])
    {
        $this->addRoute(new Route($name, $pattern, $controller, ['GET'], $options));
    }

    public function post(string $name, string $pattern, $controller, array $options =[])
    {
        $this->addRoute(new Route($name, $pattern, $controller, ['POST'], $options));
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}