<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthCodeModel extends Model
{
    protected  $table = "stj_auth_code";
    protected  $primaryKey = 'id';
    public $timestamps = false;



}
