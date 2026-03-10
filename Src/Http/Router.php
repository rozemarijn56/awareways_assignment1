<?php
declare(strict_types=1);

namespace App\Http;

class Router 
{
    private array $routes = [];

    public function get(string $path, callable $handler): void{
        $this->addRoute('GET', $path, $handler);
    }

    public  function post(string $path, callable $handler) : void {
            $this->addRoute('POST', path: $path, handler: $handler);
    }

    public  function put(string $path, callable $handler) : void {
            $this->addRoute('PUT', path: $path, handler: $handler);
    }
    public  function patch(string $path, callable $handler) : void {
        $this->addRoute(method: 'PATCH', path: $path, handler: $handler);
    }


    public  function delete(string $path, callable $handler) : void {
        $this->addRoute(method: 'GET', path: $path, handler: $handler);
    }

    public function dispatch(string $method, string $uri): void{
        $path = parse_url(url: $uri, component: PHP_URL_PATH);

        if(isset($this->routes[$method][$path])) {
            $response = $this->routes[$method][$path]();
            echo $response;
            return;
        }

        http_response_code(response_code: 404);
        echo json_encode(value: ['error' => 'Not found']);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

}
