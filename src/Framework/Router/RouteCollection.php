<?php

namespace Cheremhovo1990\Framework\Router;

class RouteCollection
{
    /** @var array|RouteInterface[] */
    private array $routes = [];

    public function addRoute(RouteInterface $route)
    {
        $this->routes[] = $route;
    }

    public function any(string $name, $pattern, $controller, array $methods)
    {
        $this->addRoute(new Route($name, $pattern, $controller, $methods));
    }

    public function get(string $name, $pattern, $controller)
    {
        $this->addRoute(new Route($name, $pattern, $controller, ['GET']));
    }

    public function post(string $name, $pattern, $controller)
    {
        $this->addRoute(new Route($name, $pattern, $controller, ['POST']));
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}