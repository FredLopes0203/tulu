@extends ('backend.layouts.app')

@section ('title', 'Alert Management' . ' | ' . 'Update Alert')

@section('after-styles')
    {{ Html::style('css/category.css') }}
    {{ Html::style("js/iCheck/blue.css") }}
@endsection

@section('page-header')
    <h1>
        Alert Management
        <small>Update Alert</small>
    </h1>
@endsection

@section('content')
        {{ Form::open(['route' => 'admin.alert.curalert.store', 'id' => 'alertcreateform', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Alert Info</h3>

                <div class="box-tools pull-right">
                    @include('backend.alert.includes.partials.alert-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                {{Form::hidden('mainalert', $alert->id)}}

                <div class="form-group">
                    {{ Form::label('title', 'Alert Title', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('alerttitle', $alert->title, ['class' => 'form-control', 'readonly'=>true, 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Alert Title']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('content', 'Alert Content', ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::textarea('alertcontent', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => 'Alert Content']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->


                <div class="form-group">
                    {{ Form::label('', '', ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        <div style="display: inline-table; margin-bottom: 15px">{{ Form::checkbox('push', '1', $alert->push == 1, array('disabled')) }}<b style="margin-left: 10px; margin-right: 25px;">Push</b></div>
                        <div style="display: inline-table; margin-bottom: 15px">{{ Form::checkbox('text', '1', $alert->text == 1, array('disabled')) }}<b style="margin-left: 10px; margin-right: 25px;">Text</b></div>
                        <div style="display: inline-table; margin-bottom: 15px">{{ Form::checkbox('email', '1', $alert->email == 1, array('disabled')) }}<b style="margin-left: 10px; margin-right: 25px;">Email</b></div>
                        <div style="display: inline-table; margin-bottom: 15px">{{ Form::checkbox('response', '1', $alert->response == 1, array('disabled')) }}<b style="margin-left: 10px">Require OK/NOT OK Responses</b></div>
                    </div><!--col-lg-10-->
                </div>

                <div class="form-group" style="margin-top:50px;">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="pull-right">
                            {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-md', 'style' => 'width:100px !important;']) }}
                        </div><!--pull-right-->
                    </div><!--col-lg-10-->
                    {{--<div class="col-lg-2">--}}
                    {{--</div>--}}
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="pull-left">
                                {{ link_to_route('admin.alert.curalert.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-md', 'style' => 'width:100px !important;']) }}
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
    {{ Html::script("js/login.js") }}
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
