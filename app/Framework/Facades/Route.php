<?php

namespace App\Framework\Facades;

use App\Framework\Router\Router as ConcreteRouter;

class Route extends Facade
{
    public static function getFacadeAccesor(): string
    {
        return ConcreteRouter::class;
    }
}
