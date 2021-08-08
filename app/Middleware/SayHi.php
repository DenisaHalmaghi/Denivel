<?php

namespace App\Middleware;

use App\Contracts\RequestHandlerInterface;
use App\Contracts\ResponseInterface;
use App\Contracts\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class SayHi implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        echo "hi!</br>";
        return $handler->handle($request);
    }
}
