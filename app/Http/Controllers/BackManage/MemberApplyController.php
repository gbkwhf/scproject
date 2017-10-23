<?php

namespace App\Http\Controllers\BackManage;

use App\CooperationApplyModel;
use App\Member;
use App\MemberApplyModel;
use App\MessageModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class MemberApplyController extends Controller
{
    //申请会员列表
    public function MemberApplyList(Request $request)
    {
    	//dd(Auth::user());
        $memberapply = MemberApplyModel::join('stj_member','stj_member.user_id','=','stj_member_apply.user_id')
            ->select('stj_member_apply.*','stj_member.name as membername','stj_member.mobile as membermobile','stj_member_apply.name as username')
            ->orderBy('stj_member_apply.created_at','desc');
        if ($request->grade != ''){
            $memberapply->where('stj_member_apply.grade',$request->grade);
        }
        if ($request->state != ''){
            $memberapply->where('stj_member_apply.state',$request->state);
        }
        if ($request->start != ''){
            $start = $request->start.' 00:00:00';
            $memberapply->where('stj_member_apply.created_at','>=',$start);
        }
        if ($request->end != ''){
            $end = $request->end.' 59:59:59';
            $memberapply->where('stj_member_apply.created_at','<=',$end);
        }
        if ($request->mobile != ''){
            $memberapply->where('stj_member_apply.mobile','like','%'.$request->mobile.'%');
        }
        $data = $memberapply->paginate(10);

        $gradeArr=array(
            '1'=>'普通会员',
            '2'=>'红卡会员',
            '3'=>'金卡会员',
            '4'=>'白金卡会员',
            '5'=>'黑卡会员'
        );
        foreach ($data as $val){

            if ($val['code'] != ''){
               $name = Member::where('mobile',$val['code'])->first();
                if ($name){
                    $val['code'] = $name['name'].'/'.$val['code'];
                }
            }else{
                $val['code'] = '无';
            }
            $val['grade']=isset($val->grade)?$gradeArr[$val->grade]:'非会员';
        }
        return view('memberapply/memberapplylist',['data'=>$data]);
    }

    public function MemberApplyEdit(Request $request)
    {
        $gradeArr=array(
            '1'=>'普通会员',
            '2'=>'红卡会员',
            '3'=>'金卡会员',
            '4'=>'白金卡会员',
            '5'=>'黑卡会员'
        );
        $data = MemberApplyModel::join('stj_member','stj_member.user_id','=','stj_member_apply.user_id')
            ->select('stj_member_apply.*','stj_member.name as username','stj_member.mobile as membermobile')
            ->where('id',$request->id)->first();
        $data['grade'] = $gradeArr[$data['grade']];
        return  view('memberapply/memberapplyedit',['data'=>$data]);

    }

    public function Save(Request $request)
    {
        $grades= array(
            '1'=>'普通会员',
            '2'=>'红卡会员',
            '3'=>'金卡会员',
            '4'=>'白金卡会员',
            '5'=>'黑卡会员',
        );
        $res = MemberApplyModel::where('id',$request->id)->update(['state'=>$request->state]);
        $user_id = MemberApplyModel::where('id',$request->id)->first();

        $res_member = Member::where('user_id',$user_id['user_id']);
        if ($request->state == 1){  //编辑为已处理
            $res_member->update(['grade'=>$user_id['grade']]);
        }else{ //编辑为未处理
            $res_member->update(['grade'=>'1']);
        }
        $cooperationapply = MemberApplyModel::where('id',$request->id)->first();
        $grade = $grades[$cooperationapply['grade']];
        $user_id = $cooperationapply['user_id'];
        $date = array(
            'user_id'=>$user_id,
            'created_at'=>date('Y-m-d H:i:s',time()),
            'content'=>'恭喜您已成为'.$grade,
        );
        MessageModel::insert($date);
        return redirect('memberapply/list');
    }
}
