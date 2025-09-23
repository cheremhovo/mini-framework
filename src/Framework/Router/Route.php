<?php

namespace Cheremhovo1990\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;

class Route implements RouteInterface
{
    /**
     * Название ссылки
     */
    private string $name;

    private string $pattern;

    /**
     * Обработчик ссылки
     */
    private $controller;

    private array $methods;

    public function __construct(string $name, string $pattern, $controller, array $methods)
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->methods = $methods;
    }

    public function match(ServerRequestInterface $request)
    {
        if ($this->methods && !in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }

        $path = $request->getUri()->getPath();

        $pattern = '~^' . $this->pattern . '$~';
        if (!preg_match($pattern, $path)) {
            return null;
        }

        return $this->controller;
    }

    public function generate(string $name, array $params = []): ?string
    {

    }
}