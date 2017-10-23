<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    public function __construct(array $attributes=[])
    {
        parent::__construct($attributes);

        $this->table=self::$tableName;
    }

    public $timestamps = false;
    protected $table = 'stj_session_info';
    protected $fillable = [
        'user_id','client_type','session','mid','push_service_type','mec_ip',
        'mec_port','lps_ip','lps_port','last_get_session_date','session_hash'
    ];
    protected $hidden = [];
    protected $primaryKey = 'user_id';

    //真实表名为了动态修改
    static public $tableName='stj_session_info';

    //发信息表名
    static public $sendTableName='';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
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



    public function createSession($uid)
    {
        $this->clearSession($uid);

        return getRandomID (32);

    }

    public function clearSession($uid)
    {
        $session = self::where('user_id',$uid)->first(['session']);

        if (\Cache::has($session)) {
            \Cache::forget($this->table.$session);

        }

    }

    public function addSession($uid,$param)
    {
        $session = self::where('user_id',$uid)->first();

        return $session ? $session->update($param) : (new Session())->create($param);

    }

    

}
