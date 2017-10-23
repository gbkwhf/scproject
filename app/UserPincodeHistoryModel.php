<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPincodeHistoryModel extends Model
{
    protected  $table = "user_pincode_history";
    protected  $primaryKey = 'user_id';
    public $timestamps = false;
}
