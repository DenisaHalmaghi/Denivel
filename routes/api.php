<?php

use App\Controllers\TestController;
use App\Framework\Facades\Router;

Router::get("/", function () {
    return "che fachetzi?";
});

//TODO:
 Router::prefix('api')->group(function () {
    Router::prefix('v1')->group(function () {
        Router::prefix('auth')->group(function () {
            Router::get('/login', function () {
                return "login";
            });

            Router::get('/register', function () {
                return "register";
            });
        });

        Router::get('/users', function () {
            return "users";
        });
    });
 });

 Router::get("/home", [TestController::class, "index"]);
