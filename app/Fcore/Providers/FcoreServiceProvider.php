<?php

namespace App\Fcore\Providers;

use App\Fcore\Facades\Fast;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class FcoreServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Views', 'Fcore');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Fast', Fast::class);
        });
    }

}
