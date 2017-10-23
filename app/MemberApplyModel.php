<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberApplyModel extends Model
{
    protected  $table = "stj_member_apply";
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
