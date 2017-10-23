<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\HospitalModel\UserView;

class Base extends Model
{

    protected $connection = 'user_center';
    protected $table = 'user_base_info';
    protected $hidden = ['password','algorithm','reg_source'];
    protected $primaryKey = 'user_id';

    protected $fillable = ['user_name'];

    public function session()
    {
        return $this->belongsTo(\App\Session::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Order::class,'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(\App\ClinicBooking::class,'user_id');
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
    
    //获取用户信息(姓名，年龄，性别，头像地址，)
    static function getUserInfoById($uid){
    	//$uid=25035841;
    	$sexArr=array(
    			1=>'男',
    			2=>'女',
    			0=>'未选择',
    	);
    	$userInfo=\App\Base::where('user_id',$uid)->with('version')->with('userExtend')->first()->toArray();
    
    	$age=date('Y-m-d',time())-date('Y-m-d',strtotime($userInfo['user_extend']['birthday']));
    
    	$result['user_name']=$userInfo['user_name'];
    	$result['thumbnail_image_url']=$userInfo['version']['thumbnail_image_url'];
    	$result['sex']=$sexArr[$userInfo['user_extend']['sex_id']];
    	$result['age']=$age;
    	$result['uid']=$uid;
    
    	$discount=\App\UserDiscountInfo::where('user_id',$uid)->first();
    	$result['ktype']=empty($discount)?'0':1;
    	if($discount){//已经签约
    		$apply_info=\App\SkySysPartnerShipUserApply::where('user_id',$uid)->where('state',1)->first();
    		$clinic=\App\Clinic::where('id',$discount->clinic_id)->first()->toArray();
    		$result['clinic_id']=$clinic['id'];
    		$result['clinic_name']=$clinic['name'];
    		$result['logo_url']=$clinic['logo_url'];
    		$result['address']=$clinic['address'];    		
    		$result['province']=\App\RegionInfo::where('id',$clinic['province'])->select('name')->first()->name;
    		$result['city']=\App\RegionInfo::where('id',$clinic['city'])->select('name')->first()->name;
    		$result['area']=\App\RegionInfo::where('id',$clinic['area'])->select('name')->first()->name;
    		$result['end_time']=date('Y-m-d',strtotime('+1years',strtotime($apply_info->approval_time)));
    		$result['ktype']=$apply_info->apply_type;
    	}else{
    		$result['clinic_id']='';
    		$result['clinic_name']='';
    		$result['logo_url']='';
    		$result['address']='';
    		$result['province']='';
    		$result['city']='';
    		$result['area']='';
    		$result['end_time']='';
    		$result['ktype']=0;
    	}
    	$adviser=\App\UserHealthAdviserInfo::where('user_id',$uid)->first();
    	if($adviser){//健康代表信息
    		$adviser_info=UserView::where('user_id',$adviser->adviser_id)->select('thumbnail_image_url')->first();
    		$result['adviser_img']=$adviser_info->thumbnail_image_url;
    		$result['adviser_id']=$adviser->adviser_id;
    	}
    	$result['adviser_name']=empty($adviser)?'暂无健康代表':$adviser->adviserInfo->user_name;
    	//私人医生
    	$private=\App\UserDiscountInfo::where('user_id',$uid)->first();

    	if($private){
    		$doc_info=UserView::where('user_id',$private->doc_id)->first();
    		$result['doc_img']=$doc_info->thumbnail_image_url;
    		$result['doc_id']=$private->doc_id;    		
    	}
    	$result['doc_name']=empty($private)?'暂无私人医生':$doc_info->user_name;
    	return $result;
    }

}
