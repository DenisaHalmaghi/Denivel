<?php

namespace App\Middleware;

use App\Framework\Contracts\MiddlewareInterface;
use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ResponseInterface;
use App\Framework\Contracts\ServerRequestInterface;

class SayHi implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $next
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        echo "hi!</br>";

        $next->handle($request);
    }
}
