<header class="main-header">

    <a href="{{ route('frontend.index') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
           <img class="navbar-brand" src="{{asset('img/logo2.png')}}"/>
        </span>

        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img class="navbar-brand" src="{{asset('img/logo1.png')}}"/>
        </span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('labels.general.toggle_navigation') }}</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ access()->user()->picture }}" class="user-image" alt="User Avatar"/>
                        <span class="hidden-xs">{{ access()->user()->full_name }}</span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ access()->user()->picture }}" class="img-circle" alt="User Avatar" />
                            <p>
                                {{ access()->user()->full_name }} - {{ implode(", ", access()->user()->roles->pluck('name')->toArray()) }}
                                <small>{{ trans('strings.backend.general.member_since') }} {{ access()->user()->created_at->format("m/d/Y") }}</small>
                            </p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('/account') }}" class="btn btn-primary btn-flat"><i class="fa fa-sign-out"></i>
                                    {{ trans('navs.frontend.user.account') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{!! route('frontend.auth.logout') !!}" class="btn btn-danger btn-flat">
                                    <i class="fa fa-sign-out"></i>
                                    {{ trans('navs.general.logout') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-custom-menu -->
    </nav>
</header>
