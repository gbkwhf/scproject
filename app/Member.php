<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employees;
use Illuminate\Contracts\Cache\Store;
use DB;

class Member extends Model
{
    protected $table = 'stj_member';    
    protected $guarded = [];


     public function getImageAttribute($val)
    {
    	 
    	return $val ? url("image").'?image='.$val : '';
    }
        



    
}
