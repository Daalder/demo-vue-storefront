<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Services\Auth\CustomerAuthenticator;
use App\Services\Cookie;
use Illuminate\Foundation\Application;
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
        $this->app->singleton(CustomerAuthenticator::class, function (Application $app) {
            return new CustomerAuthenticator(
                $app->make(CustomerRepository::class),
                $app->make(Cookie::class)
            );
        });
    }
}
