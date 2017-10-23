<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CooperationApplyModel extends Model
{
    protected  $table = "stj_cooperation_apply";
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
