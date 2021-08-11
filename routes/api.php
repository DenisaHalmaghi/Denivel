<?php

use App\Controllers\TestController;
use App\Framework\Facades\Route;
use App\Middleware\SayHello;
use App\Middleware\SayHi;

Route::get("/", function () {
    return "che fachetzi?";
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

 Route::middleware([SayHi::class,SayHello::class])->group(function () {
     Route::get('/test', function () {
         return "test";
     });
 });

 Route::get("/home", [TestController::class, "index"]);
