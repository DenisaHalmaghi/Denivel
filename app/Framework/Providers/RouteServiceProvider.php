<?php

namespace App\Framework\Providers;

use App\Framework\Router\Router;
use App\Framework\Providers\ServiceProvider;

class RouteServiceProvider implements ServiceProvider
{
  public function register()
  {
    // echo "hehehe";
    app()->singleton(Router::class, fn () => new Router());
  }

  public function boot()
  {
    $routes = app()->basePath("routes/api.php");
    require_once $routes;
  }
}
