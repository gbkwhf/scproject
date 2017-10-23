<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVersionInfoModel extends Model
{
    protected  $table = "stj_user_version_info";
    protected  $primaryKey = 'user_id';
    public $timestamps = false;
}
