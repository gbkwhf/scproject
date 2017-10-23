<?php

namespace App\Http\Middleware;
use App\Session;
use Closure;
use Acme\Repository\ErrorCode;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$sessionTable='stj_session_info')
    {
        if(! $request->has('ss')){

            return response()->json([
                'code' => ErrorCode::API_ERR_MISSED_PARAMATER,
                'msg' => (new ErrorCode)->getMessage(ErrorCode::API_ERR_MISSED_PARAMATER)
            ]);

        }

        //根据所传表名修改表名
        Session::$tableName=$sessionTable;


        //检验session

//        $session = \Cache::get($sessionTable.$request->ss, function() use($request,$sessionTable) {
//
//            $session =Session::where('session',$request->only('ss'))->pluck('session');
//
//            if($session){
//                \Cache::put($sessionTable.$session,1,\Carbon\Carbon::now()->addDay(1));
//                return $session;
//            }else{
//                return false;
//            }
//
//        });

        $session = Session::where('session',$request->only('ss'))->pluck('session');

        if (empty($session)) {
            return response()->json([
                'code' => ErrorCode::API_ERR_INVALID_SESSION,
                'msg' => (new ErrorCode)->getMessage(ErrorCode::API_ERR_INVALID_SESSION)
            ]);

        }

        \Cache::put($sessionTable.$session,1,\Carbon\Carbon::now()->addDay(1));
        return $next($request);

    }
}
