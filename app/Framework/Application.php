<?php

namespace App\Framework;

use App\Framework\Router;
use App\Controllers\TestController;

class Application
{
  /**
   * Class constructor.
   */

  protected Router $router;

  public function __construct()
  {
    $this->router = new Router();

    $this->router->get("/elo", function () {
      return "che fachetzi?";
    });

    $this->router->get("/home", [TestController::class, "index"]);
  }

  public function start()
  {
    echo $this->router->resolveRoute();
  }
}
