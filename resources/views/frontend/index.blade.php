<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{app_name()}} - {{ trans('strings.frontend.landingpage.title') }} ">
    <meta name="author" content="Jin You Sheng - freelancer.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:title" content="{{app_name()}}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{app_name()}} - {{ trans('strings.frontend.landingpage.title') }}" />

    <title>{{ app_name() }}</title>

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/all-landing.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/AdminLTE.min.css') }}" rel="stylesheet">
</head>

<body data-spy="scroll" data-target="#navigation" data-offset="50">

<div id="app" v-cloak>
    <!-- Fixed navbar -->
    <div id="navigation" class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/"><img class="navbar-brand" src="{{asset('img/logo.png')}}"/></a>
            </div>
            <div id="myNavbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    {{--<li class="active"><a href="#home" class="smoothScroll">{{ trans('strings.frontend.landingpage.menu1') }}</a></li>--}}
                    {{--<li><a href="#desc" class="smoothScroll">Mobile App</a></li>--}}
                    {{--<li><a href="#contact" class="smoothScroll">Contact Us</a></li>--}}
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">{{ trans('strings.frontend.landingpage.menu5') }}</a></li>
                        <li><a href="{{ url('/register') }}">{{ trans('strings.frontend.landingpage.menu6') }}</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $logged_in_user->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                @if($logged_in_user->roles->first()->name != "User")
                                    <li>{{ link_to_route('frontend.user.dashboard', trans('navs.frontend.dashboard'), [], ['class' => active_class(Active::checkRoute('frontend.user.dashboard')) ]) }}</li>
                                @endif
                                {{--<li>{{ link_to_route('frontend.user.account', trans('navs.frontend.user.account'), [], ['class' => active_class(Active::checkRoute('frontend.user.account')) ]) }}</li>--}}
                                <li>{{ link_to_route('frontend.auth.logout', trans('navs.general.logout')) }}</li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>

    <section id="home" name="home">
        <div id="headerwrap" style="background: #fff;">
            <div class="container">
                <div class="row">
                    <div class="row centered">
                        <h2><a style="color: #0C426D;"><img style="width: 130px; margin-top:-24px;" src="{{asset('img/logo.png')}}"/><b> *</b></a>Emergency Notification & Accountability System</h2>
                    </div>

                    <div class="row centered">
                        <h3>In an emergency, first responders need the ability to communicate emergency information – such as weather emergencies, threats, and safety alerts – to everyone in an organization, and also account for people quickly...</h3>
                    </div>
                </div>
            </div> <!--/ .container -->


            <div class="row" style="margin-top: 50px; margin-right: 50px; margin-left:70px;">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 25px;">
                        <img class="img-responsive" src="{{ asset('/img/description.png') }}" alt="">
                    </div>
                </div>

                <div class="col-lg-5 col-md-5 col-sm-5" >
                    <div class="login-box-body requestbox">
                        {{ Form::open(['route' => 'frontend.auth.demo.post']) }}
                        <h3>Request a demo</h3>
                        <br>

                        <div class="form-group">
                            <label for="name1">{{ trans('strings.frontend.landingpage.name') }}</label>
                            <input type="name" name="Name" class="form-control" id="name1" placeholder="{{ trans('strings.frontend.landingpage.name') }}">
                        </div>

                        <div class="form-group">
                            <label for="email1">{{ trans('strings.frontend.landingpage.emailaddress') }}</label>
                            <input type="email" name="Mail" class="form-control" id="email1" placeholder="{{ trans('strings.frontend.landingpage.enteremail') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ trans('strings.frontend.landingpage.text') }}</label>
                            <textarea class="form-control" name="Message" rows="3"></textarea>
                        </div>

                        <br>
                        <button type="submit" class="btn btn-large btn-success">{{ trans('strings.frontend.landingpage.submit') }}</button>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="row centered">
                    <h2>... now you can with the <b>TULU</b> mobile app.</h2>
                </div>
            </div>

            <div class="row" style="text-align: center">
                <h3><b>Download the mobile app.</b></h3>
                <a href="#"><img style="display: inline !important; width: 100px;" class="img-responsive" src="{{ asset('/img/appstore.png') }}"></a>
                <a href="#"><img style="display: inline !important; width: 100px;" class="img-responsive" src="{{ asset('/img/googlestore.png') }}"></a>
            </div>

            <div class="row" style="text-align: center">
                <h5 style="color: #000; text-align: center !important; font-weight: normal !important;">Tulu mobile app is patent pending.</h5>
                <br>
                <br>
            </div>


        </div><!--/ #headerwrap -->



    </section>

    <footer>
        <div id="c" style="padding-top: 0px !important;">
            <div class="container">
                <p style="margin-bottom: 0px !important;">
                    Copyright © 2018 TULU. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="{{ url ('/js/frontend.js') }}"></script>
<script src="{{ url ('/js/smoothscroll.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.carousel').carousel({
            interval: 3500
        });

        $('body').scrollspy({target: ".navbar", offset: 50});

        $("#myNavbar a").on('click', function(event) {
            if (this.hash !== "") {
                event.preventDefault();
                var hash = this.hash;

                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 800, function(){
                    window.location.hash = hash;
                });
            }
        });
    });
</script>
</body>
</html>
