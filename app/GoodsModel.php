<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsModel extends Model
{
	use SoftDeletes;
    protected  $table = "stj_goods";
    protected  $primaryKey = 'id';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
    
    public function getImageAttribute($val)
    {
    
    	return $val ? url("image").'?image='.$val : '';
    }    
}
