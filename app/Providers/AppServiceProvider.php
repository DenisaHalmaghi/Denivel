<?php

namespace App\Providers;

use App\Framework\Providers\ServiceProvider;

class AppServiceProvider implements ServiceProvider
{
    public function register()
    {
      //register stuff in container
        echo "--------------AppServiceProvider register -------";
    }

    public function boot()
    {
    }
}
