<div class="pull-right mb-10 hidden-sm hidden-xs">
    @if($group == null)
        {{ link_to_route('admin.group.create', 'Create Organization', [], ['class' => 'btn btn-primary btn-sm']) }}
        {{ link_to_route('admin.group.assignshow', 'Input Org ID#', [], ['class' => 'btn btn-success btn-sm']) }}
    @else
        {{ link_to_route('admin.group.edit', 'Edit Organization', ['group' => $group->id], ['class' => 'btn btn-success btn-sm']) }}
    @endif
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            All Groups <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            @if($group == null)
                <li>{{ link_to_route('admin.group.create', 'Create Organization') }}</li>
                {{ link_to_route('admin.group.assignshow', 'Input Org ID#') }}
            @else
                <li>{{ link_to_route('admin.group.edit', 'Edit Organization', ['group' => $group->id]) }}</li>
            @endif
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>