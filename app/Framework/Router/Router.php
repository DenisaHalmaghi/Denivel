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
        $this->request = new Request();
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

    public function middleware($prefix)
    {
        $this->prefixes[] = $prefix;
        return $this;
    }

    public function group(callable $callable)
    {
    }

    public function resolveRoute()
    {
        $path = $this->request->path();

        if (!$this->isPathRegistered($path)) {
            echo "$path is not registered";
            return null;
        }

        $requestMethod = $this->request->method();

        if (!$this->isMethodRegisteredForPath($path, $requestMethod)) {
            echo "$path does not support $requestMethod requests";
            return null;
        }

        return $this->handleAction($this->routes[$path][$requestMethod]);
    }

    public function handleAction($action): mixed
    {
        if (is_callable($action)) {
            //inject here
            return $action();
        }

        if (is_array($action)) {
            [$controller, $method] = $action;

            if (!class_exists($controller)) {
                echo "controller $controller not found";
                return;
            }

            if (!method_exists($controller, $method)) {
                echo "method $method does not exist on $controller";
                return;
            }
            //inject here
            return (new $controller())->$method();
        }
    }

    public function isPathRegistered($path): bool
    {
        return array_key_exists($path, $this->routes);
    }

    public function isMethodRegisteredForPath($path, $method): bool
    {
        return array_key_exists($method, $this->routes[$path]);
    }
}
