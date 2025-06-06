<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Nothing to do here - the admin controller already uses
        // the container pattern to include all admin views within
        // the admin layout with sidebar
        
        // We are keeping this provider in case we need to add more
        // functionality in the future
    }
}
