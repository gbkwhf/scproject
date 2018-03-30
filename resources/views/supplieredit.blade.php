<script src="{{ asset('/js/jquery.min.js') }}"></script>

@extends('app')

@section('htmlheader_title')
    Home
@endsection
    

@section('main-content')


    @if(count($errors))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> 错误!</h4>
            @if(is_object($errors))
                @foreach($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
            @else
                <p>{{$errors}}</p>
            @endif
        </div>
    @endif
            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
             @if (isset($data) )
             	@section('contentheader_title','编辑供应商')
             	<form class="form-horizontal" action="{{ url('suppliersave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加供应商')
            	<form class="form-horizontal" action="{{ url('suppliercreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >名称</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >手机号</label>
                  <div class="col-sm-10">
                    <input type="text"  name="mobile"  placeholder="Enter ..." class="form-control" value="{{ $data->mobile or '' }}">
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">密码</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="password" placeholder="Enter ..." value="">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >持卡人</label>
                  <div class="col-sm-10">
                    <input type="text"  name="real_name"  placeholder="Enter ..." class="form-control" value="{{ $data->real_name or '' }}">
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >银行名</label>
                  <div class="col-sm-10">
                    <input type="text"  name="bank_name"  placeholder="Enter ..." class="form-control" value="{{ $data->bank_name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >支行地址</label>
                  <div class="col-sm-10">
                    <input type="text"  name="bank_address"  placeholder="Enter ..." class="form-control" value="{{ $data->bank_address or '' }}">
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" >卡号</label>
                  <div class="col-sm-10">
                    <input type="text"  name="bank_num"  placeholder="Enter ..." class="form-control" value="{{ $data->bank_num or '' }}">
                  </div>
                </div>                                                                    
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">状态</label>
                  <div class="col-sm-10">
                      <select name="state"  class="form-control  " style="float:left;width:100px">
                          <option @if(!isset($data) || $data->state==1) selected   @endif value=1>正常</option>
                          <option @if(isset($data) && $data->state==0) selected   @endif value=0>禁用</option>
                      </select>
                  </div>
                </div>


                @if(!empty($data) && !empty($data->class_id)))
                    <div class="form-group">
                          <label class="col-sm-2 control-label" for="inputEmail3">门店分类</label>
                          <div class="col-sm-10">
                              <select name="tmp"  class="form-control"  id="prov"  onchange="class_ld()"   style="float:left;width:100px">
                                  <option value="-1">--选择--</option>
                                  @if(!empty($class_info))
                                      @foreach($class_info as $k=>$v)
                                          @if($v->first_id == 0 && $v->id == $first_info->id)

                                              <option value="{{$v->id}}"  selected >{{$v->name}}</option>
                                          @elseif( $v->first_id == 0)
                                             <option value="{{$v->id}}">{{$v->name}}</option>
                                          @endif
                                      @endforeach
                                   @endif

                              </select>
                                  <select name="class_id"  class="form-control"  id="tmp_class"  style="float:left;width:100px">
                                       <option value="{{$second_info->id}}">{{$second_info->name}}</option>
                                  </select>
                          </div>
                      </div>


                      <div class="form-group">
                          <label class="col-sm-2 control-label" for="inputEmail3">Logo图片</label>
                          <div class="col-sm-10">
                              <input type="file" name="image[]" value="" >
                              <span style="color:red;font-size:12px;">图片尺寸：长400*宽400</span>
                              <br>

                              {{--<a target="_blank" href=""><img  width="200" height="100" src="" ></a>--}}
                              <a target="_blank" href="{{ url($data->logo)}}"><img  width="200" height="100" src="{{ url($data->logo)}}" ></a>

                          </div>
                      </div>


                  @elseif(!empty($data)))

                          <div class="form-group">
                              <label class="col-sm-2 control-label" for="inputEmail3">门店分类</label>
                              <div class="col-sm-10">
                                  <select name="tmp"  class="form-control"  id="prov"  onchange="class_ld()"   style="float:left;width:100px">
                                      <option value="-1">--选择--</option>
                                      @if(!empty($class_info))
                                          @foreach($class_info as $k=>$v)
                                              @if($v->first_id == 0)
                                                  <option value="{{$v->id}}">{{$v->name}}</option>
                                              @endif
                                          @endforeach
                                      @endif

                                  </select>
                                  <select name="class_id"  class="form-control"  id="tmp_class"  style="float:left;width:100px">
                                      <option value="-1">--选择--</option>
                                  </select>
                              </div>
                          </div>


                          <div class="form-group">
                              <label class="col-sm-2 control-label" for="inputEmail3">Logo图片</label>
                              <div class="col-sm-10">
                                  <input type="file" name="image[]" value="" >
                                  <span style="color:red;font-size:12px;">图片尺寸：长400*宽400</span>
                                  <br>

                                  {{--<a target="_blank" href=""><img  width="200" height="100" src="" ></a>--}}
                                  <a target="_blank" href="{{ url($data->logo)}}"><img  width="200" height="100" src="{{ url($data->logo)}}" ></a>

                              </div>
                          </div>


                  @else

                      <div class="form-group">
                          <label class="col-sm-2 control-label" for="inputEmail3">门店分类</label>
                          <div class="col-sm-10">
                              <select name="tmp"  class="form-control"  id="prov"  onchange="class_ld()"   style="float:left;width:100px">
                                  <option value="-1">--选择--</option>
                                  @if(!empty($class_info))
                                      @foreach($class_info as $k=>$v)
                                          @if($v->first_id == 0)
                                              <option value="{{$v->id}}">{{$v->name}}</option>
                                          @endif
                                      @endforeach
                                  @endif

                              </select>
                              <select name="class_id"  class="form-control"  id="tmp_class"  style="float:left;width:100px">
                                  <option value="-1">--选择--</option>
                              </select>
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="col-sm-2 control-label" for="inputEmail3">Logo图片</label>
                          <div class="col-sm-10">
                              <input type="file" name="image[]" value="" >
                              <span style="color:red;font-size:12px;">图片尺寸：长400*宽400</span>
                              <br>

                              {{--<a target="_blank" href=""><img  width="200" height="100" src="" ></a>--}}
                              <a target="_blank" href=""><img  width="200" height="100" src="" ></a>

                          </div>
                      </div>


                  @endif

                  <div class="form-group">
                      <label class="col-sm-2 control-label" >包邮金额</label>
                      <div class="col-sm-10">
                          <input type="text"  name="free_shipping"  placeholder="Enter ..." class="form-control" value="{{ $data->free_shipping or '' }}">
                      </div>
                  </div>


              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type="hidden" name="id" value="{{ $data->id or '' }} ">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />                
                <button class="btn btn-info pull-right" type="submit">提交</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>

@endsection
<script>


    function class_ld(){


        var tmp_arr = "{{$arr}}";

        tmp_arr = JSON.parse(decodeURIComponent(tmp_arr));

        var sel = document.getElementById('prov');

        var opt = '';
        if (sel.value == '-1') {
            document.getElementById('tmp_class').innerHTML = opt;
            return;   // 如果显示的是选择，则城市的栏目里什么也没有返回
        }


        for(i=0;i<=tmp_arr.length-1;i++){
            for(j=0;j<=tmp_arr[i].length-1;j++){
                if(sel.value == tmp_arr[i][j].split("+")[0]){
                    opt = opt + '<option value="'+ tmp_arr[i][j].split("+")[1] +'">' + tmp_arr[i][j].split("+")[2] + '</option>';//+i+指的是连接符
                    document.getElementById('tmp_class').innerHTML = opt;
                }
            }
        }
    }

</script>