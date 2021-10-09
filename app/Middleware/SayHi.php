<?php

namespace App\Middleware;

use App\Framework\Contracts\MiddlewareInterface;
use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ResponseInterface;
use App\Framework\Contracts\ServerRequestInterface;
use App\Framework\Middleware\BaseMiddleware;

class SayHi extends BaseMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        echo "hi!</br>";

        return $next->handle($request);
    }
}
