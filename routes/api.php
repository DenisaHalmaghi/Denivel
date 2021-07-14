<?php

use App\Framework\Facades\Router;
use App\Controllers\TestController;

Router::get("/elo", function () {
  return "che fachetzi?";
});

//TODO:
// Router::prefix('auth')->group(function () {
//   Router::get('/users', function () {
//     // Matches The "/admin/users" URL
//   });
// });

Router::get("/home", [TestController::class, "index"]);
