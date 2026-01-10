<?php

use Cheremhovo1990\Framework\App;
use Cheremhovo1990\Framework\Container\Container;
use Cheremhovo1990\Framework\Helper\UrlHelper;
use Cheremhovo1990\Framework\Http\HandlerController;
use Cheremhovo1990\Framework\Pipeline\Pipeline;
use Cheremhovo1990\Framework\Router\RouteCollection;
use Cheremhovo1990\Framework\Router\Router;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;


require __DIR__ . '/../../vendor/autoload.php';

### Initialization

$container = new Container();
App::setContainer($container);
App::setRootDirectory(realpath(__DIR__ . '/../'));

$routeCollection = new RouteCollection();
require __DIR__ . '/../config/routes.php';
$router = new Router($routeCollection);
UrlHelper::setRouter($router);

$pipeline = new Pipeline();
require __DIR__ . '/../config/pipeline.php';

### Running
$request = ServerRequestFactory::fromGlobals();

$controller = $router->match($request);
foreach ($controller->arguments as $key => $argument) {
    $request = $request->withAttribute($key, $argument);
}
$handlerController = new HandlerController($controller);
try {
    $response = $pipeline($request, $handlerController);
} catch (Throwable $e) {
    $response = new TextResponse($e->getMessage());
}


### Postprocessing

$response = $response->withHeader('X-ID', 'Mini');

### Sending

(new SapiEmitter())->emit($response);