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
            <h3 class="box-title">Current Alert</h3>

            <div class="box-tools pull-right">
                @include('backend.alert.includes.partials.alert-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->


        <div class="panel-body">

            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#curalert" aria-controls="curalert" role="tab" data-toggle="tab"><i class="fa fa-exclamation-triangle"></i></a>
                    </li>

                    <li role="presentation">
                        <a href="#oklist" aria-controls="oklist" role="tab" data-toggle="tab"><i class="fa fa-thumbs-up"></i></a>
                    </li>

                    <li role="presentation">
                        <a href="#notoklist" aria-controls="notoklist" role="tab" data-toggle="tab"><i class="fa fa-thumbs-down"></i></a>
                    </li>

                    <li role="presentation">
                        <a href="#alllist" aria-controls="alllist" role="tab" data-toggle="tab"><i class="fa fa-bars"></i></a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane mt-30 active" id="curalert">
                        @include('backend.alert.tabs.list')
                    </div><!--tab panel profile-->

                    <div role="tabpanel" class="tab-pane mt-30" id="oklist">
                        @include('backend.alert.tabs.oklist')
                    </div><!--tab panel profile-->

                    <div role="tabpanel" class="tab-pane mt-30" id="notoklist">
                        @include('backend.alert.tabs.notoklist')
                    </div><!--tab panel profile-->

                    <div role="tabpanel" class="tab-pane mt-30" id="alllist">
                        @include('backend.alert.tabs.alllist')
                    </div><!--tab panel profile-->

                </div><!--tab content-->

            </div><!--tab panel-->

        </div><!--panel body-->
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
                $('#alert-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.alert.alerts.get") }}',
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

                $('#ok-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.alert.responses.get") }}',
                        data: {type: 1},
                        type: 'post',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'profileimage', name: 'profileimage', searchable: false, sortable: false},
                        {data: 'username', name: 'username', searchable: false, sortable: false},
                        {data: 'useremail', name: 'useremail', searchable: false, sortable: false},
                        {data: 'phonenumber', name: 'phonenumber', searchable: false, sortable: false},
                        {data: 'responsestr', name: 'responsestr', searchable: false, sortable: false},
                        {data: 'location', name: 'location', searchable: false, sortable: false},
                        {data: 'created_at', name: 'created_at', sortable:false},
                    ]
                });

                $('#notok-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.alert.responses.get") }}',
                        data: {type: 0},
                        type: 'post',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'profileimage', name: 'profileimage', searchable: false, sortable: false},
                        {data: 'username', name: 'username', searchable: false, sortable: false},
                        {data: 'useremail', name: 'useremail', searchable: false, sortable: false},
                        {data: 'phonenumber', name: 'phonenumber', searchable: false, sortable: false},
                        {data: 'responsestr', name: 'responsestr', searchable: false, sortable: false},
                        {data: 'location', name: 'location', searchable: false, sortable: false},
                        {data: 'created_at', name: 'created_at', sortable:false},
                    ]
                });

                var alltable = $('#all-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route("admin.alert.responses.get") }}',
                        data: {type: 2},
                        type: 'post',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'profileimage', name: 'profileimage', searchable: false, sortable: false},
                        {data: 'username', name: 'username', searchable: false, sortable: false},
                        {data: 'useremail', name: 'useremail', searchable: false, sortable: false},
                        {data: 'phonenumber', name: 'phonenumber', searchable: false, sortable: false},
                        {data: 'responsestr', name: 'responsestr', searchable: false, sortable: false},
                        {data: 'location', name: 'location', searchable: false, sortable: false},
                        {data: 'created_at', name: 'created_at', sortable:false},
                    ]
                });

                $('#all-table tbody').on('click', 'tr', function () {
                    var rowdata = alltable.row(this).data();
                    var userid = rowdata['userid'];
                    var domainUrl = document.location.origin;
                    console.log(userid)
                    if(rowdata['location'] != "Unknown")
                    {
                        var newurl = domainUrl + "/admin/alert/curalert/location/" + userid;
                        $(location).attr('href',newurl);
                    }
                } );
            });
        });
    </script>
@endsection