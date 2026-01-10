<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Helper;

use Cheremhovo1990\Framework\Router\Router;

class UrlHelper
{
    protected static Router|null $router = null;

    public static function setRouter(Router $router)
    {
        static::$router = $router;
    }

    public static function generate(string $name, array $params = []): string
    {
        $url = static::$router->generate($name, $params);
        if ($url !== null) {
            return $url;
        }
        return '';
    }
}