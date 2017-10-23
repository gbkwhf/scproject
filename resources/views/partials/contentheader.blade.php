<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        @yield('contentheader_title', 'Page Header here')
        <small>@yield('contentheader_description')</small>
    </h1>
    

    <ol class="breadcrumb">
        @section('brade_line')
        <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i>首页</a></li>
        @show
    </ol>{{----}}

    

</section>