<?php

use App\Helpers\Router;
use App\Controllers\AppController;


$router = new Router;
$GLOBALS['router'] = $router;

$router->mount('', function () use ($router) {
});

$router->match("GET", '/test', function () {
    echo "ok";
});

$router->set404(function () use ($router) {
    $AppController = new AppController();
    $AppController->NotFound404($router->getCurrentUri());
});

$router->run();
