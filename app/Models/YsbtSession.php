<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorSession extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = false;
    protected $table = 'doctor_session_info';
    protected $fillable = [
        'user_id','client_type','session','mid','push_service_type','mec_ip',
        'mec_port','lps_ip','lps_port','last_get_session_date','session_hash'
    ];
    protected $hidden = [];
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['user_id'];

    public function base()
    {
        return $this->hasOne(\App\Base::class,'user_id');
    }

    public function version()
    {
        return $this->hasOne(\App\Version::class,'user_id');
    }

    public function userExtend()
    {
        return $this->hasOne(\App\UserExtend::class,'user_id');
    }

    public function doctorExtend()
    {
        return $this->hasOne(\App\DoctorExtend::class,'user_id');
    }

    public function addressBooks()
    {
        return $this->hasMany(\App\AddressBook::class,'user_id');
    }

    /***
     * 流动资金
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function flowCash()
    {
        return $this->hasOne(\App\FlowCash::class,'user_id');
    }

    /***
     * 用户签约表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userDiscount()
    {
        return $this->hasOne(\App\UserDiscountInfo::class,'user_id');
    }


    public function createSession($uid)
    {
        $this->clearSession($uid);

        return getRandomID (32);

    }

    public function clearSession($uid)
    {
        $session = self::where('user_id',$uid)->first(['session']);

        if (\Cache::has($session.'_'.$uid)) {

            \Cache::forget($session.'_'.$uid);

        }

    }

    public function addSession($uid,$param)
    {
        $session = self::where('user_id',$uid)->first();

        if($session)  \Cache::forget($this->table.$session->session);

        return $session ? $session->update($param) : (new ManageSession())->create($param);

    }

}
