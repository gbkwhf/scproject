<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseOrderModel extends Model
{
    protected  $table = "ys_base_order";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
