<?php

namespace Application\Infrastructure\Presentation;


use src\utils\Response;

class Router
{
    private array $routes = [];

    public function get($path, $handler): void
    {
        $this->routes[] = [
            'method'  => 'GET',
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function post($path, $handler): void
    {
        $this->routes[] = [
            'method'  => 'POST',
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function put($path, $handler): void
    {
        $this->routes[] = [
            'method'  => 'PUT',
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function patch($path, $handler): void
    {
        $this->routes[] = [
            'method'  => 'PATCH',
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function delete($path, $handler): void
    {
        $this->routes[] = [
            'method'  => 'DELETE',
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] == strtoupper($method) && preg_match("#^{$route['path']}$#", $uri, $matches)) {
                array_shift($matches);

                return call_user_func_array($route['handler'], $matches);
            }
        }

        Response::json([], 404, 'Not Found');
    }
}