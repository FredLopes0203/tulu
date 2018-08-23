<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.access.manager.create', 'Create New Admin', [], ['class' => 'btn btn-info btn-sm']) }}
    {{ link_to_route('admin.access.manager.index', 'Active Admins', [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('admin.access.manager.pending', 'Pending Admins', [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('admin.access.manager.deactivated', 'Deactivated Admins', [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('admin.access.manager.deleted', 'Deleted Admins', [], ['class' => 'btn btn-danger btn-sm']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Admin Management <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.access.manager.create', 'Create New Admin')}}</li>
            <li>{{ link_to_route('admin.access.manager.index', 'Active Admins') }}</li>
            <li class="divider"></li>
            <li>{{ link_to_route('admin.access.manager.pending', 'Pending Admins') }}</li>
            <li>{{ link_to_route('admin.access.manager.deactivated', 'Deactivated Admins') }}</li>
            <li>{{ link_to_route('admin.access.manager.deleted', 'Deleted Admins') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>