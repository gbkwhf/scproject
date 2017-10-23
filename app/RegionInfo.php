<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employees;
use Illuminate\Contracts\Cache\Store;
use DB;

class RegionInfo extends Model
{
    protected $table = 'com_sic_region_info';    
    protected $guarded = [];
    public $timestamps = false;
    

//     public function getImageAttribute($val)
//     {
    	 
//     	return $val ? url("image").'?image='.$val : '';
//     }
        



    
}
