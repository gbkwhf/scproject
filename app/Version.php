<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{

    protected $connection = 'user_center';
    protected $table = 'user_version_info';
   // protected $hidden = ['thumbnail_image_url','source_image_url'];
    protected $primaryKey = 'user_id';


    public function getThumbnailImageUrlAttribute($value)
    {
        return $value ? env('USER_CENTER_HTTP_REQUEST_URL').$value : "";
    }

    public function getSourceImageUrlAttribute($value)
    {
        return $value ? env('USER_CENTER_HTTP_REQUEST_URL').$value : "";
    }

    public function session()
    {
        return $this->belongsTo(\App\Session::class);
    }

}
