@extends ('backend.layouts.app')

@section ('title', 'Organization Management' . ' | ' . 'Assign Organization')

@section('after-styles')
    {{ Html::style('css/category.css') }}
    {{ Html::style('js/select2/select2.min.css') }}
@endsection

@section('page-header')
    <h1>
        Organization Management
        <small>Assign Organization</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.group.assign', 'id' => 'groupassignform', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Input Organization ID#</h3>
            </div><!-- /.box-header -->

            <div class="box-body">


                <div class="form-group">
                    {{ Form::label('name', 'Organiation ID #', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('organizationid', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Org ID #']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group" style="margin-top:50px;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="pull-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success btn-md', 'style' => 'width:100px !important;']) }}
                        </div><!--pull-right-->
                    </div><!--col-lg-10-->

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="pull-left">
                            {{ link_to_route('admin.group.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
                        </div><!--pull-left-->
                    </div><!--col-lg-10-->
                </div><!--form control-->
            </div><!-- /.box-body -->
        </div><!--box-->

        {{--<div class="box box-info">--}}
            <div class="box-body">

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        {{--</div><!--box-->--}}

    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('js/backend/access/users/script.js') }}
    {{ Html::script("js/select2/select2.full.min.js") }}
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
