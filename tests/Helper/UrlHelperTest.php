<?php

declare(strict_types=1);

namespace Test\Helper;

use Cheremhovo1990\Framework\Helper\UrlHelper;
use Cheremhovo1990\Framework\Router\Route;
use Cheremhovo1990\Framework\Router\RouteCollection;
use Cheremhovo1990\Framework\Router\Router;
use PHPUnit\Framework\TestCase;

class UrlHelperTest extends TestCase
{
    public function testGenerate()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(new Route('default', '/', 'controller'));
        $routeCollection->addRoute(new Route('news', '/news/{id}', 'controller'));

        UrlHelper::setRouter(new Router($routeCollection));

        $this->assertEquals('/', UrlHelper::generate('default'));
        $this->assertEquals('/news/10', UrlHelper::generate('news', ['id' => 10]));
    }
}