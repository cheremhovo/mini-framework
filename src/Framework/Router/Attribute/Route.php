<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router\Attribute;

#[\Attribute]
class Route
{
    public string $pattern;
    public string $name;
    public array $methods;
    public array $options;

    public function __construct(string $name, string $pattern, array $methods = [], array $options = [])
    {
        $this->pattern = $pattern;
        $this->name = $name;
        $this->methods = $methods;
        $this->options = $options;
    }
}