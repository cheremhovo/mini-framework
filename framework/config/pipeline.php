<?php

/** @var \Pipeline\Pipeline $pipeline */

use App\Middleware\ProfileMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

$pipeline->pipe(ProfileMiddleware::class);
$pipeline->pipe(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
    $response = $handler->handle($request);
    return $response->withHeader('X-Developer-email', 'cheremhovo1990@yandex.ru');
});