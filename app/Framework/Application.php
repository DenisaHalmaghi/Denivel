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

  protected function registerUserDefinedProviders()
  {
    $providersFile = $this->basePath("config/providers.php");
    $providers = (require_once $providersFile)["providers"];
    foreach ($providers as  $provider) {
      $this->registerProvider(new $provider());
    }
  }

  protected function registerCoreServices()
  {
    $this->container->bind(Router::class, fn () => $this->router);
  }

  public function basePath($nestedPath = null)
  {
    return $nestedPath ? $this->basePath . "/$nestedPath" : $this->basePath;
  }

  public function start()
  {
    $this->registerUserDefinedProviders();
    $this->bootProviders();

    echo $this->resolve(Router::class)->resolveRoute();
  }
}
