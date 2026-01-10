<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Router;

use Psr\Http\Message\ServerRequestInterface;

interface RouteInterface
{
    public function match(ServerRequestInterface $request);

    public function generate(string $name, array $params = []): null|string;
}