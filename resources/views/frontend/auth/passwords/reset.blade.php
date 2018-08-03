@extends('frontend.layouts.app')

@section('title', app_name() . ' | Reset Password')

@section('after-styles')
    {{ Html::style("css/login.css") }}
@endsection

@section('content')

    <body class="hold-transition register-page">
    <div class="register-box" >
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="register-box-body registerboxextended">
            <div style="text-align: center; margin-bottom: 10px; margin-top: 10px">
                <a href="/"><img src="<?php echo (asset('/img/logo.png')); ?>" style="height: 50px" alt=""></a>
            </div>


            <p class="login-box-msg registertext">Reset Password</p>
            {{ Form::open(['route' => 'frontend.auth.password.reset']) }}
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group has-feedback">
                {{ Form::email('curemail', $email, ['class' => 'form-control customizedtext', 'readonly'=>true, 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                <span class="glyphicon glyphicon-envelope form-control-feedback customizedicon"></span>
                {{ Form::hidden('email', $email, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
            </div>
            <div class="form-group has-feedback">
                {{ Form::password('password', ['class' => 'form-control customizedtext', 'required' => 'required', 'placeholder' => 'New Password']) }}
                <span class="glyphicon glyphicon-lock form-control-feedback customizedicon"></span>
            </div>
            <div class="form-group has-feedback">
                {{ Form::password('password_confirmation', ['class' => 'form-control customizedtext', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) }}
                <span class="glyphicon glyphicon-ok form-control-feedback customizedicon"></span>
            </div>

            <div class="row" style="padding-left: 15px; padding-right: 15px;">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
            </div>
            {{ Form::close() }}

        </div>
        <!-- /.form-box -->
    </div>
    </body>
@endsection

@section('after-scripts')
    {{ Html::script("js/login.js") }}
@endsection