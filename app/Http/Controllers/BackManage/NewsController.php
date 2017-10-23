<?php

namespace App\Http\Controllers\BackManage;

use App\NewsModel;
use Acme\Exceptions\ValidationErrorException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;

class NewsController extends Controller
{
    //健康资讯列表
    public function NewsList(Request $request)
    {
        $data = NewsModel::orderBy('sort','asc')
            ->orderBy('created_at','desc');
        if($request->name !=''){
            $data->where('title','like','%'.$request->name.'%');
        }
        $paginate = $data->paginate(10);
        return view('news.newslist',['data'=>$paginate]);
    }
    //添加健康咨询
    public function Newadd()
    {
        return view('news.newadd');
    }
    //提交健康资讯
    public function Store(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'title'=> 'required',
            'sort'=> 'required',
            'content'=> 'required',
        ];
        $massage = [
            'title.required' =>'资讯标题不能为空',
            'sort.required' =>'资讯排序不能为空',
            'content.required' =>'资讯内容不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);

        if($validator->passes()){
            if ($request->hasFile('image')){//图片上传
                $up_res=uploadPic($request->file('image'));
                $file_name[]=$up_res;
                $image = $file_name['0'];
            }else{
                $image = '';
            }
            $params=array(
                'title'=>$request->title,
                'sort'=>$request->sort,
                'content'=>$request->content,
                'image'=>$image,
                'created_at'=>date('Y-m-d H:i:s',time()),
            );
            $res = NewsModel::insert($params);
            if($res){
                return redirect('newslist');
            }else{
                return back() -> with('errors','数据填充失败');
            }
        }else{
            return back() -> withErrors($validator);
        }

    }
    //编辑健康资讯
    public function Edit($id)
    {
        $data = NewsModel::where('id',$id)->first();
        return view('news.newadd',['data'=>$data]);
    }
    
    //编辑健康资讯保存
    public function Newsave(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'title'=> 'required',
            'sort'=> 'required',
            'editorValue'=> 'required',
        ];
        $massage = [
            'title.required' =>'资讯标题不能为空',
            'sort.required' =>'资讯排序不能为空',
            'editorValue.required' =>'资讯内容不能为空',
        ];
        $validator = \Validator::make($input,$rules,$massage);
        if($validator->passes()){
            if ($request->hasFile('image')){//图片上传
                $up_res=uploadPic($request->file('image'));
                $file_name[]=$up_res;
                $image = $file_name['0'];
            }else{
                if ($input['image_url'] == ''){
                    $image = '';
                }else{
                    $image_url =  explode("=",$input['image_url']);
                    $image = $image_url['1'];
                }
            }
            $input['image'] = $image;
            $id = $input['id'];
            $params=array(
                'title'=>$input['title'],
                'sort'=>$input['sort'],
                'content'=>$input['editorValue'],
                'image'=>$image,
            );
            $res = NewsModel::where('id',$id)->update($params);
            if($res === false){
                return back() -> with('errors','数据更新失败');

            }else{
                return redirect('newslist');
            }
        }else{
            return back() -> withErrors($validator);
        }
    }
    //删除健康资讯
    public function Newdel($id)
    {
        NewsModel::where('id',$id)->delete();
        return redirect('newslist');
    }
}
