<?php

namespace Optimisthub\LCSC\Providers;

use Illuminate\Support\ServiceProvider;

class LCSCServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../Migrations");
    }
}
