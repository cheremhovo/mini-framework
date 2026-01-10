<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router\Attribute;

#[\Attribute]
class RouteGroup
{
    public string $name;
    public string $pattern;

    public function __construct(
        string $name,
        string $pattern = '',
    )
    {
        $this->name = $name;
        $this->pattern = $pattern;
    }
}