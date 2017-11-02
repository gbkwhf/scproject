<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsImageModel extends Model
{
    protected  $table = "ys_goods_image";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
