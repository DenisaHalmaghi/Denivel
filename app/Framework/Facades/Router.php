<?php

namespace App\Framework\Facades;

use App\Framework\Router as ConcreteRouter;

class Router extends Facade
{
  public static function getFacadeAccesor(): string
  {
    return ConcreteRouter::class;
  }
}
