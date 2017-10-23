<?php

namespace App\Http\Controllers\Baseuser;

use Acme\Exceptions\ValidationErrorException;

use App\HospitalModel;
use App\OrgClassManageModel;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;

class OrgDistributionController extends Controller
{
    //一二级分布列表
    public function Org_list(Request $request)
    {

        $validator = $this->setRules([
            'ss' => 'string'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $one_level=array(
            array('id'=>1,'name'=>'中国大陆'),
            array('id'=>2,'name'=>'大中华'),
            array('id'=>3,'name'=>'日本'),
            array('id'=>4,'name'=>'亚洲'),
            array('id'=>5,'name'=>'欧洲'),
            array('id'=>6,'name'=>'美洲'),
            array('id'=>7,'name'=>'澳洲'),
            array('id'=>8,'name'=>'其他'),
        );
        $org_list['one_level'] = $one_level; //一级分布数组
        for($i=0;$i<count($one_level);$i++){ //循环一级分布数组
            $two_level = OrgClassManageModel::select('id','name','sort','type')
                ->orderBy('sort','asc')
                ->where('type',$one_level[$i]['id'])
                ->get(); //根据一级分布的id，取出二级分布的内容
            for ($t=0;$t<count($two_level);$t++){ //循环二级数组
                $three_level = HospitalModel::select('id','name','content','logo','address','phone','sort','grade','class_id','city')
                    ->orderBy('sort','asc')
                    ->where('class_id',$two_level[$t]['id'])
                    ->get();

                $two_level_list = array_merge($two_level[$t]['original'],array('count'=>count($three_level))); //二级内容
                $two_level_original[$i][] = $two_level_list; //将二级内容循环写入数组
            }
            $org_list['one_level'][$i]['count'] = count($two_level); //将二级数组长度参数写入一级数组
            $org_list['one_level'][$i]['two_level']=$two_level_original[$i]; //将二级数组写入一级数组
        }
        return $this->respond($this->format($org_list));
    }

    //医院列表
    public function Therr_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'class_id' => 'string',//父id
            'class_type' => 'string',//医院类型
            'city' => 'string',//城市id
            'type' => 'string',//类型 【1通过父id获取医院 2通过医院类别和地区获取医院】
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        if ($request->type == 1){ //通过医院类别 和城市id查询医院列表
            $level = \App\HospitalModel::select('*');
            if ($request->class_type){ //医院类别
                $level->where('class','like','%'.$request->class_type.'%');
            }
            if ($request->city){ //城市id
                $level->where('city','like','%'.$request->city.'%');
            }
            $three_level = $level->orderBy('sort','asc')->orderBy('id','desc')->get();
        }else{//通过父id查询医院列表
            $three_level = \App\HospitalModel::select('*')
                ->where('class_id',$request->class_id)->orderBy('sort','asc')->orderBy('id','desc')->get()->toArray();
            
            for ($i=0;$i<count($three_level);$i++){
            	$three_level[$i]['content']=formatContent($three_level[$i]['content']);
                $three_level_original[] = $three_level[$i];
            }
            if (isset($three_level_original)){
                $three_level = $three_level_original;
            }else{
                $data['msg'] = '该区域下没有医院';
                return $this->respond($data);
            }
        }
        if($three_level){
            return $this->respond($this->format($three_level));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
        /*return $this->respond($this->format($three_level));*/
    }

    //单独列表
    public function Tow_list(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'type' => 'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $res = OrgClassManageModel::select('id','name','sort')->orderBy('sort','asc')
            ->where('type',$request->type)->get();
        $res[] = array(
            'count'=>count($res)
        );
        return $this->respond($this->format($res));
    }
}
