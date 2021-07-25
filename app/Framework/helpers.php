<?php

use App\Framework\Container\Container;

function app($containerKey = null)
{
    if (!$containerKey) {
        return Container::getInstance();
    }

    return Container::getInstance()->resolve($containerKey);
}

function baseUrl($appenedUrl = "")
{
    return app()->getBasePath();
}
