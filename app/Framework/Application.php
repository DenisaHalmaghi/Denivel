<?php

namespace App\Framework;

use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ResponseInterface;
use App\Framework\Contracts\ServerRequestInterface;
use App\Framework\Request\Handler;
use App\Framework\Request\Request;
use App\Framework\Router\Route;
use App\Framework\Router\Router;
use App\Framework\Container\Container;
use App\Framework\Providers\RouteServiceProvider;
use App\Framework\Providers\ServiceProvider;

class Application extends Container
{
  /**
   * Class constructor.
   */
    protected array $providers = [];

    protected string $basePath = "";

    protected Container $container;

    protected ServerRequestInterface $request;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->container = Container::getInstance();
        $this->bootstrap();
    }

    protected function bootstrap()
    {
        $this->requireHelpers();

        $this->selfBind();
        $this->registerCoreProviders();
    }

    protected function requireHelpers()
    {
        require_once __DIR__ . "/helpers.php";
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
        $userDefinedProviders = (require_once $providersFile)["providers"];
        foreach ($userDefinedProviders as $provider) {
            $this->registerProvider(new $provider());
        }
    }

    public function basePath($nestedPath = null): string
    {
        return $nestedPath ? $this->basePath . "/$nestedPath" : $this->basePath;
    }

    protected function bindRequest(): void
    {
        $this->request = $this->createRequest();
        $this->singleton(Request::class, fn() => $this->request);
    }

    protected function createRequest(): ServerRequestInterface
    {
        return Request::fromGlobals();
    }

    public function start(): ResponseInterface
    {
        $this->bindRequest();

        $this->registerUserDefinedProviders();

        $this->bootProviders();

        $route = $this->resolve(Router::class)->resolveRoute();

        return $this->passRequestThroughRouteMiddleware($route);
    }

    protected function passRequestThroughRouteMiddleware(Route $route): ResponseInterface
    {
        return array_reduce(
            array_reverse($route->getMiddleware()),
            $this->carry(),
            new Handler(fn()=>$route->handleActionForMethod($this->request->getMethod()))
        )
            ->handle($this->request);
    }

    protected function carry(): \Closure
    {
        return function (RequestHandlerInterface $nextHandler, string|callable|RequestHandlerInterface $currentHandler) {
            return new Handler(function ($request) use ($nextHandler, $currentHandler) {
                if (is_string($currentHandler)) {
                    $currentHandler = (new $currentHandler($nextHandler));
                } elseif (is_callable($nextHandler)) {
                    $currentHandler = new Handler($currentHandler);
                }
                return $currentHandler->handle($request);
            });
        };
    }
}
