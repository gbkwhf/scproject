<?php

/**
 * Class TestCase
 */
class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://182.254.209.88:80/api/v1';

    protected $version=[
        'os_type'=>'android',
        'version'=>'1.0.0'
    ];


    /**随机返回一条用户session
     * @return mixed
     */
    private function getUserSession()
    {
        return \App\Session::orderBy(\DB::raw('RAND()'))->first();
    }




    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function createUrl($url,$session=true)
    {
        return $this->baseUrl.'/'.$url.'?'.$this->getVersionString($session);
    }


    protected function getVersionString($session)
    {
        $arr=[
            'os_type'=>'android',
            'version'=>'1.0.0'
        ];
        if($session==true){
            $arr=array_merge($arr,['ss'=>$this->getUserSession()->session]);
        }

        return http_build_query($arr);
    }

    /**
     * api测试通过标志
     */
    protected function successRemarkJson()
    {
        $this->seeJson([
            'code' => "1"
        ]);

        return $this;

    }


}
