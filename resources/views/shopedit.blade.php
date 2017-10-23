<script src="{{ asset('/js/jquery.min.js') }}"></script>
@extends('app')

@section('htmlheader_title')
    Home
@endsection
    

@section('main-content')
         
 
            @if (session('message'))  
            
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> 错误!</h4>
                	{{ session('message') }}
              </div>
            @endif
            
<div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
             @if (isset($data) )
             	@section('contentheader_title','编辑门店')
             	<form class="form-horizontal" action="{{ url('shop/shopsave') }}" method="post"  enctype ="multipart/form-data">
             @else
             	@section('contentheader_title','添加门店')
            	<form class="form-horizontal" action="{{ url('shop/shopcreate') }}" method="post"  enctype ="multipart/form-data">
             @endif
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label" >门店名称</label>
                  <div class="col-sm-10">
                  	<input type="text" class="form-control" name="name" placeholder="Enter ..." value="{{ $data->name or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" >电话</label>
                  <div class="col-sm-10">
                    <input type="text"  name="phone"  placeholder="Enter ..." class="form-control" value="{{ $data->phone or '' }}">
                  </div>
                </div> 
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">是否自营</label>
                  <div class="col-sm-10">
                     <label><input type="radio" onclick="show_jms(1)" @if (isset($data) && $data->selfsupport == 1) checked="" @endif value="1"  name="selfsupport">是</label>
                     <label><input type="radio" onclick="show_jms(0)"   @if (isset($data) && $data->selfsupport == 0) checked=""@else  checked="" @endif  value="0"  name="selfsupport">否</label>
                  </div>
                </div>  
                <div class="form-group" id="select_jms" @if (isset($data) && $data->selfsupport == 1) style="display:none" @endif>
                  <label class="col-sm-2 control-label" for="inputEmail3">所属加盟商</label>
                  <div class="col-sm-3">	                  
	                      <select class="form-control" name="owner_id">
	                      <option>请选择加盟商</option>
	                      @foreach ($jms as $j)
	                      	<option @if (isset($data) && $data->owner_id == $j->user_id) selected="" @endif value="{{$j->user_id}}">{{$j->user_name}}_{{$j->mobile}}</option> 
	                      @endforeach 
	                  	  </select>	                 
                  </div>
                </div>   
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">服务剩余次数</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="balance" placeholder="Enter ..." value="{{ $data->balance or 0 }}">
                  </div>
                </div> 
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">价格</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="price" placeholder="Enter ..." value="{{ $data->price or '' }}">
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">所在城市</label>
                  <div class="col-sm-2 ">
                      <select class="form-control" id="province">
                      	<option>请选择省</option>
                       @foreach ($province as $p)  
                        	<option @if (isset($data) &&  $data->province == $p->id) selected="" @endif value="{{ $p->id }}">{{ $p->name }}</option>
                       @endforeach  
                  	  </select>
                  </div>
                  <div class="col-sm-2 ">
                      <select class="form-control" id="city" name="city">
                      @if (isset($data) &&  $data->city > 0) 
                       @foreach ($city as $c)  
                        	<option @if ($data->city == $c->id) selected="" @endif value="{{ $c->id }}">{{ $c->name }}</option>
                       @endforeach  
                      @else
                      	<option value="0">请选择市</option>                      
                      @endif                      	
                  	  </select>
                  </div>                  
                </div>                 
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">地址</label>
                  <div class="col-sm-3">
                   <div class="input-group">
                		<input type="text" class="form-control" id="address" placeholder="Enter ..." name="address" value="{{ $data->address or '' }}">
                    	<span class="input-group-btn">
                      	<button class="btn btn-info btn-flat" id="searchBtn" type="button">获取经纬度</button>
                    	</span>
              		</div>
                  </div>
                </div>                 
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">精度</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Enter ..." value="{{ $data->longitude or '' }}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">维度</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter ..." value="{{ $data->latitude or '' }}">
                  </div>
                </div>                                 
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">定位</label>
                  <div class="col-sm-10">
                     <div style="width:560px;height:240px;border:0px solid gray" id="container"></div>
                  </div>
                </div>  
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="inputEmail3">LOGO</label>
                  <div class="col-sm-10">
                     <input type="file" name="image">
                     	@if (isset($data) && $data->image!='')
                     		<a target="_blank" href="{{ $data->image }}"><img width="200" height="100" src="{{ $data->image }}" ></a>
                     	@endif
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
function show_jms(par){
		if(par==1){
			$('#select_jms').hide();
		}else{
			$('#select_jms').show();
		}
	}
$(document).ready(function(){	
	$('#province').change(function(){
		var province=$('#province').val();
		var url="{{ url('ajax/citylist') }}";	
		var str='';
		$.ajax({
			url: url,
			type: 'POST',
			data:{id:province},
			dataType: 'JSON',
			error: function () {  },
			success: function(data){
				str+='<option value="-1">所在市</option>';
				$.each(data,function(i,val){
					 str+="<option value="+val.id+">"+val.name+"</option>";
				})
				$('#city').html(str);
			}
		});
	 });
	
	});	                      
                      
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?ak=aMPu3ZNx7fY4R9TiYqOBnMKg&v=2.0&services=false"></script>

        <script type="text/javascript">
        $(document).ready(function(){	
        	var longitude="{{ $data->longitude or '' }}";
        	var latitude="{{ $data->latitude or '' }}"; 
        	if(longitude){ var lon=longitude}else{ var lon=108.953764};
        	if(latitude){var lat=latitude}else{ var lat=34.265509};
            //创建Map实例
            var map = new BMap.Map("container");
            var point = new BMap.Point(lon,lat);
            map.centerAndZoom(point,15);
            //添加鼠标滚动缩放
            map.enableScrollWheelZoom();
            
            //添加缩略图控件
            map.addControl(new BMap.OverviewMapControl({isOpen:false,anchor:BMAP_ANCHOR_BOTTOM_RIGHT}));
            //添加缩放平移控件
            map.addControl(new BMap.NavigationControl());
            //添加比例尺控件
            map.addControl(new BMap.ScaleControl());
            //添加地图类型控件
            //map.addControl(new BMap.MapTypeControl());
            
            //设置标注的图标
            var icon = new BMap.Icon("{{ asset('/images/icon.jpg') }}",new BMap.Size(50,50));
            //设置标注的经纬度
            var marker = new BMap.Marker(new BMap.Point(lon,lat),{icon:icon});
            //把标注添加到地图上
            map.addOverlay(marker);
            var content = "<table>";  
                content = content + "<tr><td> 编号：001</td></tr>";  
                content = content + "<tr><td> 地点：淄博市张店区1</td></tr>"; 
                content = content + "<tr><td> 时间：2014-09-26</td></tr>";  
                content += "</table>";
            var infowindow = new BMap.InfoWindow(content);
            marker.addEventListener("click",function(){
                //this.openInfoWindow(infowindow);
            });
            
            //点击地图，获取经纬度坐标
            map.addEventListener("click",function(e){
                $("#longitude").val(e.point.lng);
                $("#latitude").val(e.point.lat);
            });

            $('#searchBtn').click(function(){
	                var province=$("#province").find("option:selected").text();
	                var city=$("#city").find("option:selected").text();
	                var address=$("#address").val();
	                
	                var keyword=province+city+address;
	                
	                //alert(keyword);
	                //var keyword = document.getElementById("keyword").value;
	                
	                var local = new BMap.LocalSearch(map, {
	                renderOptions:{map: map}
	            });
	           		 local.search(keyword);
                });
            
        });	   
        </script>
