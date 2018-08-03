<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.alert.curalert.index', 'Current Alert', [], ['class' => 'btn btn-warning btn-sm']) }}
    {{ link_to_route('admin.alert.curalert.create', 'Create Alert', [], ['class' => 'btn btn-info btn-sm']) }}
    {{ link_to_route('admin.alert.curalert.update', 'Update Alert', [], ['class' => 'btn btn-success btn-sm']) }}
    {{ link_to_route('admin.alert.curalert.dismiss', 'Dismiss Alert', [], ['class' => 'btn btn-primary btn-sm']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Alert Management <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.alert.curalert.index', 'Current Alert') }}</li>
            <li>{{ link_to_route('admin.alert.curalert.create', 'Create Alert') }}</li>
            <li>{{ link_to_route('admin.alert.curalert.update', 'Update Alert') }}</li>
            <li>{{ link_to_route('admin.alert.curalert.dismiss', 'Dismiss Alert') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>