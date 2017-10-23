<?php

namespace App\Http\Middleware;

use Acme\Repository\ErrorCode;
use Closure;

class CheckSoftVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! $request->has('os_type') || !$request->has('version')){
            return response()->json([
                'code' => ErrorCode::SYSTEM_VERSION_LOW,
                'msg' => (new ErrorCode)->getMessage(ErrorCode::SYSTEM_VERSION_LOW)
            ]);
        }

        $type = $request->get('os_type');
        $version = $request->get('version');
        $verFlag =  \App\UpdateInfo::isUpdateVersion($type,$version);

        if ($verFlag) {
            return response()->json([
                'code' => ErrorCode::SYSTEM_VERSION_LOW,
                'msg' => (new ErrorCode)->getMessage(ErrorCode::SYSTEM_VERSION_LOW)
            ]);

        }

        return $next($request);
    }
}
