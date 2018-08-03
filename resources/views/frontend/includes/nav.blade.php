<!-- Custom styles for this template -->

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{route('frontend.index')}}"><img class="navbar-brand" src="{{asset('img/logo.png')}}"/></a>
            </div>
        </div><!--navbar-header-->

        <div class="collapse navbar-collapse" id="frontend-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                @if ($logged_in_user)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ $logged_in_user->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>{{ link_to_route('frontend.auth.logout', trans('navs.general.logout')) }}</li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div><!--navbar-collapse-->
    </div><!--container-->
</nav>
