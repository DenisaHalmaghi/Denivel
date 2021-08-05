<?php

namespace App\Framework\Router;

use Closure;

class Route
{
    /**
     * The HTTP methods the route responds to.
     *
     * @var array
     */
    public array $methods = [];

    protected array $middleware = [];

    public array $actions = [];

    public function __construct(
        protected string $uri,
        string $method,
        array|Closure $action
    ) {
        $this->methods[] = $method;
        $this->actions[$method] = $action;
    }

    public function hasMethod(string $method)
    {
        return in_array($method, $this->methods);
    }

    public function handleActionForMethod($method): mixed
    {
        $action = $this->actions[$method];

        if (is_callable($action)) {
            //inject here
            return $action();
        }

        if (is_array($action)) {
            [$controller, $method] = $action;

            if (!class_exists($controller)) {
                echo "controller $controller not found";
                return null;
            }

            if (!method_exists($controller, $method)) {
                echo "method $method does not exist on $controller";
                return null;
            }
            //inject here
            return (new $controller())->$method();
        }
    }
}
