<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router;

class ResultRoute
{
    public $controller;

    public array $arguments;

    public array $options;

    public function __construct($controller, $arguments = [], $options = [])
    {
        $this->controller = $controller;
        $this->arguments = $arguments;
        $this->options = $options;
    }
}