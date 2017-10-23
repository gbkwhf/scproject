<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected  $table = "stj_order";
    protected  $primaryKey = 'id';
    public $timestamps = false;

    public function getImageAttribute($val)
    {

        return $val ? url("image").'?image='.$val : '';
    }
}
