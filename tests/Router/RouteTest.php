<?php

declare(strict_types=1);

namespace Test\Router;

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

        self::assertEquals($controller, $route->match($request)->controller);
    }

    public function testNotMatch()
    {
        $route = new Route('default', '/', 'controller', ['GET']);
        $request = (new ServerRequest())
            ->withMethod('GET')
            ->withUri(new Uri('/blog'))
        ;

        self::assertNull($route->match($request));
    }

    public function testPattern()
    {
        $route = new Route('page', '/page/{id:\d+}/title/{title}', 'controller', ['GET'], ['requirements' => ['title' => '\w+']]);
        $request = (new ServerRequest())
            ->withMethod('GET')
            ->withUri(new Uri('/page/10/title/hello_world'))
        ;

        self::assertNotNull($route->match($request));
    }
}