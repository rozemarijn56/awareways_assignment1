<?php 

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Router;
use App\Http\Response;
use App\Db\Connection;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$router = new Router();


$router->get(path: '/health', handler: function (): string {
    
    Connection::get()->query(query: 'SELECT 1')->fetch();
    
    return Response::json(data: [
        'status' => 'ok'
    ]);
});

$router->dispatch(method: $_SERVER['REQUEST_METHOD'], uri: $_SERVER['REQUEST_URI']);