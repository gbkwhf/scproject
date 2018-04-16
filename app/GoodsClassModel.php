<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsClassModel extends Model
{
    protected  $table = "ys_goods_class";
    protected  $primaryKey = 'id';
    public $timestamps = false;
    
   
}
