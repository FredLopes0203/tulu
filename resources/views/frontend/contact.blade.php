@extends('frontend.layouts.app')

@section('title', app_name() . ' | Contact Us')

@section('after-styles')
    {{ Html::style("js/iCheck/blue.css") }}
    {{ Html::style("css/login.css") }}
@endsection

@section('content')
    <body class="hold-transition register-page" style="background-image: url('/img/contactus.png');">
    <div class="register-box">

        <!-- /.form-box -->
    </div>
    </body>
@endsection