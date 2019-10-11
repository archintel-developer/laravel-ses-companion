<?php

namespace ArchintelDev\SesCompanion;

use Illuminate\Support\ServiceProvider;

class SesCompanionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . '/resources/assets' => resource_path('js/ArchintelDev/SesCompanion'
        )], 'vue-components');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}