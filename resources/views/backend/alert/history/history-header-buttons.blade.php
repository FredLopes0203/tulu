<div class="pull-right mb-10 hidden-sm hidden-xs">
    {{ link_to_route('admin.alert.history.index', 'Back to history', [], ['class' => 'btn btn-warning btn-sm']) }}
</div><!--pull right-->

<div class="pull-right mb-10 hidden-lg hidden-md">
    <div class="btn-group">
        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Alert Management <span class="caret"></span>
        </button>

        <ul class="dropdown-menu" role="menu">
            <li>{{ link_to_route('admin.alert.history.index', 'Back to history') }}</li>
        </ul>
    </div><!--btn group-->
</div><!--pull right-->

<div class="clearfix"></div>