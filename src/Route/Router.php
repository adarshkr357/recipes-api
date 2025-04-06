<?php

namespace App\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    protected $routes = [];

    public function add($method, $pattern, $handler)
    {
        $this->routes[strtoupper($method)][] = [
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $method = strtoupper($request->getMethod());
        $uri    = $request->getPathInfo();

        if (!isset($this->routes[$method])) {
            return new Response('Not Found', 404);
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                list($class, $callable) = explode('::', $route['handler']);
                if (class_exists($class) && method_exists($class, $callable)) {
                    $controller = new $class();
                    return call_user_func_array([$controller, $callable], [$request, ...$matches]);
                } else {
                    return new Response('Handler not found', 500);
                }
            }
        }
        return new Response('Not Found', 404);
    }
}
