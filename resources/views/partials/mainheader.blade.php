<!-- Main Header -->
<header class="main-header">
                <?php
                //获取当前管理员权限  
 					if(Auth::user()->role==1 || Auth::user()->role==4 || Auth::user()->role==5 || Auth::user()->role==6){
 						$index='admin';
 					}elseif(Auth::user()->role==2){//经销商
 						$index='agencyadmin';
 					}elseif (Auth::user()->role==3){//供应商
 						$index='supplieradmin';
 					}
                ?>
    <!-- Logo -->
    <a href="{{ url($index) }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>双创共享</b>后台管理系统 </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                       
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header" style="height: 75px;">
                            <p>
                                {{ Auth::user()->name }}
                                <?php
                                    $role = Auth::user()->role;
                                    $adminrole = \App\AdminRoleModel::where('id',$role)->first();
                                    $role_name = $adminrole['role_name'];
                                ?>
                                <small>{{$role_name}}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        {{--<li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </li>--}}
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            {{--<div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>--}}
                            <div class="pull-right">
                                <a href="{{ url('/auth/logout') }}" class="btn btn-default btn-flat">退出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
               {{-- <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>--}}
            </ul>
        </div>
    </nav>
</header>