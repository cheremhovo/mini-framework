<?php

/** @var \Router\RouteCollection $routeCollection  */

use Cheremhovo1990\Framework\App;
use Cheremhovo1990\Framework\Helper\StringHelper;
use Cheremhovo1990\Framework\Router\RouterMapController;
use Laminas\Diactoros\ServerRequestFactory;

$routeCollection->get('about', '/about/{id:\d+}/page/{page_id:\d+}', function () {
    $request = ServerRequestFactory::fromGlobals();
    $name = $request->getQueryParams()['name'] ?: 'Guest';
    return 'hello ' . $name . '!';
});

$routeCollection->get('about.page', '/about/page/{id}', function (\Psr\Http\Message\RequestInterface $request) {
    return 'page ' . $request->getAttribute('id');
}, ['requirements' => ['id' => '\w+']]);

$paths = glob(__DIR__ . '/../src/Controller/*Controller.php');
$paths = array_merge($paths, glob(__DIR__ . '/../src/Controller/*/*Controller.php'));

$classes = [];
foreach ($paths as $path) {
    $path = realpath($path);
    $class = StringHelper::replace(App::getRootDirectory('src/'), 'App/', $path);
    $class = StringHelper::replaceEnd('.php', "", $class);
    $class = StringHelper::replace(DIRECTORY_SEPARATOR, '\\', $class);
    $classes[] = $class;
}

$reflection = new RouterMapController($classes);
$routes = $reflection();
foreach ($routes as $route) {
    $routeCollection->any(...$route);
}