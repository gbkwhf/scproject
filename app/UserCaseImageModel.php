<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCaseImageModel extends Model
{
    protected  $table = "stj_user_case_image";
    protected  $primaryKey = 'id';
    public $timestamps = false;

    public function getUrlAttribute($val)
    {

        return $val ? url("image").'?image='.$val : '';
    }
}
