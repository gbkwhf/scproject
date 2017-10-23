<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employees;
use Illuminate\Contracts\Cache\Store;
use DB;

class Question extends Model
{
    protected $table = 'stj_question';    
    protected $guarded = [];
    public $timestamps = false;


//     public function getImageAttribute($val)
//     {
    	 
//     	return $val ? url("image").'?image='.$val : '';
//     }
        



    
}
