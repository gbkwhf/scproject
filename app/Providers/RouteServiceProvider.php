<?php

namespace App\Providers;

use Acme\Exceptions\SessionInvalidException;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
        $router->bind('session', function($value){
            $session=\App\Session::where('session',$value)->with('base')->first();
            if (! is_null($session)) {
                return $session;
            }

            throw (new SessionInvalidException);

        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
            require app_path('Http/routes/public.php');
            require app_path('Http/routes/backmanage.php');
            require app_path('Http/routes/user.php');
            require app_path('Http/routes/profession.php');
            require app_path('Http/routes/goods.php');

        });
    }
}
