<?php

use App\Controllers\TestController;
use App\Framework\Facades\Route;
use App\Middleware\SayHello;
use App\Middleware\SayHi;

Route::middleware([SayHi::class,SayHello::class])->group(function () {
    Route::get('/', function () {
        return response("che fachetzi?", 201);
    });
});

 Route::prefix('api')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('/login', function () {
                return "login";
            });

            Route::get('/register', function () {
                return "register";
            });
        });

        Route::get('/users', function () {
            return "users";
        });
    });
 });

 Route::get("/home", [TestController::class, "index"]);
