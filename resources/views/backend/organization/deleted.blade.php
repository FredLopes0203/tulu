@extends ('backend.layouts.app')

@section ('title', 'TULU | Organization Management')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        Organization Management
    </h1>
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Deleted Organization List</h3>

            <div class="box-tools pull-right">
                @include('backend.organization.includes.partials.organization-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->


        <div class="box-body">
            <div class="table-responsive">
                <table id="organization-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th style="width: 20%">Organization Name</th>
                        <th style="width: 15%">Org ID#</th>
                        <th style="width: 20%">Address</th>
                        <th style="width: 15%">{{ trans('labels.backend.access.users.table.created') }}</th>
                        <th style="width: 15%">{{ trans('labels.backend.access.users.table.last_updated') }}</th>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $(function() {
                $('#organization-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.organizations.get") }}',
                        type: 'post',
                        data: {status: false, approved: false, trashed: true},
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name', searchable:true, sortable:true},
                        {data: 'groupid', name: 'groupid', searchable: true, sortable: true},
                        {data: 'full_address', name: 'full_address', searchable:true, sortable:true},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'actions', name: 'actions', searchable: false, sortable: false}
                    ],
                    order: [[0, "asc"]]
                });
            });
        });
    </script>
@endsection