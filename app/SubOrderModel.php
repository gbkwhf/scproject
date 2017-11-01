<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubOrderModel extends Model
{
    protected  $table = "ys_sub_order";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
