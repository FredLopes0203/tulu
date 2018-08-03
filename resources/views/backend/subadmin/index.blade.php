@extends ('backend.layouts.app')

@section ('title', 'TULU | Subadmin Management')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        Subadmin Management
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Active Sub Admin List</h3>

            <div class="box-tools pull-right">
                @include('backend.subadmin.includes.partials.subadmin-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="admins-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th style="width: 20%">Email</th>
                            <th style="width: 15%">First Name</th>
                            <th style="width: 15%">Last Name</th>
                            <th style="width: 11%">Phone Number</th>
                            <th style="width: 12%">{{ trans('labels.backend.access.users.table.created') }}</th>
                            <th style="width: 12%">{{ trans('labels.backend.access.users.table.last_updated') }}</th>
                            <th style="width: 15%">{{ trans('labels.general.actions') }}</th>
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
            $('#admins-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route("admin.subadmin.get") }}',
                    type: 'post',
                    data: {status: 1, approved: 1, trashed: false},
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'email', name: 'email'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'phonenumber', name: 'phonenumber'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@endsection
