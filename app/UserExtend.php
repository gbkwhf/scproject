<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExtend extends Model
{
    protected $connection = 'user_center';
    protected $table = 'sky_user_extend_info';
    protected $primaryKey = 'user_id';
    protected $dates = ['birthday'];
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','birthday','birth_place','live_province_id',
        'live_place','blood_id','sex_id','skills','lifeclock'
    ];


    public function session()
    {
        return $this->belongsTo(\App\Session::class);
    }

}
