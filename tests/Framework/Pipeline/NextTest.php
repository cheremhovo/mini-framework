<?php

declare(strict_types=1);

namespace Framework\Pipeline;

use Cheremhovo1990\Framework\App;
use Cheremhovo1990\Framework\Container\Container;
use Cheremhovo1990\Framework\Http\CallableMiddlewareWrapper;
use Cheremhovo1990\Framework\Http\RequestHandlerWrapper;
use Cheremhovo1990\Framework\Pipeline\Next;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NextTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        App::setContainer(new Container());
    }

    public function testEmptyQueue()
    {
        $expect = 'empty';
        $next = new Next(
            new \SplQueue(),
            new RequestHandlerWrapper(function () use ($expect) {
                return new TextResponse($expect);
            })
        );
        $response = $next(new ServerRequest());
        $this->assertEquals($expect, $response->getBody()->getContents());
    }

    public function testQueue()
    {
        $queue = new \SplQueue();
        $queue->enqueue(new CallableMiddlewareWrapper(function (ServerRequestInterface $request, RequestHandlerInterface $next) {
            $response = $next->handle($request);
            return new TextResponse((string)($response->getBody()->getContents() * 3));
        }));
        $queue->enqueue(Middleware::class);
        $next = new Next(
            $queue,
            new RequestHandlerWrapper(function (){
                return new TextResponse((string)5);
            })
        );
        $response = $next(new ServerRequest());
        $this->assertEquals(9, $response->getBody()->getContents());
    }
}

class Middleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return new TextResponse((string)($response->getBody()->getContents() - 2));
    }
}