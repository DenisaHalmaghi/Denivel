<?php

namespace App\Framework\Router;

use App\Framework\Contracts\ServerRequestInterface;
use App\Framework\Request\Request;

class Router
{
    protected array $routes = [];

    protected ServerRequestInterface $request;

    protected $groupStack = [];

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
        $path = $this->appendPrefix($uri);
        return (new Route($path, $method, $action))
            ->middleware($this->getGroupAttribute("middleware"));
    }

    protected function getGroupAttribute($attribute, $default = [])
    {
        $last = end($this->groupStack) ;
        if (!$last) {
            $last = [];
        }
        return array_key_exists($attribute, $last) ? $last[$attribute] : $default;
    }

    protected function appendPrefix($prefix)
    {
        return rtrim($this->getGroupAttribute("prefix", ""), "/") . '/' . ltrim($prefix, "/");
    }

    private function mergeGroupMiddleware($middleware)
    {
        return array_merge($this->getGroupAttribute("middleware"), $middleware);
    }

    public function prefix($prefix)
    {
        $this->groupStack[] = ["prefix" => $this->appendPrefix($prefix)];
        return $this;
    }

    public function middleware($middleware)
    {
        $this->groupStack[] = ["middleware" => $this->mergeGroupMiddleware($middleware)];

        return $this;
    }

    public function group(callable $callable)
    {
        $callable();
        array_pop($this->groupStack);
    }

    /**
     * @throws \Exception
     */
    public function resolveRoute(): Route
    {
        $path = $this->request->getUri();

        if (!($route = $this->getMatchedRoute($path))) {
            throw new \Exception("$path is not registered");
        }

        if (!$route->hasMethod($requestMethod = $this->request->getMethod())) {
            throw new \Exception("$path does not support $requestMethod requests");
        }

        return $route;
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
