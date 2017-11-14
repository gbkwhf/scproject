<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeModel extends Model
{
	use SoftDeletes;
    protected  $table = "ys_employee";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
    protected $dates = ['deleted_at'];
   
}
