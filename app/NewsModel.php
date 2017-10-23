<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
    protected  $table = "stj_news";
    protected  $primaryKey = 'id';
    public $timestamps = false;
    
    public function getImageAttribute($val)
    {
    
    	return $val ? url("image").'?image='.$val : '';
    }
}
