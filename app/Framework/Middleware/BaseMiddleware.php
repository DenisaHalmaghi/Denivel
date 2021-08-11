<?php

namespace App\Framework\Middleware;

use App\Framework\Contracts\MiddlewareInterface;
use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ResponseInterface;
use App\Framework\Contracts\ServerRequestInterface;

abstract class BaseMiddleware implements MiddlewareInterface, RequestHandlerInterface
{
    public function __construct(protected RequestHandlerInterface $next)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process($request, $this->next);
    }
}
