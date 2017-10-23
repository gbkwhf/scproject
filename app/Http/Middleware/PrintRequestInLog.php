<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandlerTest;

class PrintRequestInLog
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
        $monolog =\ Log::getMonolog();

        $monolog=new $monolog('printRequest');

        $monolog->pushHandler(new \Monolog\Handler\StreamHandler(base_path().'/storage/logs/request-'.date('Ymd'), $monolog::INFO));

        if(env('APP_DEBUG',false)==true) {


            $print = [];

            //完整url
            $print['fullUrl'] = $request->fullUrl();

            //请求地址
            $print['url'] = $request->url();

            //请求方法
            $print['method'] = $request->getMethod();

            $print['post'] = $_POST;

            $print['get'] = $_GET;

            //header头
            $print['header'] = $request->headers->all();

            $monolog->addInfo("+++++++++++++++++++++the request start++++++++++++++++++++++\n",
               $print);


            //打印结果
            $response = $next($request);

            $result=json_decode($response->getContent(),true);

            if(!is_null($result)){
                $monolog->addInfo(
                    "\n+++++++++++++++++++++the response +++++++++++++++++++++", $result);

            }
            return $response;

        }

        return $next($request);
    }
}
