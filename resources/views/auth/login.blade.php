@extends('auth.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('admin') }}"><b>双创共享后台管理系统</b></a>{{----}}
        </div><!-- /.login-logo -->

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="login-box-body">
    <p class="login-box-msg"></p>
    <form action="{{ url('/auth/login') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="用户名" name="name"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>        
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="密码" name="password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
		  <div class="col-xs-7"><input type="text" name="captcha" class="form-control " placeholder="验证码" ></div>
          <div class="col-xs-5" style=""><a class="col-sm-6"  onclick="javascript:re_captcha();" ><img src="{{ URL('captcha/1') }}"  alt="验证码" title="刷新图片" width="100" height="34" id="c2c98f0de5a04167a9e427d883690ff6" border="0"></a></div>
        </div>          


<script>  
  function re_captcha() {
    $url = "{{ URL('/captcha') }}";
        $url = $url + "/" + Math.random();
        document.getElementById('c2c98f0de5a04167a9e427d883690ff6').src=$url;
  }
</script>        
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
					<select name="role_type" class="form-control ">
						<option value="1" >经销商</option>
						<option value="2" >供应商</option>
						<option value="3" >系统管理</option>
					</select>
                </div>
            </div><!-- /.col -->

            <div class="col-xs-4">
                <br>
                <button type="submit" class="btn btn-primary btn-block btn-flat">登陆</button>
            </div><!-- /.col -->
        </div>
    </form>

</div><!-- /.login-box-body -->

</div><!-- /.login-box -->

    @include('auth.scripts')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
</body>

@endsection
