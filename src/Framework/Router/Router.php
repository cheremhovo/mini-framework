<?php

namespace Cheremhovo1990\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;

class Router
{
    protected RouteCollection $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request)
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($controller = $route->match($request)) {
                return $controller;
            }
        }
        return null;
    }

    public function generate()
    {

    }
}