<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AddedServiceModel extends Model
{
    use SoftDeletes;
    protected  $table = "stj_added_service";
    protected  $primaryKey = 'id';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
