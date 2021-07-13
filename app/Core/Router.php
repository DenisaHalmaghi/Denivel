<?php

namespace App\Core;

use App\Core\Request;

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
    $path = ltrim($this->request->path(), "/");
    // var_dump($path);
    $callback = $this->routes[$path][$this->request->method()];

    if (!$callback) {
      echo "route not registered";
      return;
    }

    $callback();
    var_dump($callback());
  }
}
