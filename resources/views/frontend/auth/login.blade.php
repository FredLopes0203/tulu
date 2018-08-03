
@extends('frontend.layouts.app')

@section('title', app_name() . ' | Login')

@section('after-styles')
    {{ Html::style("js/iCheck/blue.css") }}
    {{ Html::style("css/login.css") }}
@endsection

@section('content')

<body class="hold-transition login-page" style="">
    <div class="login-box" >
        <!-- /.login-logo -->
        <div class="login-box-body loginboxextended">
            <div style="text-align: center; margin-bottom: 10px; margin-top: 10px">
                <a href="/"><img src="<?php echo (asset('/img/logo.png')); ?>" style="height: 50px" alt=""></a>
            </div>

            <p class="login-box-msg logintext">Login</p>
                {{ Form::open(['route' => 'frontend.auth.login.post']) }}
                <div class="form-group has-feedback">
                    {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>



                <div class="row" style="padding-left: 15px; padding-right: 15px; margin-top: -15px; margin-bottom: 10px">

                        <div class="checkbox icheck">
                            <label class="logintext" style="padding-left: 0px !important;">
                                {{ Form::checkbox('remember') }} {{ trans('labels.frontend.auth.remember_me') }}
                            </label>
                        </div>

                    <!-- /.col -->

                    <!-- /.col -->
                </div>

            <div class="row" style="padding-left: 15px; padding-right: 15px;">
                <div style="width: 48%; float: left;">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>
                <div style="width: 48%; float:right;">
                    <a href="/register" class="btn btn-danger btn-block btn-flat">Register</a>
                    {{--<button type="submit" class="">{{ trans('labels.frontend.auth.login_button') }}</button>--}}
                </div>
            </div>
            {{Form::close()}}
            <div class="row" style="padding-right: 15px; padding-left: 15px; padding-top: 15px">
                {{ link_to_route('frontend.auth.password.reset', trans('labels.frontend.passwords.forgot_password')) }}
            </div>



        </div>
        <!-- /.login-box-body -->
    </div>
</body>

@endsection

@section('after-scripts')
    {{ Html::script("js/login.js") }}
@endsection
