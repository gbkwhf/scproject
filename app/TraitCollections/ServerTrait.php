<?php

namespace App\TraitCollections;

trait ServerTrait
{
    protected function getDispatchServerInfo($appName = 'stj')
    {

        if(\Cache::has(strtoupper('app_server_info'))){
            return  \Cache::get(strtoupper('app_server_info'));
        }


		if($appName=='stj'){
        	$mecData = \DB::table('stj_dim_server_config')
        	->where('category', 1)
        	->where('is_enable', 1)->first(['local_ip', 'local_port']);
        	
        	$lpsSql = <<<T_ECHO
SELECT ip,port
FROM stj_dim_server_config
WHERE category = 2 AND is_enable = 1 AND  (max_loading - curr_loading) > 0
ORDER BY max_loading - curr_loading DESC
T_ECHO;
        	
        	 
        	$lpsData = \DB::select($lpsSql)[0];
        }


        $httpData = \DB::table('stj_dim_server_config')
            ->where('category', 3)
            ->where('is_enable', 1)->first(['ip', 'port']);

        $newsData = \DB::table('stj_dim_server_config')
            ->where('category', 4)
            ->where('is_enable', 1)->first(['ip', 'port']);

        $fileData = \DB::table('stj_dim_server_config')
            ->where('category', 5)
            ->where('is_enable', 1)->first(['ip', 'port']);

        if ($mecData && $lpsData && $httpData && $newsData && $fileData) {

            $serverInfo = [
                "mec_ip" => $mecData->local_ip,
                "mec_port" => $mecData->local_port,
                "lps_ip" => $lpsData->ip,
                "lps_port" => $lpsData->port,
                "api_ip" => $httpData->ip,
                "api_port" => $httpData->port,
                "news_ip" => $newsData->ip,
                "news_port" => $newsData->port,
                "file_ip" => $fileData->ip,
                "file_port" => $fileData->port
            ];

            \Cache::add(strtoupper('app_server_info'),$serverInfo,30);

            return $serverInfo;

        }

        return false;
    }
}