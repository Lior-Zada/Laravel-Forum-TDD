<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
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
        //If you want to affect ALL VIEWS -> use a "*" instead of array of views.
        view()->composer(['threads.create', 'layouts.app'], function($view){
            //Save channels to chache 
            $channels = Cache::rememberForever('channels', function(){
                return Channel::all();
            });

            $view->with('channels', $channels);
        });

        // This will share with all views also.
        // view()->share('channels', Channel::all());

        Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
        Validator::extend('recaptcha', 'App\Rules\Recaptcha@passes');
    }
}
