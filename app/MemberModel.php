<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employees;
use Illuminate\Contracts\Cache\Store;
use DB;

class MemberModel extends Model
{
    protected $table = 'ys_member';    
    protected $guarded = [];


//      public function getImageAttribute($val)
//     {
    	 
//     	return $val ? url("image").'?image='.$val : '';
//     }
        



    
}
