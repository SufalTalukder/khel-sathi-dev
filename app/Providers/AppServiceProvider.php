<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
            if (!str_contains($host, '127.0.0.1') && !str_contains($host, 'localhost') && !str_ends_with($host, '.test')) {
                URL::forceScheme('https');
            }
        } elseif (!app()->runningInConsole() && app()->environment('production')) {
            URL::forceScheme('https');
        }

        if (config('app.url') && app()->runningInConsole()) {
            URL::forceRootUrl(config('app.url'));
        }
    }
}
