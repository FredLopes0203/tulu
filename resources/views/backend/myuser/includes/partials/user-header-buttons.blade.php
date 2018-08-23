<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.myuser.create', 'Create New User', [], ['class' => 'btn btn-info btn-sm']) }}
    {{ link_to_route('admin.myuser.index', 'Active Users', [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('admin.myuser.pending', 'Pending Users', [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('admin.myuser.deactivated', 'Deactivated Users', [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('admin.myuser.deleted', 'Deleted Users', [], ['class' => 'btn btn-danger btn-sm']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Admins <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.myuser.create', 'Create New User') }}</li>
            <li>{{ link_to_route('admin.myuser.index', 'Active Users') }}</li>
            <li class="divider"></li>
            <li>{{ link_to_route('admin.myuser.index', 'Pending Users') }}</li>
            <li>{{ link_to_route('admin.myuser.deactivated', 'Deactivated Users') }}</li>
            <li>{{ link_to_route('admin.myuser.deleted', 'Deleted Users') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>