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
                 	$current_route=trim($route[0].'/'.$route1,'/');
                    $type ='';

                        if ($route[0] == 'memberservice'){ //尊享服务
                            if (count($route)>2){
                                $current_route=trim($route[0].'/'.$route1.'/'.$route2,'/');
                                if ($route1 == 'serviceedit' || $route1 == 'optionlist' || $route1 == 'optionadd' || $route1 == 'hospitalcalss'){ //编辑\可选项列表\添加可选项\医院类别
                                    $AddedService = \App\AddedServiceModel::where('id',$route2)->first();
                                    $type = $AddedService['type'];
                                }elseif ($route1 == 'optionedit'){//编辑可选项
                                    $AddedServiceoption = \App\AddedServiceOptionModel::where('id',$route2)->first();
                                    $AddedService = \App\AddedServiceModel::where('id',$AddedServiceoption['added_id'])->first();
                                    $type = $AddedService['type'];
                                }
                            }else{
                                $current_route=trim($route[0].'/'.$route1,'/');
                            }
                        }
                ?>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li @if (in_array($current_route,['admin','member/memberedit'])) class="active" @endif @if (!in_array(1,$power_arr)) style="display:none"  @endif  ><a href="{{ url('/admin') }}"><i class='fa fa-dashboard'></i> <span>数据统计</span></a></li>
            <li @if (in_array($current_route,['memberlist','member/memberedit'])) class="active" @endif  @if (!in_array(2,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberlist') }}"><i class='fa fa-table'></i> <span>会员列表</span></a></li>
            <li @if (in_array($current_route,['memberapply/list','memberapply/edit'])) class="active" @endif @if (!in_array(3,$power_arr)) style="display:none"  @endif   ><a href="{{ url('memberapply/list') }}"><i class="fa fa-edit"></i><span>会员申请</span></a></li>
            <li @if (in_array($current_route,['questionlist','question/questionedit'])) class="active" @endif @if (!in_array(4,$power_arr)) style="display:none"  @endif   ><a href="{{ url('questionlist') }}"><i class="fa fa-th"></i><span>用户咨询</span></a></li>
            <li @if (in_array($current_route,['hzapplylist','hzapply/hzapplyedit'])) class="active" @endif @if (!in_array(5,$power_arr)) style="display:none"  @endif   ><a href="{{ url('hzapplylist') }}"><i class="fa fa-laptop"></i><span>合作申请</span></a></li>
            <li @if (!in_array(6,$power_arr) && !in_array(7,$power_arr)) style="display:none"  @endif @if (in_array($current_route,['goodslist']) || in_array($current_route,['orderlist']) || in_array($current_route,['goods/goodsadd']) || in_array($current_route,['goods/edit']) || in_array($current_route,['order/deliver']) || in_array($current_route,['order/orderinfo'])) class='treeview active'@else class="treeview" @endif >
                <a href="#">
                    <i class="fa fa-folder"></i> <span>健康福利</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu menu-open" @if (in_array($current_route,['goodslist','goods/goodsadd','goods/edit']) || in_array($current_route,['orderlist','order/deliver','order/orderinfo'])) style="display: block;" @else style="display: none;" @endif >
                    <li @if (in_array($current_route,['goodslist','goods/goodsadd','goods/edit'])) class="active" @endif  @if (!in_array(6,$power_arr)) style="display:none"  @endif   ><a href="{{ url('goodslist') }}"><i class="fa fa-circle-o text-red"></i>商品</a></li>
                    <li @if (in_array($current_route,['orderlist','order/deliver','order/orderinfo'])) class="active" @endif @if (!in_array(7,$power_arr)) style="display:none"  @endif   ><a href="{{ url('orderlist') }}"><i class="fa fa-circle-o text-yellow"></i><span>订单</span></a></li>
                </ul>
            </li>
            <li @if (in_array($current_route,['orders/orderslist','orders/feedback','orders/ordersinfo'])) class="active" @endif @if (!in_array(8,$power_arr)) style="display:none"  @endif   ><a href="{{ url('orders/orderslist') }}"><i class="fa fa-pie-chart"></i><span>需求订单</span></a></li>
            <li @if (in_array($current_route,['orgclasslist','orgsecondclasslist/'.$route1,'orgsecondclass/classedit','orglist/'.$route1,'org/orgadd','orgsecondclass/classadd'])) class="active" @endif @if (!in_array(9,$power_arr)) style="display:none"  @endif   ><a href="{{ url('orgclasslist') }}"><i class="fa fa-files-o"></i><span>医疗机构</span></a></li>
            <li @if (!in_array(10,$power_arr) && !in_array(11,$power_arr)&& !in_array(12,$power_arr)&& !in_array(13,$power_arr)&& !in_array(14,$power_arr)&& !in_array(15,$power_arr)&& !in_array(16,$power_arr)) style="display:none"  @endif
            @if (in_array($current_route,['memberservice/1','memberservice/2','memberservice/3','memberservice/4','memberservice/5','memberservice/6','memberservice/7','memberservice/serviceadd/'.$route2,'memberservice/serviceedit/'.$route2]) || $type == 1  || $type == 2  || $type == 3  || $type == 4  || $type == 5  || $type == 6  || $type == 7  ) class='treeview active'@else class="treeview" @endif>
                <a href="#">
                    <i class="fa fa-folder"></i> <span>尊享服务</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu menu-open" @if (in_array($current_route,['memberservice/1','memberservice/2','memberservice/3','memberservice/4','memberservice/5','memberservice/6','memberservice/7','memberservice/serviceadd/1','memberservice/serviceadd/2','memberservice/serviceadd/3','memberservice/serviceadd/4','memberservice/serviceadd/5','memberservice/serviceadd/7','memberservice/serviceadd/7'])  || $type == 1  || $type == 2  || $type == 3  || $type == 4  || $type == 5  || $type == 6  || $type == 7 ) style="display: block;" @else style="display: none;" @endif>
                    <li @if (in_array($current_route,['memberservice/7','memberservice/serviceadd/7']) || $type == 7) class="active" @endif @if (!in_array(10,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberservice',['id'=>7]) }}"><i class="fa fa-circle-o text-red"></i>医疗服务</a></li>
                    <li @if (in_array($current_route,['memberservice/1','memberservice/serviceadd/1']) || $type == 1) class="active" @endif @if (!in_array(11,$power_arr)) style="display:none"  @endif   ><a href="{{ url('memberservice',['id'=>1]) }}"><i class="fa fa-circle-o text-yellow"></i><span>海外医疗</span></a></li>
                    <li @if (in_array($current_route,['memberservice/2','memberservice/serviceadd/2']) || $type == 2) class="active" @endif @if (!in_array(12,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberservice',['id'=>2]) }}"><i class="fa fa-circle-o text-aqua"></i><span>健康管理</span></a></li>
                    <li @if (in_array($current_route,['memberservice/3','memberservice/serviceadd/3']) || $type == 3) class="active" @endif @if (!in_array(13,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberservice',['id'=>3]) }}"><i class="fa fa-circle-o text-blue"></i><span>产业医生</span></a></li>
                    <li @if (in_array($current_route,['memberservice/4','memberservice/serviceadd/4']) || $type == 4) class="active" @endif @if (!in_array(14,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberservice',['id'=>4]) }}"><i class="fa fa-circle-o text-light-blue"></i><span>增值服务</span></a></li>
                    <li @if (in_array($current_route,['memberservice/5','memberservice/serviceadd/5']) || $type == 5) class="active" @endif @if (!in_array(15,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberservice',['id'=>5]) }}"><i class="fa fa-circle-o text-green"></i><span>个性定制</span></a></li>
                    <li @if (in_array($current_route,['memberservice/6','memberservice/serviceadd/6']) || $type == 6) class="active" @endif @if (!in_array(16,$power_arr)) style="display:none"  @endif  ><a href="{{ url('memberservice',['id'=>6]) }}"><i class="fa fa-circle-o text-teal"></i><span>保险经纪</span></a></li>
                </ul>
            </li>
            <li @if (in_array($current_route,['newslist','news/newadd','news/edit'])) class="active" @endif @if (!in_array(17,$power_arr)) style="display:none"  @endif   ><a href="{{ url('newslist') }}"><i class="fa fa-book"></i><span>健康资讯</span></a></li>
            

            <li @if (in_array($current_route,['managelist','manage/manageadd','manage/manageedit'])) class="active" @endif @if (!in_array(18,$power_arr)) style="display:none"  @endif   ><a href="{{ url('managelist') }}"><i class='fa fa-flag-o'></i> <span>管理员</span></a></li>
        </ul><!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
</aside>
