<?php

use App\Helpers\Router;


$router = new Router;
$GLOBALS['router'] = $router;

$router->mount('', function () use ($router) {
});

$router->match("GET", '/test', function () {
    $DatabaseQuery = DatabaseQuery();
    dd($DatabaseQuery['query']->table("bugs")->get());
});

$router->set404('App\Helpers\Users@404');

$router->run();
