<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/public/backstage/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{_account['login_account']}}</p>
                <a href="javascript:;"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">主导航</li>
            <li class="active">
                {{link_to('index','
                <i class="fa fa-dashboard"></i> <span>概况</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
                ')}}
            </li>


            <li class="treeview">
                <a href="#" data-event='true'>
                    <i class="fa fa-cogs"></i>
                    <span>系统</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        {{link_to('role/index','<i class="fa fa-circle-o"></i>角色及权限')}}
                    </li>
                    <li>
                        {{link_to('account/index','<i class="fa fa-circle-o"></i>操作员')}}
                    </li>
                    <li>
                        {{link_to('crontab/index','<i class="fa fa-circle-o"></i>计划任务')}}
                    </li>
                    <li>
                        {{link_to('jobs/index','<i class="fa fa-circle-o"></i>mysql队列')}}
                    </li>
                    <li>{{link_to('oauth/index','<i class="fa fa-circle-o"></i>信任登陆')}}</li>
                    <li>
                        {{link_to('setting/index','<i class="fa fa-circle-o"></i>配置')}}
                    </li>

                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
