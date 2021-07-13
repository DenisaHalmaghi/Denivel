<?php

namespace App\Framework;



class Request
{
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
