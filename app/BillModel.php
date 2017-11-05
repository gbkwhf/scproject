<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillModel extends Model
{
    protected  $table = "ys_bills";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
