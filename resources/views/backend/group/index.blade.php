@extends ('backend.layouts.app')

@section ('title', 'TULU | Organization Management')

@section('after-styles')
    {{ Html::style('css/category.css') }}
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
            <h3 class="box-title">Organization Info</h3>


            <div class="box-tools pull-right">
                @include('backend.group.includes.partials.group-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->


            @if($group == null)
                <div class="box-body">
                    <h3 style="font-size: 15px; text-align: center;">No Organization</h3>
                </div>
            @else
                <div class="box-body">
                    <div class="groupinfodiv">
                        <label class="col-lg-2 control-label groupinfotitlelabel">Organization Status : </label>
                        <div class="col-lg-10">
                            @if($group->approved == 0)
                                <label class="control-label groupinfovaluelabel" style="color: orange">
                                    Pending approval
                                </label>
                            @else
                                @if($group->status == 0)
                                    <label class="control-label groupinfovaluelabel" style="color: red">
                                        Inactive
                                    </label>
                                @else
                                    <label class="control-label groupinfovaluelabel" style="color: green">
                                        Active
                                    </label>
                                @endif
                            @endif
                        </div>
                    </div><!--form control-->

                    <div class="groupinfodiv">
                        <label class="col-lg-2 control-label groupinfotitlelabel">Organization ID # : </label>
                        <div class="col-lg-10">
                            <label class="control-label groupinfovaluelabel">
                                @if($group->approved == 0)
                                    Will be assigend after approved.
                                @else
                                    {{$group->groupid}}
                                @endif
                            </label>
                        </div>
                    </div><!--form control-->

                    <div class="groupinfodiv">
                            <label class="col-lg-2 control-label groupinfotitlelabel">Organization Name : </label>
                        <div class="col-lg-10">
                            <label class="control-label groupinfovaluelabel">{{$group->name}}</label>
                        </div>
                    </div><!--form control-->

                    <div class="groupinfodiv">
                            <label class="col-lg-2 control-label groupinfotitlelabel">Organization Address : </label>
                        <div class="col-lg-10">
                            <label class="control-label groupinfovaluelabel">{{$group->full_address}}</label>
                        </div>
                    </div><!--form control-->

                    <div style="margin-top:10px;">
                        <label class="col-lg-2 control-label groupinfotitlelabel">Organization Logo : </label>
                        <div class="col-lg-10">
                            <div class="main-img-preview">
                                <img class="thumbnail logo-img-preview" src="{{asset($group->logo)}}" title="Preview Logo">
                            </div>
                        </div>
                    </div><!--form control-->
                </div><!-- /.box-body -->
            @endif
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
        });
    </script>
@endsection