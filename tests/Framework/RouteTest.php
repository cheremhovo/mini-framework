<?php

namespace Framework;

use Cheremhovo1990\Framework\Router\Route;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testMatch()
    {
        $route = new Route('default', '/', $controller = 'controller', ['GET']);
        $request = (new ServerRequest())
            ->withMethod('GET')
            ->withUri(new Uri('/'))
        ;

        self::assertEquals($controller, $route->match($request));
    }

    public function testNotMatch()
    {
        $route = new Route('default', '/', $controller = 'controller', ['GET']);
        $request = (new ServerRequest())
            ->withMethod('GET')
            ->withUri(new Uri('/blog'))
        ;

        self::assertNull($route->match($request));
    }
}