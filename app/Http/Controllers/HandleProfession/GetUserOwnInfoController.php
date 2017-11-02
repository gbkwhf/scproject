<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 15:22
 */

namespace App\Http\Controllers\HandleProfession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;


/**
 * Class GetUserOwnInfoController
 * @package App\Http\Controllers\HandleProfession
 * 该控制器主要完成用户个人中心的一些信息
 */
class GetUserOwnInfoController extends Controller{


  public function getInviteList(Request $request)
  {
      $validator = $this->setRules([
          'ss' => 'required|string',
      ])
          ->_validate($request->all());
      if (!$validator)  return $this->setStatusCode(9999)->respondWithError($this->message);

      $user_id = $this->getUserIdBySession($request->ss); //获取员工id

      $list = \DB::table('ys_member')->select('user_id','mobile','sex','name','address','image as thumbnail_image_url')->where('invite_id',$user_id)->get();

      $http = getenv('HTTP_REQUEST_URL'); //获取域名
       if(!empty($list)){
           foreach($list as $k=>$v){
               $list[$k]->thumbnail_image_url =  empty($v->thumbnail_image_url) ? "" : $http.'/api/gxsc/show-ico/'.'thu_'.$v->thumbnail_image_url;
           }
       }else{
           $list = [];
       }
      return $this->respond($this->format($list));
  }









}