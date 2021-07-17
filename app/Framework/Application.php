<?php

namespace App\Framework;

use App\Framework\Router\Router;
use App\Framework\Container\Container;
use App\Framework\Providers\RouteServiceProvider;
use App\Framework\Providers\ServiceProvider;

class Application extends Container
{
  /**
   * Class constructor.
   */
  protected $providers = [];

  protected $basePath = "";

  protected Container $container;

  public function __construct($basePath)
  {
    $this->basePath = $basePath;
    $this->container = Container::getInstance();
    $this->bootstrap();
  }

  protected function bootstrap()
  {
    //"activate" helpers
    require_once __DIR__ . "/helpers.php";

    $this->selfBind();
    $this->registerCoreProviders();
    $this->bootProviders();
  }

  protected function selfBind()
  {
    static::setInstance($this);

    $this->bind(Container::class, fn () => $this);
  }

  protected function registerCoreProviders()
  {
    $this->registerProvider(new RouteServiceProvider());
  }

  protected function registerProvider(ServiceProvider $provider)
  {
    $this->providers[] = $provider;
    $provider->register();
  }

  protected function bootProviders()
  {
    foreach ($this->providers as $provider) {
      $provider->boot();
    }
  }

  protected function registerCoreServices()
  {
    $this->container->bind(Router::class, fn () => $this->router);
  }

  public function basePath($nestedPath)
  {
    return $this->basePath . "/$nestedPath";
  }

  public function start()
  {
    echo app(Router::class)->resolveRoute();
  }
}
