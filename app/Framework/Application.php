<?php

namespace App\Framework;

use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ServerRequestInterface;
use App\Framework\Request\Handler;
use App\Framework\Request\Request;
use App\Framework\Router\Route;
use App\Framework\Router\Router;
use App\Framework\Container\Container;
use App\Framework\Providers\RouteServiceProvider;
use App\Framework\Providers\ServiceProvider;
use Psr\Http\Server\MiddlewareInterface;

class Application extends Container
{
  /**
   * Class constructor.
   */
    protected array $providers = [];

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
        foreach ($providers as $provider) {
            $this->registerProvider(new $provider());
        }
    }

    public function basePath($nestedPath = null): string
    {
        return $nestedPath ? $this->basePath . "/$nestedPath" : $this->basePath;
    }

    public function start()
    {
        $request = Request::fromGlobals();
        $this->singleton(Request::class, fn() => $request);

        $this->registerUserDefinedProviders();
        $this->bootProviders();

        $route = $this->resolve(Router::class)->resolveRoute();

        $this->runRouteMiddleware($request, $route);
    }

    protected function runRouteMiddleware(ServerRequestInterface $request, Route $route)
    {
        $response =  array_reduce(
            array_reverse($route->getMiddleware()),
            $this->carry(),
            new Handler(fn()=>$route->handleActionForMethod($request->getMethod()))
        )
            ->handle($request);

        echo $response->getBody();
    }

    protected function carry()
    {
        return function (RequestHandlerInterface $next, $current) {
            return new Handler(function ($request) use ($next, $current) {
                if (is_string($current)) {
                    //instantiate the middleware
                    $current = (new $current($next));
                } elseif (is_callable($next)) {
                    $current = new Handler($current);
                }
                return $current->handle($request);
            });
        };
    }
}
