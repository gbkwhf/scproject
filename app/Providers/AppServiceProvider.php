<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //扩展验证方法
        \Validator::extend('mobile', function($attribute, $value, $parameters) {
            $num_exp = '/^1[034578][0-9]{9}$/';
            if(!preg_match($num_exp,$value)){
                return false;
            }else{
                return true;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::setLocale('zh');
        
    }
}
