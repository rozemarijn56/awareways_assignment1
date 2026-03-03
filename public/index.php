<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Router;
use App\Http\Response;

$router = new Router();

$router->get(path: '/health', handler: function (): string {
    return Response::json(data: [
        'status' => 'ok'
    ]);
});

$router->dispatch(method: $_SERVER['REQUEST_METHOD'], uri: $_SERVER['REQUEST_URI']);