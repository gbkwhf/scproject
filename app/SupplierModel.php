<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierModel extends Model
{
	use SoftDeletes;
    protected  $table = "ys_supplier";
    protected  $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
    protected $dates = ['deleted_at'];


}
