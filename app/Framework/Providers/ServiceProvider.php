<?php

namespace App\Framework\Providers;

interface ServiceProvider
{
    public function register();
    public function boot();
}
