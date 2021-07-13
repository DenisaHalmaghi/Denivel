<?php

namespace App\Framework\Facades;

use App\Framework\Container\Container;

abstract class Facade
{
  public static abstract function getFacadeAccesor(): string;

  public static function __callStatic($method, $args)
  {
    $instance = Container::getInstance()->resolve(self::getFacadeAccesor());

    if (!$instance) {
      throw new \RuntimeException('Could not resolve facade instance. Make sure it\'s bound in the container');
    }

    return $instance->$method(...$args);
  }
}
