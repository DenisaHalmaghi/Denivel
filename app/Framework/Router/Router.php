<?php

namespace App\Framework\Router;

use App\Contracts\ServerRequestInterface;
use App\Framework\Request\Request;

class Router
{
    protected array $routes = [];

    protected ServerRequestInterface $request;

    protected $prefixes = [];

    /**
       * Class constructor.
   */
    public function __construct(Request $request = null)
    {
        $this->request = app(Request::class);
    }

    public function get($path, $callback)
    {
        $this->routes[$path] = $this->createRoute($path, Request::METHOD_GET, $callback);
    }

    public function post($path, $callback)
    {
        $this->routes[$path] = $this->createRoute($path, Request::METHOD_POST, $callback);
    }

    protected function createRoute($uri, $method, $action): Route
    {
        return new Route($uri, $method, $action);
    }

    public function prefix($prefix)
    {
        $this->prefixes[] = $prefix;
        return $this;
    }

    public function middleware($middleware)
    {
        if (is_array($middleware)) {
            return ;
        }
    }

    public function group(callable $callable)
    {
    }

    public function resolveRoute()
    {
        $path = $this->request->getUri();

        if (!$this->isPathRegistered($path)) {
            echo "$path is not registered";
            return null;
        }

        $requestMethod = $this->request->getMethod();

        if (!$this->isMethodRegisteredForPath($path, $requestMethod)) {
            echo "$path does not support $requestMethod requests";
            return null;
        }
        return $this->routes[$path]->handleActionForMethod($requestMethod);
    }

    public function isPathRegistered($path): bool
    {
        return array_key_exists($path, $this->routes);
    }

    public function isMethodRegisteredForPath($path, $method): bool
    {
        return $this->routes[$path]->hasMethod($method);
    }
}
