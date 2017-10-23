<?php

namespace Acme\Extensions\Providers;

use Illuminate\Support\ServiceProvider;

class ErrorCodeServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('ErrorCode', function () {

            return new \Acme\Repository\ErrorCode();

        });
    }

}