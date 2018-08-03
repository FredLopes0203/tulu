@extends('frontend.layouts.app')

@section('title', app_name() . ' | Register')

@section('after-styles')
    {{ Html::style("js/iCheck/blue.css") }}
    {{ Html::style("css/login.css") }}
@endsection

@section('content')

    <body class="hold-transition register-page">
    <div class="register-box" style="margin-top: 20px !important;">
        <div class="register-box-body registerboxextended">
            <div style="text-align: center; margin-bottom: 10px; margin-top: 10px">
                <a href="/"><img src="<?php echo (asset('/img/logo.png')); ?>" style="height: 50px" alt=""></a>
            </div>


            <p class="login-box-msg registertext">{{ trans('labels.frontend.auth.register_box_title') }}</p>
            {{ Form::open(['route' => 'frontend.auth.register.post']) }}
            @if(!empty($affilliatecode))
                {{ Form::hidden('affilliatecode', $affilliatecode) }}
            @endif
            <div class="form-group has-feedback">
                {{ Form::text('first_name', null,['class' => 'form-control customizedtext', 'maxlength' => '191', 'required' => 'required', 'autofocus' => 'autofocus', 'placeholder' => trans('validation.attributes.frontend.first_name')]) }}
                <span class="glyphicon glyphicon-user form-control-feedback customizedicon"></span>
            </div>

            <div class="form-group has-feedback">
                {{ Form::text('last_name', null,['class' => 'form-control customizedtext', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.last_name')]) }}
                <span class="glyphicon glyphicon-user form-control-feedback customizedicon"></span>
            </div>

            <div class="form-group has-feedback">
                {{ Form::text('phonenumber', null,['class' => 'form-control customizedtext', 'maxlength' => '191', 'required' => 'required', 'placeholder' => 'Phone Number']) }}
                <span class="glyphicon glyphicon-earphone form-control-feedback customizedicon"></span>
            </div>

            <div class="form-group has-feedback">
                {{ Form::email('email', null, ['class' => 'form-control customizedtext', 'maxlength' => '191', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                <span class="glyphicon glyphicon-envelope form-control-feedback customizedicon"></span>
            </div>
            <div class="form-group has-feedback">
                {{ Form::password('password', ['class' => 'form-control customizedtext', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                <span class="glyphicon glyphicon-lock form-control-feedback customizedicon"></span>
            </div>
            <div class="form-group has-feedback">
                {{ Form::password('password_confirmation', ['class' => 'form-control customizedtext', 'required' => 'required', 'placeholder' => trans('validation.attributes.frontend.password_confirmation')]) }}
                <span class="glyphicon glyphicon-ok form-control-feedback customizedicon"></span>
            </div>
            <div class="row" style="padding-left: 15px; padding-right: 15px; margin-top: -15px; margin-bottom: 10px">

                    <div class="checkbox icheck">
                        <label  class="registertext" style="font-size: 12px;">
                            <input type="checkbox" required > Yes, I understand and agree to the TULU Terms of Service, including the <a href="/tos">User Agreements & Privacy Policy</a>
                        </label>
                    </div>
                <!-- /.col -->
            </div>

            <div class="row" style="padding-left: 15px; padding-right: 15px;">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
            </div>
            {{ Form::close() }}

            <div class="row" style="padding-right: 15px; padding-left: 15px; padding-top: 15px">
                <a href="/login" class="text-center">I already have an account</a>
            </div>

        </div>
        <!-- /.form-box -->
    </div>
    </body>
@endsection

@section('after-scripts')
    {{ Html::script("js/login.js") }}

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