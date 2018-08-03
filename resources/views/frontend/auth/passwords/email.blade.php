@extends('frontend.layouts.app')

@section('title', app_name() . ' | Reset Password')

@section('after-styles')
    {{ Html::style("css/login.css") }}
@endsection

@section('content')
    <body class="login-page" style="background-image: url('../img/forgot.png')">

    <div id="app">

        <div class="login-box">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="login-box-body forgotpasswordextened">
                <div style="text-align: center; margin-bottom: 10px; margin-top: 10px">
                    <a href="/"><img src="<?php echo (asset('/img/logo.png')); ?>" style="height: 50px" alt=""></a>
                </div>

                <p class="login-box-msg registertext">{{ trans('labels.frontend.passwords.reset_password_box_title') }}</p>

                {{ Form::open(['route' => 'frontend.auth.password.email.post']) }}

                <div class="form-group has-feedback">
                    {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="row" style="padding-left: 15px; padding-right: 15px;">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('labels.frontend.passwords.send_password_reset_link_button') }}</button>
                </div>
                {{ Form::close() }}
            </div><!-- /.login-box-body -->

        </div><!-- /.login-box -->
    </div>
    </body>
@endsection
