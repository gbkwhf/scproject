<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRoleModel extends Model
{
    protected  $table = "ys_admin_role";
    protected  $primaryKey = 'id';
    public $timestamps = false;


}
