<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCaseModel extends Model
{
    protected  $table = "stj_user_case";
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
