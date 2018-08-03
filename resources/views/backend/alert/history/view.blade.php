@extends ('backend.layouts.app')

@section ('title', 'TULU | Alert Management')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
@endsection

@section('page-header')
    <h1>
        Alert Management
    </h1>
@endsection

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Alert History</h3>
            <small>{{$alert->title}}</small>

            <div class="box-tools pull-right">
                @include('backend.alert.history.history-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->


        <div class="box-body">
            <div class="table-responsive">
                <table id="alert-table" class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th style="width: 20%">Title</th>
                        <th style="width: 35%">Content</th>
                        <th style="width: 10%">Type</th>
                        <th style="width: 20%">Author</th>
                        <th style="width: 15%">{{ trans('labels.backend.access.users.table.created') }}</th>
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

            var alertID = "<?php echo $alert->id?>";
            $(function() {
                $('#alert-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.alert.history.detail.get") }}',
                        data: {alertid: alertID},
                        type: 'post',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'title', name: 'title', searchable: false, sortable: false},
                        {data: 'content', name: 'content', searchable: false, sortable: false},
                        {data: 'type_label', name: 'type_label', searchable: false, sortable: false},
                        {data: 'creator_name', name: 'creator_name', searchable: false, sortable: false},
                        {data: 'created_at', name: 'created_at', sortable:false},
                    ]
                });
            });
        });
    </script>
@endsection