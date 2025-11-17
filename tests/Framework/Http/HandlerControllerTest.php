<?php

declare(strict_types=1);

namespace Test\Framework\Http;

use Cheremhovo1990\Framework\App;
use Cheremhovo1990\Framework\Container\Container;
use Cheremhovo1990\Framework\Http\HandlerController;
use Cheremhovo1990\Framework\Router\ResultRoute;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class HandlerControllerTest extends TestCase
{
    public function testHandle()
    {
        $request = new ServerRequest();
        $message = 'this is a response';
        $controller = function (ServerRequestInterface $request) use ($message) {
            return $message;
        };
        $resultRoute = new ResultRoute($controller);
        $handler = new HandlerController($resultRoute);
        $response = $handler->handle($request);
        $this->assertEquals($message, $response->getBody()->getContents());

        $request = new ServerRequest();
        $controller = [Controller::class, 'action'];
        $resultRoute = new ResultRoute($controller);
        $handler = new HandlerController($resultRoute);
        $response = $handler->handle($request);
        $this->assertEquals('class controller', $response->getBody()->getContents());
    }
}



class Controller {
    public function action()
    {
        return 'class controller';
    }
}