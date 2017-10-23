<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersModel extends Model
{
    protected  $table = "stj_orders";
    protected  $primaryKey = 'order_id';
    public $timestamps = false;
}
