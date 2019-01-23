<?php namespace App\Providers;

use App\Models\User;
use App\Auth\DoctrineUserProvider;
use Illuminate\Support\ServiceProvider;

class DoctrineAuthProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->extend('doctrine',function()
        {
            return new DoctrineUserProvider($this->app['hash']);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}