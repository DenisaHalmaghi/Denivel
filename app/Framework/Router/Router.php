<?php

namespace App\Framework\Router;

use App\Contracts\ServerRequestInterface;
use App\Framework\Request\Request;

class Router
{
    protected array $routes = [];

    protected ServerRequestInterface $request;

    protected $prefixesStack = [];

    /**
       * Class constructor.
   */
    public function __construct(Request $request = null)
    {
        $this->request = app(Request::class);
    }

    public function get($path, $callback)
    {
        $this->routes[] = $this->createRoute($path, Request::METHOD_GET, $callback);
    }

    public function post($path, $callback)
    {
        $this->routes[] = $this->createRoute($path, Request::METHOD_POST, $callback);
    }

    protected function createRoute($uri, $method, $action): Route
    {
        $realPath = $this->appendPrefix($uri);
        return new Route($realPath, $method, $action);
    }

    protected function getPrefix()
    {
        return end($this->prefixesStack);
    }

    protected function appendPrefix($prefix)
    {
        return rtrim($this->getPrefix(), "/") . '/' . ltrim($prefix, "/");
    }

    public function prefix($prefix)
    {
        $this->prefixesStack[] = $this->appendPrefix($prefix);
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
        $callable();
        array_pop($this->prefixesStack);
    }

    public function resolveRoute()
    {
        $path = $this->request->getUri();

        if (!($route = $this->getMatchedRoute($path))) {
            echo "$path is not registered";
            return null;
        }

        if (!$route->hasMethod($requestMethod = $this->request->getMethod())) {
            echo "$path does not support $requestMethod requests";
            return null;
        }

        return $route->handleActionForMethod($requestMethod);
    }

    public function getMatchedRoute($path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->isForPath($path)) {
                return $route;
            }
        }
        return null;
    }
}
