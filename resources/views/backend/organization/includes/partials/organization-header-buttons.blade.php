<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.organization.create', 'Create Organization', [], ['class' => 'btn btn-info btn-sm']) }}
    {{ link_to_route('admin.organization.index', 'Active Organizations', [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('admin.organization.pending', 'Pending Organizations', [], ['class' => 'btn btn-primary btn-sm']) }}
    {{ link_to_route('admin.organization.deactivated', 'Deactivated Organizations', [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('admin.organization.deleted', 'Deleted Organizations', [], ['class' => 'btn btn-danger btn-sm']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Organizations <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.organization.create', 'Create Organization') }}</li>
            <li>{{ link_to_route('admin.organization.index', 'Active Organizations') }}</li>
            <li>{{ link_to_route('admin.organization.pending', 'Pending Organizations') }}</li>
            <li>{{ link_to_route('admin.organization.deactivated', 'Deactivated Organizations') }}</li>
            <li>{{ link_to_route('admin.organization.deleted', 'Deleted Organizations') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>