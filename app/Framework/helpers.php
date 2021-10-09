<?php

use App\Framework\Container\Container;
use App\Framework\Request\Response;
use JetBrains\PhpStorm\Pure;

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

#[Pure] function response(string $body, int $status): Response
{
    return new Response($status, body:$body);
}
