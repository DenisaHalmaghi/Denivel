<?php

namespace App\Framework\Request;

use App\Framework\Contracts\RequestHandlerInterface;
use App\Framework\Contracts\ResponseInterface;
use App\Framework\Contracts\ServerRequestInterface;

class Handler implements RequestHandlerInterface
{
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->callable)($request);
    }
}
