<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AddedServiceOptionModel extends Model
{
    use SoftDeletes;
    protected  $table = "stj_added_service_option";
    protected  $primaryKey = 'id';
    public $timestamps = false;
    protected $dates = ['deleted_at'];
}
