<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderGoodsModel extends Model
{
    protected  $table = "ys_order_goods";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
