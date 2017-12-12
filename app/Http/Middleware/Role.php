<?php

namespace App\Http\Middleware;

use Acme\Repository\ErrorCode;
use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$ned_role='1')
    {    	
    	
    	
    	$had_role=\Session::get('role');
    	
    	//dump($ned_role);
    	//dump($had_role);
    	
        if($had_role!=$ned_role){
            return response('无权限');
        }
        return $next($request);
    }
}
