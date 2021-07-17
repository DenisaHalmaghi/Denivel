<?php

namespace App\Framework\Request;

class Request
{
  public const METHOD_GET = 'GET';
  public const METHOD_POST = 'POST';
  public const METHOD_PUT = 'PUT';
  public const METHOD_PATCH = 'PATCH';
  public const METHOD_DELETE = 'DELETE';

  public function path(): string
  {
    $uri = explode("?", $_SERVER["REQUEST_URI"]);
    return $uri[0];
  }

  public function method()
  {
    return $_SERVER["REQUEST_METHOD"];
  }
}
