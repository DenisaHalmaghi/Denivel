<?php

namespace App\Framework\Container;

class Container
{
  protected static $instance;

  /**
   * The closure/instance bindings.
   */
  protected $bindings = [];

  /**
   * The singleton instances.
   */
  protected $instances = [];

  public static function getInstance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  public function bind(string $key, callable $callback)
  {
    $this->bindings[$key] = [$callback, false];
  }

  public function singleton(string $key, callable $callback)
  {
    $this->bindings[$key] = [$callback, true];
  }

  public function resolve(string $key)
  {
    if (!$this->isBound($key)) {
      // throw new BindingResolutionException();
    }

    [$resolver, $isSingleton] = $this->bindings[$key];

    if ($isSingleton) {
      if (!$this->isInstanceInitialized($key)) {
        $this->instances[$key] = $resolver();
      }

      return $this->instances[$key];
    }

    return $resolver();
  }

  public function isBound(string $key): bool
  {
    return isset($this->bindings[$key]);
  }

  public function isInstanceInitialized(string $key): bool
  {
    return isset($this->instances[$key]);
  }
}
