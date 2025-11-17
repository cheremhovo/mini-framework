<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Pipeline;

use Cheremhovo1990\Framework\App;
use Cheremhovo1990\Framework\Http\RequestHandlerWrapper;
use Cheremhovo1990\Framework\Resolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Next  implements RequestHandlerInterface
{
    protected \SplQueue $queue;
    private RequestHandlerInterface $controller;

    public function __construct(
        \SplQueue $queue,
        RequestHandlerInterface $controller
    )
    {
        $this->queue = $queue;
        $this->controller = $controller;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($this->queue->isEmpty()) {
            return $this->controller->handle($request);
        }
        $middleware = $this->getMiddleware();
        return $middleware->process($request, $this);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this)($request);
    }


    protected function getMiddleware(): MiddlewareInterface
    {
        $middleware = $this->queue->dequeue();
        return App::get($middleware);
    }
}