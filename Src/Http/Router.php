<?php
declare(strict_types=1);

namespace App\Http;

class Router 
{
    private array $routes = [];

    public function get(string $path, callable $handler): void{
        $this->routes['GET'][$path] = $handler;
    }

    public  function post(string $path, callable $hander) : void {
        $this->routes['POST'][$path] = $hander;
    }

    public  function put(string $path, callable $hander) : void {
        $this->routes['PUT'][$path] = $hander;
    }
    public  function patch(string $path, callable $hander) : void {
        $this->routes['PATCH'][$path] = $hander;
    }


    public  function delete(string $path, callable $hander) : void {
        $this->routes['DELETE'][$path] = $hander;
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

}
