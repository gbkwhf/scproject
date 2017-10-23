<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employees;
use Illuminate\Contracts\Cache\Store;
use DB;

class OrgClassManageModel extends Model
{
    protected $table = 'stj_orgclass_manage';    
    protected $guarded = [];
    public $timestamps = false;
    

//     public function getImageAttribute($val)
//     {
    	 
//     	return $val ? url("image").'?image='.$val : '';
//     }
        



    
}
