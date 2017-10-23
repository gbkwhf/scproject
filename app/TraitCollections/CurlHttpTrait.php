<?php

namespace App\TraitCollections;

use Illuminate\Http\Request;

trait CurlHttpTrait
{

    public function curl($url,$data)
    {
        return $this->http_request($url, $data);
    }

    public function setParameters(Request $request, $appendData = [])
    {
        return array_merge($request->all(),$appendData);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $baseUrl = getenv('HTTP_REQUEST_URL');

        $this->url = $baseUrl ? $baseUrl.$url : $url;
    }

    private function http_request($url, $data = null)
    {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, TRUE);//目前版本不支持非安全上传

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $output = curl_exec($curl);

        curl_close($curl);

        return json_decode($output, true);

    }


}