<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employees;
use Illuminate\Contracts\Cache\Store;
use DB;

class HospitalModel extends Model
{
    protected $table = 'stj_hospital';    
    protected $guarded = [];
    public $timestamps = false;
    

     public function getLogoAttribute($val)
     {
    	 
     	return $val ? url("image").'?image='.$val : '';
     }




    
}
