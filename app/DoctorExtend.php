<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorExtend extends Model
{
    protected $connection = 'user_center';
    protected $table = 'sky_doctor_info';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id','hospital','recollection_id','duty_id','authentication',
        'sky_name','state','comm','style','refers','refers_now','people_num',
        'people_now','service_days','promise_num','pro_price','k','honour','summary',
        'doc_video_url','skills'
    ];


    public function session()
    {
        return $this->belongsTo(\App\Session::class);
    }

}
