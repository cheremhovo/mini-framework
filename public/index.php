<?php

use Cheremhovo1990\Framework\Router\RouteCollection;
use Cheremhovo1990\Framework\Router\Router;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

### Initialization

$routes = new RouteCollection();

$routes->get('default', '/', function () {
    return 'hello world';
});

$routes->get('about', '/about', function () {
    $request = ServerRequestFactory::fromGlobals();
    $name = $request->getQueryParams()['name'] ?: 'Guest';
    return 'hello ' . $name . '!';
});

$router = new Router($routes);

### Running
$request = ServerRequestFactory::fromGlobals();

/** @var callable $controller */
$controller = $router->match($request);
$response = $controller();
if (!$response instanceof ResponseInterface) {
    if (is_string($response)) {
        $response = new HtmlResponse($response);
    }
}
### Postprocessing

$response = $response->withHeader('X-ID', 'Mini');

### Sending

(new SapiEmitter())->emit($response);