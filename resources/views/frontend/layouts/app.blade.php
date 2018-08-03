<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="{{app_name()}} - {{ trans('strings.frontend.landingpage.title') }} ">
        <meta name="author" content="Jin You Sheng - freelancer.com">

        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->

        @langRTL
            {{ Html::style(getRtlCss(mix('css/backend.css'))) }}
        @else
            {{ Html::style(mix('css/backend.css')) }}
        @endif

        @yield('after-styles')
        <link href="{{ asset('/css/all-landing.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/AdminLTE.min.css') }}" rel="stylesheet">
    </head>
    <body id="app-layout">
        <div id="app">
            @include('includes.partials.logged-in-as')

            <div class="container">
                @include('includes.partials.messages')
                @yield('content')
            </div><!-- container -->
        </div><!--#app-->

        <!-- Scripts -->
        @yield('before-scripts')
            {!! Html::script(mix('js/backend.js')) !!}
        @yield('after-scripts')

        @include('includes.partials.ga')
    </body>
</html>