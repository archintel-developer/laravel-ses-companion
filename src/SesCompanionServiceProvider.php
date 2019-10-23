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
        
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/companion'
        )], 'blade-templates');

        $this->publishes([
            __DIR__.'/Mail' => app_path('Http/Controllers'
        )], 'mail');

        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
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