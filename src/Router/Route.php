<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router;

use Cheremhovo1990\Framework\Helper\StringHelper;
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

    private array $options;

    private array $keys = [];

    public function __construct(string $name, string $pattern, $controller, array $methods = [], array $options = [])
    {
        $this->name = $name;
        $this->pattern = '/' . StringHelper::replaceStart('/', '', $pattern);
        $this->controller = $controller;
        $this->methods = $methods;
        $this->options = $options;
    }

    public function match(ServerRequestInterface $request): null|ResultRoute
    {
        if ($this->methods && !in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }

        $path = $request->getUri()->getPath();
        $pattern = $this->getPattern();
        if (!preg_match($pattern, $path, $matches)) {
            return null;
        }
        $arguments = [];
        foreach ($this->keys as $key) {
            $arguments[$key] = $matches[$key];
        }
        $result = new ResultRoute($this->controller, $arguments, $this->options);
        return $result;
    }

    protected function getPattern(): string
    {
        $pattern = preg_replace_callback("~{(?<param>.+?)}~", function ($matches) {
            $subpattern = $matches['param'];
            if (strpos($subpattern, ':')) {
                [$key, $expression] = explode(':', $subpattern);
            } else {
                $key = $subpattern;
                $requirements = $this->options['requirements'] ?? [];
                $expression = $requirements[$key] ?? null;
                if ($expression === null) {
                    throw new RouteException(sprintf('The expression not found at subpattern %s', $key));
                }
            }
            if (!in_array($expression, ['\d+', '\w+'])) {
                throw new RouteException(sprintf('The expression "%s" not matched', $expression));
            }
            $this->keys[] = $key;
            $result = sprintf('(?<%s>%s?)', $key, $expression);
            return $result;
        }, $this->pattern);
        $pattern = '~^' . $pattern . '$~';
        return $pattern;
    }

    public function generate(string $name, array $params = []): null|string
    {
        $requirements = $this->options['requirements'] ?? [];
        if ($this->name === $name) {
            $pattern = preg_replace_callback("~{(?<param>.+?)}~", function ($matches) use (&$params, &$requirements) {
                $subpattern = $matches['param'];
                if (strpos($subpattern, ':')) {
                    [$key] = explode(':', $subpattern);
                } else {
                    $key = $subpattern;
                }
                if (!key_exists($key, $params)) {
                    throw new RouteException(sprintf('The route params "%s" not found.', $key));
                }
                $requirements[$key] = null;
                $result = sprintf('%s', $params[$key]);
                $params[$key] = null;
                return $result;
            }, $this->pattern);
            $requirements = array_keys(array_filter($requirements));
            $params = array_filter($params);
            foreach ($requirements as $key) {
                if (!key_exists($key, $params)) {
                    throw new RouteException(sprintf('The requirement params "%s" not found.', $key));
                }
            }

            if (empty($params)) {
                return $pattern;
            }

            return sprintf('%s?%s', $pattern, http_build_query($params));
        }
        return null;
    }
}