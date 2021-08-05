<?php

use App\Controllers\TestController;
use App\Framework\Facades\Route;

Route::get("/", function () {
    return "che fachetzi?";
});

//TODO:
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
