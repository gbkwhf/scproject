<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyModel extends Model
{
	use SoftDeletes;
    protected  $table = "ys_agency";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    protected $dates = ['deleted_at'];


}
