<?php

namespace App\Framework;

use App\Framework\Request;

class Router
{
  protected $routes = [];

  protected const GET = "GET";
  protected const POST = "POST";
  protected const PATCH = "PATCH";
  protected const DELETE = "DELETE";

  protected $request;

  /**
   * Class constructor.
   */
  public function __construct(Request $request = null)
  {
    $this->request = new Request();
  }

  public function get($path, $callback)
  {
    $this->routes[$path][self::GET] = $callback;
  }

  public function resolveRoute()
  {
    $path = $this->request->path();
    // var_dump($path);
    if (!array_key_exists($path, $this->routes)) {
      echo "route not registered";
      return;
    }
    $action = $this->routes[$path][$this->request->method()];

    if (!$action) {
      echo "no action registered for $path";
      return;
    }

    if (is_callable($action)) {
      return $action();
    }

    if (is_array($action)) {
      [$controller, $method] = $action;

      // if (!class_exists($controller)) {
      //   echo "controller $controller not found";
      //   return;
      // }

      // if (!method_exists($controller, $method)) {
      //   echo "method $method does not exist on $controller";
      //   return;
      // }

      return (new $controller)->$method();
    }
  }
}
