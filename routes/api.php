<?php

use App\Framework\Facades\Router;
use App\Controllers\TestController;

Router::get("/", function () {
  return "che fachetzi?";
});

//TODO:
// Router::prefix('api')->group(function () {
//   Router::prefix('v1')->group(function () {
//     Router::prefix('auth')->group(function () {
//       Router::get('/login', function () {
//         return "login";
//       });
//     });
//   });
// });

// Router::prefix('auth')->group(function () {
//   Router::get('/login', function () {
//     return "login";
//   });
// });

Router::get("/home", [TestController::class, "index"]);
