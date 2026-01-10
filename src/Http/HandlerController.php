<?php

declare(strict_types=1);

namespace Cheremhovo1990\Framework\Http;

use Cheremhovo1990\Framework\App;
use Cheremhovo1990\Framework\Router\ResultRoute;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HandlerController implements RequestHandlerInterface
{
    private null|ResultRoute $target;

    public function __construct(null|ResultRoute $target)
    {
        $this->target = $target;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (is_array($this->target->controller)) {
            $class = $this->target->controller[0];
            $method = $this->target->controller[1];
            $controller = [App::getContainer()->get($class), $method];
        } else {
            $controller = App::get($this->target->controller);
        }
        $response = ($controller)($request);
        if (!$response instanceof ResponseInterface) {
            if (is_string($response)) {
                $response = new HtmlResponse($response);
            }
        }
        return $response;
    }
}