<?php

use App\Framework\Facades\Router;

$router = new Router();

Router::get("/elo", function () {
  return "che fachetzi?";
});

Router::get("/home", [TestController::class, "index"]);
