<?php

namespace App\Core;

use App\Core\Router;

class Application
{
  /**
   * Class constructor.
   */

  protected Router $router;

  public function __construct()
  {
    $this->router = new Router();

    $this->router->get("elo", function () {
      return "che fachetzi?";
    });
  }

  public function start()
  {
    $this->router->resolveRoute();
  }
}
