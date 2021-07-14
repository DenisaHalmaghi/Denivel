<?php

namespace App\Framework;

use App\Framework\Router;
use App\Framework\Container\Container;

class Application
{
  /**
   * Class constructor.
   */

  public function __construct()
  {
    $this->router = new Router();
    $this->container = Container::getInstance();
    $this->bootstrap();
  }

  protected function bootstrap()
  {
    //regiser in container
    $this->registerCoreServices();
    $this->registerRoutes();
  }

  protected function registerRoutes()
  {
    $routes = __DIR__ . "/../../routes/api.php";
    require_once $routes;
  }

  protected function registerCoreServices()
  {
    $this->container->bind(Router::class, fn () => $this->router);
  }

  public function start()
  {
    echo $this->router->resolveRoute();
  }
}
