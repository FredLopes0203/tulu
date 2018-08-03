<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ access()->user()->picture }}" class="img-circle" alt="User Image" />
            </div><!--pull-left-->
            <div class="pull-left info">
                <p>{{ access()->user()->full_name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('strings.backend.general.status.online') }}</a>
            </div><!--pull-left-->
        </div><!--user-panel-->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">{{ trans('menus.backend.sidebar.general') }}</li>

            <li class="{{ active_class(Active::checkUriPattern('admin/dashboard')) }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>{{ trans('menus.backend.sidebar.dashboard') }}</span>
                </a>
            </li>

            <li class="header">{{ trans('menus.backend.sidebar.system') }}</li>

            @role(2)

                <li class="{{ active_class(Active::checkUriPattern('admin/group*')) }} treeview">
                    <a href="{{ route('admin.group.index') }}">
                        <i class="fa fa-university"></i>
                        <span>My Organization</span>
                    </a>
                </li>

                @if(access()->user()->isinitial == 1)
                    <li class="{{ active_class(Active::checkUriPattern('admin/subadmin*')) }} treeview">
                        <a href="{{ route('admin.subadmin.index') }}">
                            <i class="fa fa-user-o"></i>
                            <span>Sub Admin Management</span>
                        </a>
                    </li>
                @endif

                <li class="{{ active_class(Active::checkUriPattern('admin/myuser*')) }} treeview">
                    <a href="{{ route('admin.myuser.index') }}">
                        <i class="fa fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>


                <li class="{{ active_class(Active::checkUriPattern('admin/alert/*')) }} treeview">
                    <a href="#">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span>Alert Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu {{ active_class(Active::checkUriPattern('admin/alert/*'), 'menu-open') }}" style="display: none; {{ active_class(Active::checkUriPattern('admin/alert/*'), 'display: block;') }}">
                        <li class="{{ active_class(Active::checkUriPattern('admin/alert/curalert*')) }}">
                            <a href="{{ route('admin.alert.curalert.index') }}">
                                <i class="fa fa-circle-o"></i>
                                <span>Current Alert</span>
                            </a>
                        </li>

                        <li class="{{ active_class(Active::checkUriPattern('admin/alert/history*')) }}">
                            <a href="{{ route('admin.alert.history.index') }}">
                            {{--<a href="#">--}}
                                <i class="fa fa-circle-o"></i>
                                <span>Alert History</span>
                            </a>
                        </li>

                    </ul>
                </li>

            @endauth


            @role(1)
                <li class="{{ active_class(Active::checkUriPattern('admin/organization*')) }} treeview">
                    <a href="{{ route('admin.organization.index') }}">
                        <i class="fa fa-university"></i>
                        <span>Organization Management</span>
                    </a>
                </li>

                <li class="{{ active_class(Active::checkUriPattern('admin/access/manager*')) }} treeview">
                    <a href="{{ route('admin.access.manager.index') }}">
                        <i class="fa fa-user-o"></i>
                        <span>Admin Management</span>
                    </a>
                </li>

                <li class="{{ active_class(Active::checkUriPattern('admin/access/user*')) }} treeview">
                    <a href="{{ route('admin.access.user.index') }}">
                        <i class="fa fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
            @endauth
        </ul><!-- /.sidebar-menu -->
    </section><!-- /.sidebar -->
</aside>