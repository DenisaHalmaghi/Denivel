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
    public array $methods;

    public array actions;

    public function __construct(
        protected string $uri,
        string $method,
        array|Closure $action
    ) {
        $this->methods[] = $method;
        $this->actions[$method] = $action;
    }
}
