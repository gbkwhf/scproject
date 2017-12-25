<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
<style>
.sidebar-menu li > a > .pull-right-container {
    margin-top: -7px;
    position: absolute;
    right: 10px;
    top: 50%;
}
</style>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
                <?php
                //获取当前管理员权限  
                	$power=\App\AdminRoleModel::where('id',Auth::user()->role)->first();
                	$power_arr=explode(',',$power->power);

                 	$route=explode('/',Request::path());
                 	$route1=count($route)>1?$route[1]:'';
                 	$route2=count($route)>2?$route[2]:'';
                 	$current_route=trim($route[0]);
                 	$current_route_2=trim($route[0].'/'.$route1,'/');                 	
                    $type ='';
                ?>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li @if (in_array($current_route,['admin'])) class="active" @endif @if (!in_array(1,$power_arr)) style="display:none"  @endif  ><a href="{{ url('/admin') }}"><i class='fa fa-dashboard'></i> <span>数据统计</span></a></li>
            <li @if (in_array($current_route,['memberlist','memberedit'])) class="active" @endif  @if (!in_array(2,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberlist') }}"><i class='fa fa-table'></i> <span>会员列表</span></a></li>
            <li @if (in_array($current_route,['supplierlist','supplieradd','supplieredit'])) class="active" @endif @if (!in_array(3,$power_arr)) style="display:none"  @endif   ><a href="{{ url('supplierlist') }}"><i class="fa fa-edit"></i><span>供应商列表</span></a></li>
            <li @if (in_array($current_route,['agencylist','agencyadd','agencyedit'])) class="active" @endif @if (!in_array(4,$power_arr)) style="display:none"  @endif   ><a href="{{ url('agencylist') }}"><i class="fa fa-edit"></i><span>经销商列表</span></a></li>
            <li @if (in_array($current_route,['goodslist','goods'])) class="active" @endif @if (!in_array(5,$power_arr)) style="display:none"  @endif   ><a href="{{ url('goodslist') }}"><i class="fa fa-edit"></i><span>商品列表</span></a></li>
            <li @if (in_array($current_route_2,['manage/orderlist','manage/orderdetial'])) class="active" @endif @if (!in_array(6,$power_arr)) style="display:none"  @endif   ><a href="{{ url('manage/orderlist') }}"><i class="fa fa-edit"></i><span>订单列表</span></a></li>
            <li @if (in_array($current_route_2,['manage/sendmemberbalance'])) class="active" @endif @if (!in_array(7,$power_arr)) style="display:none"  @endif   ><a href="{{ url('manage/sendmemberbalance') }}"><i class="fa fa-edit"></i><span>会员返现</span></a></li>
            <li @if (in_array($current_route_2,['manage/membercashlist'])) class="active" @endif @if (!in_array(8,$power_arr)) style="display:none"  @endif   ><a href="{{ url('manage/membercashlist') }}"><i class="fa fa-edit"></i><span>会员提现</span></a></li>
            <li @if (in_array($current_route_2,['manage/suppliercashlist','manage/suppliercashedit'])) class="active" @endif @if (!in_array(9,$power_arr)) style="display:none"  @endif   ><a href="{{ url('manage/suppliercashlist') }}"><i class="fa fa-edit"></i><span>供应商提现</span></a></li>
            <li @if (in_array($current_route_2,['manage/joinsupplier','manage/joinsupplierdetial'])) class="active" @endif @if (!in_array(10,$power_arr)) style="display:none"  @endif   ><a href="{{ url('manage/joinsupplier') }}"><i class="fa fa-edit"></i><span>供应商加盟</span></a></li>
            <li @if (in_array($current_route_2,['manage/managepassword'])) class="active" @endif @if (!in_array(11,$power_arr)) style="display:none"  @endif   ><a href="{{ url('manage/managepassword') }}"><i class="fa fa-edit"></i><span>修改密码</span></a></li>
            <li @if (in_array($current_route_2,['member/cashbacklist'])) class="active" @endif @if (!in_array(12,$power_arr)) style="display:none"  @endif   ><a href="{{ url('member/cashbacklist') }}"><i class="fa fa-edit"></i><span>返现列表</span></a></li>

            

            
        	<!-- 供应商功能 -->	    
			<li @if (in_array($current_route_2,['supplier/orderlist','supplier/orderdetial'])) class="active" @endif @if (!in_array(30,$power_arr)) style="display:none"  @endif   ><a href="{{ url('supplier/orderlist') }}"><i class="fa fa-edit"></i><span>订单列表</span></a></li>
			<li @if (in_array($current_route_2,['supplier/supplierbillslist','supplier/suppliercashadd'])) class="active" @endif @if (!in_array(30,$power_arr)) style="display:none"  @endif   ><a href="{{ url('supplier/supplierbillslist') }}"><i class="fa fa-edit"></i><span>提现记录</span></a></li>            
        	<!-- 经销商功能 -->	    
			<li @if (in_array($current_route_2,['agency/orderlist','agency/orderdetial'])) class="active" @endif @if (!in_array(50,$power_arr)) style="display:none"  @endif   ><a href="{{ url('agency/orderlist') }}"><i class="fa fa-edit"></i><span>订单列表</span></a></li>
			<li @if (in_array($current_route_2,['agency/setemployee'])) class="active" @endif @if (!in_array(51,$power_arr)) style="display:none"  @endif   ><a href="{{ url('agency/setemployee') }}"><i class="fa fa-edit"></i><span>员工管理</span></a></li>            
            <li @if (in_array($current_route_2,['agency/membercashlist'])) class="active" @endif @if (!in_array(52,$power_arr)) style="display:none"  @endif   ><a href="{{ url('agency/membercashlist') }}"><i class="fa fa-edit"></i><span>提现流水</span></a></li>
            
            
        </ul><!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
</aside>
