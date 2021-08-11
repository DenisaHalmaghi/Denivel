<?php

namespace App\Middleware;

use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ResponseInterface;
use App\Framework\Contracts\ServerRequestInterface;
use App\Framework\Middleware\BaseMiddleware;

class SayHello extends BaseMiddleware
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        echo "hello!</br>";

        return $next->handle($request);
    }
}
