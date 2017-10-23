<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpdateInfo extends Model
{
    protected $table = 'stj_updateinfo';
    protected $dates = ['upload_date'];
    public $timestamps = false;


    static function isUpdateVersion($os_type,$version)
    {


        $minVersion = \Cache::get('check_version'.$os_type,function()use($os_type){

            $version=self::where('os_type',$os_type)->lists('minimal_version')->first();

            if(!$version){
                return 0;
            }else{
                \Cache::put('check_version'.$os_type,$version,\Carbon\Carbon::now()->addDay(1));
                return $version;
            }
        });

        if(!$minVersion) return true;

        if($version >= $minVersion){
            return false;
        }else{
            return true;
        }

    }

}
