<?php
namespace App\Http\Controllers\Baseuser;
use App\Member;
use App\NewsModel;
use App\Question;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Acme\Exceptions\ValidationErrorException;
use Carbon\Carbon;
class NewsController extends Controller
{
    //用户提问
    public function newQuestion(Request $request){

        //参数效验
        $validator = $this->setRules([
            'ss' => 'string',
            'type'=>'integer',
            'name'=>'required',
            'mobile'=>'required',
            'content'=>'string'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        
        $params=array(
            'type'=>$request->type>0?$request->type:2,
            'content'=>$request->content,
            'name'=>$request->name,
            'mobile'=>$request->mobile,
            'created_at'=>date('Y-m-d H:i:s',time())
        );
        if($user_id){
        	$params['user_id']=$user_id;
        }
        $res=\App\Question::insert($params);
        if($res){
            return $this->respond($this->format('',true));
        }else{
            return $this->setStatusCode(9998)->respondWithError($this->message);
        }
    }
    //用户历史咨询
    public function questionList(Request $request){
        //参数效验
        $validator = $this->setRules([
            'ss' => 'required|string',
            'page'=>'string',
            'home'=>'string',
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $user_id = $this->getUserIdBySession($request->ss); //获取用户id
        $start=$request->page<=1?0:(($request->page)-1)*10;
        if (!$request->home){ //首页
            $res=\App\Question::where('user_id',$user_id)->orderBy('created_at','desc')->skip($start)->take(10)->get()->toArray();
            for ($i=0;$i<count($res);$i++){
                $res[$i]['state'];                
                $question_class=\App\QuestionClassModel::where('id',$res[$i]['type'])->first();
                $res[$i]['type'] = $question_class->name;
            }
        }else{
            $res_question=\App\Question::where('user_id',$user_id)->orderBy('created_at','desc')->get()->toArray();
            $res['list']=\App\Question::where('user_id',$user_id)->orderBy('created_at','desc')->skip($start)->take(10)->get()->toArray();
            $res['count'] = count($res_question);
            for ($i=0;$i<count($res['list']);$i++){
                $res['list'][$i]['state'];               
                $question_class=\App\QuestionClassModel::where('id',$res['list'][$i]['type'])->first();
                $res['list'][$i]['type'] = $question_class->name;
                
            }
        }

        return $this->respond($this->format($res));
    }

    //咨询详情页面
    public function questionInfo(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'required|string',
            'id'=>'required'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $res = Question::where('id',$request->id)->first();
        $res_user = User::where('id',$res['manage_id'])->first();
        $res['manage_name'] = $res_user['name'];
        if ($res['type'] == '1'){ //咨询类型
            $res['type'] = '付款相关';
        }else{
            $res['type'] = '会员相关';
        }
        if ($res['state'] == '0'){//咨询状态
            $res['state'] = '未处理';
        }else{
            $res['state'] = '已处理';
        }
        if ($res){
            return $this->respond($this->format($res));
        }else{
            $data['msg'] = '该咨询不存在';
            return $this->respond($data);
        }

    }

    //新闻资讯列表
    public function NewList(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'type'=>'integer',
            'home'=>'integer',
            'page'=>'integer'
        ])
            ->_validate($request->all());
        if (!$validator) throw new ValidationErrorException;
        $start=$request->page<=1?0:(($request->page)-1)*10;
		$new_data=array();
            $res = NewsModel::orderBy('sort','asc')->orderBy('created_at','desc');
                if ($request->home == '1'){
                    $res->take(3);
                }else{
                    $res->skip($start)
                        ->take(10);
                }
        $new_data['list'] = $res->get();
        $new_data['count'] =NewsModel::count() ;
		
        return $this->respond($this->format($new_data));
    }

    //新闻资讯详情页
    public function NewInfo(Request $request)
    {
        $validator = $this->setRules([
            'ss' => 'string',
            'id'=>'required'
        ])
            ->_validate($request->all());

        if (!$validator) throw new ValidationErrorException;
        $res = NewsModel::select('title','content','sort','type','created_at','image')
            ->where('id',$request->get('id'))
            ->first();
        $res['content']=formatContent($res['content']);
        
        return $this->respond($this->format($res));

    }
    //获取咨询类型或合作类型
    public function getQuestionType(Request $request)
    {
    	$validator = $this->setRules([
    			'type' => 'required',
    			])
    			->_validate($request->all());
    	if (!$validator) throw new ValidationErrorException;
    	$res = \App\QuestionClassModel::where('type',$request->type)->orderBy('id','asc')->select('id','name')->get()->toArray();
    	return $this->respond($this->format($res));
    }    
    //获取医院科室
    public function getRecollection(Request $request)
    {
    	$res = \App\RecollectionCodeModel::orderBy('id','asc')->select('id','name')->get()->toArray();
    	return $this->respond($this->format($res));
    }
}