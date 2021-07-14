<?php

use App\Framework\Facades\Router;
use App\Controllers\TestController;

Router::get("/elo", function () {
  return "che fachetzi?";
});

Route::prefix('auth')->group(function () {
  Route::get('/users', function () {
    // Matches The "/admin/users" URL
  });
});

Router::get("/home", [TestController::class, "index"]);
