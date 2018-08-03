@extends ('backend.layouts.app')

@section ('title', $type == 1? 'TULU | Pending Admins' : 'TULU | Pending Users')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        @if($type == 1)
            Admin Management
        @else
            User Management
        @endif
        <small>
            @if($type == 1)
                Pending Admins
            @else
                Pending Users
            @endif
        </small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                @if($type == 1)
                    Pending Admins
                @else
                    Pending Users
                @endif
            </h3>

            <div class="box-tools pull-right">
                @if($type == 1)
                    @include('backend.access.includes.partials.admin-header-buttons')
                @else
                    @include('backend.access.includes.partials.user-header-buttons')
                @endif
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                            <th>Admin Type</th>
                            <th>Organization</th>
                            <th>{{ trans('labels.backend.access.users.table.created') }}</th>
                            <th>{{ trans('labels.backend.access.users.table.last_updated') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}

    <script>
        $(function () {
            var type = "<?php echo $type?>";

            if(type == 1)
            {
                $('#users-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.access.user.get") }}',
                        type: 'post',
                        data: {admin: type, status: 2, approve:0, trashed: false},
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'email', name: '{{config('access.users_table')}}.email'},
                        {data: 'first_name', name: '{{config('access.users_table')}}.first_name'},
                        {data: 'last_name', name: '{{config('access.users_table')}}.last_name'},
                        {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                        {data: 'type', name: 'type', sortable: true},
                        {data: 'organization', name: 'organization', sortable: true},
                        {data: 'created_at', name: '{{config('access.users_table')}}.created_at'},
                        {data: 'updated_at', name: '{{config('access.users_table')}}.updated_at'},
                        {data: 'actions', name: 'actions', searchable: false, sortable: false}
                    ],
                    order: [[5, "asc"]],
                    searchDelay: 500
                });
            }
            else
            {
                $('#users-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.access.user.get") }}',
                        type: 'post',
                        data: {admin: type, status: 2, approve:0, trashed: false},
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'email', name: '{{config('access.users_table')}}.email'},
                        {data: 'first_name', name: '{{config('access.users_table')}}.first_name'},
                        {data: 'last_name', name: '{{config('access.users_table')}}.last_name'},
                        {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                        {data: 'type', name: 'type', sortable: false, visible: false},
                        {data: 'organization', name: 'organization', sortable: true},
                        {data: 'created_at', name: '{{config('access.users_table')}}.created_at'},
                        {data: 'updated_at', name: '{{config('access.users_table')}}.updated_at'},
                        {data: 'actions', name: 'actions', searchable: false, sortable: false}
                    ],
                    order: [[5, "asc"]],
                    searchDelay: 500
                });
            }
        });
    </script>
@endsection
