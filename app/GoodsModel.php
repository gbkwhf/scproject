<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsModel extends Model
{
	use SoftDeletes;
    protected  $table = "ys_goods";
    protected  $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    
   
}
