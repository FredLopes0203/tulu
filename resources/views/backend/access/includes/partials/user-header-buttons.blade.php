<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.access.user.create', 'Create New User', [], ['class' => 'btn btn-info btn-sm']) }}
    {{ link_to_route('admin.access.user.index', 'Active Users', [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('admin.access.user.pending', 'Pending Users', [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('admin.access.user.deactivated', trans('menus.backend.access.users.deactivated'), [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('admin.access.user.deleted', trans('menus.backend.access.users.deleted'), [], ['class' => 'btn btn-danger btn-sm']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            {{ trans('menus.backend.access.users.main') }} <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.access.user.create', 'Create New User') }}</li>
            <li>{{ link_to_route('admin.access.user.index', 'Active Users') }}</li>
            <li class="divider"></li>
            <li>{{ link_to_route('admin.access.user.pending', 'Pending Users') }}</li>
            <li>{{ link_to_route('admin.access.user.deactivated', trans('menus.backend.access.users.deactivated')) }}</li>
            <li>{{ link_to_route('admin.access.user.deleted', trans('menus.backend.access.users.deleted')) }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>