<?php

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch($requestUri, $requestMethod)
    {
        foreach ($this->routes as $route) {
            if ($route['path'] === $requestUri && $route['method'] === strtoupper($requestMethod)) {
                call_user_func($route['callback']);
                return;
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
}
