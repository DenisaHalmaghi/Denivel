<?php

namespace App\Framework\Facades;

use App\Framework\Container\Container;

abstract class Facade
{
    abstract public static function getFacadeAccesor(): string;

    public static function __callStatic($method, $args)
    {
        $instance = Container::getInstance()->resolve(static::getFacadeAccesor());

        if (!$instance) {
            throw new \RuntimeException('Could not resolve facade instance. Make sure it\'s bound in the container');
        }

        return $instance->$method(...$args);
    }
}
