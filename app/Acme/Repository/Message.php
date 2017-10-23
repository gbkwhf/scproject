<?php

namespace App\Repository;


class Message
{

    private static $message =[
        '9999' => '参数错误',
        '9998' => '系统错误'
    ];


    public static function getMessage($code = 1)
    {
        return self::$message[$code];
    }

}