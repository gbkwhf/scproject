<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    protected  $table = "stj_message";
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
