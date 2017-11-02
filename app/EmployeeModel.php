<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    protected  $table = "ys_employee";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
