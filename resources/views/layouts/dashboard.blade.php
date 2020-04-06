<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {!! SEOMeta::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="/internet.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body class="gray">
<div id="wrapper">
    <div id="app">
        <header id="header-container" class="fullwidth dashboard-header not-sticky">
            <div id="header">
                <div class="container">
                    <div class="left-side">
                        <div id="logo">
                            <a href="{{ url('/') }}"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKUAAAAqCAYAAADSzZvXAAAOrElEQVR4nO2ce4wdVR3HP7/JzbpZm7qsUNe1lBUBARuse31URblE0aoYEIyg8Y0PFJ8xgoqmMYj1EYKkNTE+ML54CGJ4iI+AjoiIhLtWrFqxqbXiWmvTbJp102w29+cfvzMzZ2Znzsz2sWq5383u3nvnPOd8z+/8XnOFIxsR8DNgvOL61cAXlmw0fTRC6789gMOMIeBU4OiK6/uXcCx9NET03x7AYcYyYDhw/ZGlGkgfzXGkk/JowqfB1FINpI/mONJJuTJwbR7YtVQD6aM5jnRSjgWuzQO7l2ogfTTHkU7KkKTchRGzj/8xHOmkfFLgWl+f/B9FC6Dd0ZWgVVJlFuShbiyVjUx0NBL0mRRIroCrta0bR3vaHW0pOiGV5WRrN5bpdkdboBMKp4M82Y1zBvSPgtyr6LbJOOo1mF/o+G5KymHg2cAERnI3Fn4L/NRrZznmfqrCZswFNQKcFCj3IHkJPg68EDjRjWUv8DvgHg5O/YiAE7C5neLGBTAL/Bm4H9jCgZ0mkRv3Wtd24pKbxsZ+H7CjqnJimX4I5ANgBAFFSEk4CbRDIxAYBfk5MKi5ukY3Rc8BbgMdF/glSMuuWllJaanPaXd0FfAJkNUZeT16w7wgD7Y7+nngtm4soZsWOr7r3EEnAx8GzqParTQLfBO4HFgHfKei3DxwDEbKc4GvVZSbBo7FSH8acCXwYmCwpOwM8G3gE8Ce8FRyGAbeDFyEzbHKO9EDtgEbgWuxudZhELgQeBewBhioKDeHEXMDcJfrK0Wy6CvVfeDTSY2iUyEp6bBC0bIbl8ARQFYotNS1bHRMCTmnyHrQGxVOU4iSMWi+rRa2A29S9NaJjo5W9DlAtdMc4O8Vnw9iN+s3wFsJ+zmHgIuBXwBnBsrtJXPUh6T3HlfuA8CvgbMpJySYD/Zi4FeEJa+P1wO/xyJZqwm7yyLX7kbgl4RPAbBN+Rvg65j0rSIk7loH+CFwPZmUto7bHQUYBciIabLO/mrtMafu6Ne8REuuzQuyx7W/EldCyCSlqzcg8HIgEq+M5NrKvYqAl4P+YqLTGy8Z1lBxsgWUScoVwI+Bj1BNhjKcihG4Cnsw6QD1eu6lwFWL6P8E4PsYSaswBHwL+AbhTVGFNVi4dk3JtRbwWeB2TPIuBhHwGuDn/rgi+5XRjACFpUdqfXmCOLIJmQKAa1FmsGMJgTEjoSyUf2mfRVKTHvD2Pqvnts4JAre2O1pclGFsMapQ3Gwj2M59YaBOCCGjcYrsiAqpFMcDn6xpqwynApdVXBsCvodJyYMxbFcAN5Hf6BHwFWwjHUzIejVwI066RopGrkMgObYNjh5/a9DocdnLTAFwRN+H/aLosZrSTrwayXbIevfUB98QSn81T9PTQK9wUj9BaPEhT8oWJkUmauocKPy+QpJqJQe+uO9goXSNsON03QG2WcQJwBXe+6sx/fRQ4HTgfQCRIMsUXa457U3x3jc4vvGOb/XIlNdJBRnzy+V78iW0ItATZJ8g04L2sjoL2neQiwsehLpjyj++34bpb3WYJ9P7FoOmpCxiDtiJ6aR1WIHpcj7egR2Pdei5PmYoGB0FJGVamAH4ngbt7gRizFtQZ5B9GBiKgJXCwp9Es9QGOiWwqlgvq59fEPGk3cLeBEH2CPJR4CnA47qxHAUcJ8hlGElLf4BBkDf6fQXG6xseI9iRGcJm4HzgKMyKfhzwAuBOwouYIDGqBgkbXwmmMOv4GOwUOgY4A9heU8+X9CvIS7UybHf9PAF4PDavU4BPk1nbPcwD83Y3lsswleAaqtWBHnAL5rU5DjMCzwCeCLwJd3KWYAWwrgWMFlwuQE6/rCWlwMrMFZTXSQXxJVJJOfHK66Qgr+rGstNvvxtHjwCfm+joXcDdoMNZXX/s+grshoK5Vqrgz+nNeOpLCa4D3kJmqOBe3wu8Elv4jwXqQyaVR6nX6x5w7fo+yB4maS4C7g604c/5EsIb4A7gDTh93+vnYczFdRPwTsxAup/85ruYavWoh23yT7Fww85jLrRpzDgrm8dZETCqOWJkdFHYJyauK9Hu6DLNKb8L3Ed/d+WGgOFEH0w0TutPwMJ+CwjpYzKWSUU/m2qrrj9P91zT7mgy0ZCkTEjSwghX2SULCemjB6zHiBRCsgnGCJNyJ3AO1U7x+wPXIHPDDGCGTRUeAl5LnpBFbMb8jfeRJ9cAJjWrcAPlhPRxBxYkKMPJLWAsT0l/ucV3ZVRhRNChpH6qPaaO83RBhoEhwdclfYtdrpyMo0pCZpCbBd2QjNB39AsyqOjR2MI1ieasIux/u5z6+c9jzvCiPlfWX50+uZ5w5tJ+wvpsMtbVmCVfhh6muwWFTQBrAm2DkXZjg3aqXF4jLYUnldnBTgLtnoyjYJhJ0VGBgVwMyLXl/iZSaQR0MCNiTqbOgN7QYCII7FVkNtkIqes9m8EgJo2qnOqQjWkt1ZJrNxZGbILNgWup94H6rKUf1fTTImyd/9P9Xxsosx2LohwoTics7V99EG0DNkFnEZMjpJNAtZnZmY+SXG17rT1xfk51BlUG9XvaPBlL01BZJNBK6vohTUfOeWwXhvTExPB4aqDMFuqlZIKQ9NpLZjSE9Nxp6qXXcsIRpkQinxgocy/NjLMqnHIQdZtgPhJHynK6NHMH+Q6dPDllP6hzZ+iYV4fMOhckEJwvwTgLQlhJZIieUzmGsAWsQjKvEHEXE0+uayc5bepCjHXx5WHCkZtHvHJ1ZQ4UoRPoUGB3pIUQY96I0H80aOQZyQvfSHLvZ9X52AQZK8ZwMkf6onaucwT7mnCiqcr2bixz1BsUycKEyoRIXUTI6e6ToE7PrbsPofo9Mn001E4oJt0Ehzvd8eGWmK7n6XmZzFMkeHy1LRni3DxBMgjs7jqdVNFji1qnV2+8yWjbnd4yzC1CTovMiiRWcJ1BkUjKkGQ6DVvAuiM8Ai5o0BeEo0wNjLxanTQhZcjZXha/XgxCbW/G1J6Dwe0tLPad0yQT2SPmwC5Fu6ORolcIMqzegZxB0bxOOuqX81PkFH12u6Pj3Vh2hPoDNmhKYD80mfb+A/cmtPizZDf2r4FyY1iU55ZAGTDXS2ihkzBti/Ax34SUoXmlOQbAnwLlOli4cFtNX0OUb9pQ27sw3+dBIcJJgswNlIszn9fu9BbcyAkjyKXiMmN8g8Nvh5yUkDRyVEy7EDNMNk50ytPf2p3eAOhVoO+WVEkouq90L+b/grBE2UV2vFX5yhJ8kbDL6FxXpi4ZA8yXG8r8CW2QBCFDyRcA91GdnDuAOcSr9M4WFgz4C/BxFqox9wTG8BLgdYHrPir13hboDuDovImSvhoV5O6Jjl4uqDsaZULR9yqyjhzFsjQ0j6BTABMdbZFKyjR86feDwNmgd7c7vQ0K94tlF60CfQnmxD2VtH28uta3wiYy6zWUHlYk5S6qlfdRLJfwakxibseINYE5kM+jPoHCN6pCZZsYIE0CAgBbsaP0mRVl1wJdLG/0R5iRNYK5ez5E5nO9Arv3G4CvYl6GBzDDdLyk3Qjz2Y5j61EWTjzZtflGLGT55WKBlnUi6eD9g9gt92qBW51lm3ScUSHnJUyupET7m32iyzW1GjNnTr43AJ4HcrsYaXqgkTr1wi8NvpGkCLITuMpLRm66ePuxsNelgfLDWNhsPRmZF5PJk/TXJJpTh7oH4RL0sJzM6wPlj8fSzty9TtIYF2AMc4a/HwsmfBfbpNdUtDuIZcxfjkXEHnHtj2CEXOX1s9GN+za/gQjL4E5/DPn37tNI0aiYoVPM7iHfzpR9LsvxXBmhLCH3OlLzRUZ4n1ZkCc0BF3Vj8XdlaPGKbq6raPasS0S987qI/WT6a2ijzFLvgmoaEEhwM/CTmjaTdp1tEcTxZGv4JepVnyFM8l6IHenrMAnq95OoEjlnfwTcI3Zc5n6g+EmajeNffwBknpJ6znqfsvKMChL51wJZQjUjyP3MC3JJNxY/QuGSTCpRzA/djSUvHI7HbfeRqRQhUk5TnTmTYBnhTPrivOaxuP3DNe02QQ9LdLnWvZ/DPA6H4mtvlmPJGakgibpx1AN5J4Wbkh3CXnpG7shkC+h6QVsF6Zj8zomTQJZvmeSb51MxbMIya+WKUhevzeJ/nVF4i6JfLUyy7tmcsoDAzcAHOfTE9KM5xwXK7abe9VQXECgjyBTwUiwB40AxB3wUU198bAdexMGTvoclcaTqRwTQjeUhvOyUPAUy7dFJP0DuUvQsO5Z96ZeUl2Qy7kjSsexzP7tHAJ1T9O2gs37fRee491kPuEfh+ZOxfHsyXnDq1BkUVVGqTVjO5GJ2/1bCcWS/r5D0buqjbGLlF7EDy/3cRPOwaYI/YKT+HOUO+YeB52BG0GLbBpv3BRQEQjrJbiwx0Fb4sqDTebdNQgbdIua8ftlkHO1yIcoZ0BlBvf/MCDLVjU0CSmoNZ7EXr/3pyTi6DuQMQWJB563OgnKzit7pHtc9czKOqna/G1Plb4h0twFPx5T07VRHRnYDnwGei0nCqr52eHVGAuXqfIZgm62q/j7CIeF9wHux6NsmjAxVc5vBMsXfgCXpxjXjmsY8Ec/CdE3fu1GG/Zg+egnwNOyUyqEYYgGg3dFhYI2actsC3SfIQ8DWbiw9r9wQMFR8CMw1Ot+No2mAiU7vOyCvS67lzCd4cDKOnuXKRYKcBKwFXQXyGOBfwFbQyW4cNTFIBggfc3tpFtYcwHyUE5i+8xgsC2cL5hZJdMVhqiXzfq/cCNWSbpb6uHfdvBYTqx/AHp89mfyXEGzHpH+Txy+qMIilzq3G7ttjgX+7Nrdh0jd4GpWS8lCj3en9DKSTvC88kXhLN5bzl2Icffx/4LB/k697wjBnefrpZor2v7i0jxyW4uul3eMSxeeA0vdNHuHt41GEpSDlkMDyfP6R/XXHeBPLs49HEZaClCsUHfAftSjkVfaP7z5yWApSjuUfs8jFunv0vyeyjwKWhJT5JDPwEjHm6ZOyjwKWxNABdhYc8ckRPuUeX+ijjxT/AZGp0xY/B/PLAAAAAElFTkSuQmCC" alt="logo"></a>
                        </div>
                        <nav id="navigation">
                            <ul id="responsive">
                                @guest
                                <li style="padding-top: 4px;"><a>How It Works</a></li>
                                <li style="padding-top: 4px;"><a href="{{ route('login') }}">Log In</a></li>
                                <li style="padding-top: 4px;"><a href="{{ route('register') }}">Register</a></li>
                                @else
                                <li><a class="not-dropdown" href="{{ route('jobs.index') }}">Find Work</a></li>
                                <li><a class="not-dropdown" href="{{ route('freelancers.index') }}">Find Freelancer</a></li>
                                @if(env('AGENCY_FEATURE'))
                                <li><a class="not-dropdown" href="#">Find Agency</a></li>
                                @endif
                                <li><a class="not-dropdown" href="{{ route('bookmarks') }}">Bookmarks</a></li>
                                <li><a class="not-dropdown" href="{{ route('contracts.index') }}">Contracts</a></li>
                                @if(Auth::user()->current_account == 'client')
                                <li><a class="not-dropdown" href="{{ route('jobs.create') }}">Post a Job</a></li>
                                @else
                                <li><a class="not-dropdown" href="{{ route('invoices.index') }}">Invoices</a></li>
                                @endif
                                @endguest
                            </ul>
                        </nav>
                        <div class="clearfix"></div>
                    </div>
                    <div class="right-side">
                        <!--  User Notifications -->
                        <div class="header-widget">
                            <notifications :user="{{ (int)Auth::user()->id }}"></notifications>
                        <messages-notifications :user="{{ (int)Auth::user()->id }}"></messages-notifications>
                        </div>
                        <!--  User Notifications / End -->

                        <!-- User Menu -->
                        <div class="header-widget">

                            <!-- Messages -->
                            <div class="header-notifications user-menu">
                                <div class="header-notifications-trigger">
                                    <a href="#">
                                        <div class="user-avatar">
                                            @if(preg_match('/^http/', Auth::user()->avatar))
                                            <img style="border-radius: 50%; width: 40px; height: 40px;" src="{{ Auth::user()->avatar }}" alt="avatar" />
                                            @else
                                            <img style="border-radius: 50%; width: 40px; height: 40px;" src="{{ asset(Auth::user()->avatar) }}" alt="avatar" />
                                            @endif
                                        </div>
                                    </a>
                                </div>

                                <!-- Dropdown -->
                                <div class="header-notifications-dropdown">

                                    <!-- User Status -->
                                    <div class="user-status">

                                        <!-- User Name / Avatar -->
                                        <div class="user-details">
                                            <div class="user-avatar">
                                                @if(preg_match('/^http/', Auth::user()->avatar))
                                                <img style="border-radius: 50%; width: 40px; height: 40px; vertical-align: middle;" src="{{ Auth::user()->avatar }}" alt="avatar" />
                                                @else
                                                <img style="border-radius: 50%; width: 40px; height: 40px; vertical-align: middle;" src="{{ asset(Auth::user()->avatar) }}" alt="avatar" />
                                                @endif
                                            </div>
                                            <div class="user-name">
                                                {{ Auth::user()->name }} <span>{{ ucfirst(Auth::user()->current_account) }}</span>
                                            </div>
                                        </div>

                                         <!-- Begin User Status Switcher -->
                                        <user-status-switcher :user="{{ json_encode(['id' => Auth::user()->hashid, 'switcher_status' => Auth::user()->switcher_status]) }}"></user-status-switcher>
                                        <!-- End User Status Switcher -->
                                </div>

                                <ul class="user-menu-small-nav">
                                    <li><a href="{{ route('dashboard') }}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                                    @if(env('SWITCH_ACCOUNT_FEATURE'))
                                    <li><a href="{{ route('account.switch') }}"><i class="icon-material-outline-account-circle"></i> Switch Account</a></li>
                                    @endif
                                    <li><a href="{{ route('settings') }}"><i class="icon-material-outline-settings"></i> Settings</a></li>
                                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementsByClassName('logout-form')[0].submit();">
                                                        <i class="icon-material-outline-power-settings-new"></i> {{ __('Logout') }}</a></li>
                                    <form class="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </ul>

                                </div>
                            </div>
                        </div>
                        <!-- User Menu / End -->
                        <span class="mmenu-trigger">
                            <button class="hamburger hamburger--collapse" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </header>
        <div class="clearfix"></div>
        <!-- Dashboard Container -->
        <div class="dashboard-container">
        <!-- Dashboard Sidebar
        ================================================== -->
        <div class="dashboard-sidebar">
            <div class="dashboard-sidebar-inner" data-simplebar>
                <div class="dashboard-nav-container">
                    <!-- Responsive Navigation Trigger -->
                    <a href="#" class="dashboard-responsive-nav-trigger">
                        <span class="hamburger hamburger--collapse" >
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </span>
                        <span class="trigger-title">Dashboard Navigation</span>
                    </a>
                    <!-- Navigation -->
                    <div class="dashboard-nav">
                        <div class="dashboard-nav-inner">
                            <ul data-submenu-title="Start">
                                <li class="{{ (Route::currentRouteName() == 'dashboard') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('dashboard') }}"><i class="icon-material-outline-dashboard"></i> Dashboard</a>
                                </li>
                                
                                <li class="{{ (Route::currentRouteName() == 'messages.index' || Route::currentRouteName() == 'messages.thread') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('messages.index') }}">
                                        <i class="icon-material-outline-question-answer"></i> Messages 
                                        <!-- Message Badge -->
                                        <message-badge :user="{{ (int)Auth::user()->id }}"></message-badge>
                                    </a>
                                </li>
                                
                                <li class="{{ (Route::currentRouteName() == 'bookmarks') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('bookmarks') }}"><i class="icon-material-outline-star-border"></i> Bookmarks</a>
                                </li>
                                
                                <li class="{{ (Route::currentRouteName() == 'reviews') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('reviews') }}"><i class="icon-material-outline-rate-review"></i> Reviews</a>
                                </li>
                                
                                <li class="{{ (Route::currentRouteName() == 'contracts.index' || Route::currentRouteName() == 'contracts.show') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('contracts.index') }}"><i class="icon-material-outline-assessment"></i> Contracts</a>
                                </li>
                                
                                <li class="{{ (Route::currentRouteName() == 'invoices.index') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('invoices.index') }}"><i class="icon-material-outline-assignment"></i> Invoices</a>
                                </li>
                               
                                @if(Auth::user()->current_account === 'freelancer')
                                <li class="{{ (Route::currentRouteName() == 'withdraws.index') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('withdraws.index') }}"><i class="icon-line-awesome-money"></i>Get Paid</a>
                                </li>
                                @endif
                                
                                <li class="{{ (Route::currentRouteName() == 'payments.index') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('payments.index') }}"><i class="icon-line-awesome-money"></i>Billing Method</a>
                                </li>
                                
                                <li class="{{ (Route::currentRouteName() == 'membership') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('membership') }}"><i class="icon-material-outline-account-balance-wallet"></i> Membership</a>
                                </li>
                                
                                @if(Auth::user()->current_account === 'freelancer')
                                <li class="{{ (Route::currentRouteName() == 'offers.index') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('offers.index') }}"><i class="icon-material-outline-local-offer"></i>  Offers</a>
                                </li>
                                @endif

                                @if(Auth::user()->current_account === 'freelancer')
                                <li class="{{ (Route::currentRouteName() == 'credit.index') ? 'active-submenu' : '' }}">
                                    <a href="{{ route('credit.index') }}"><i class="icon-line-awesome-credit-card"></i>  Credits</a>
                                </li>
                                @endif
                            </ul>
                            @if(Auth::user()->current_account === 'client')
                            <ul data-submenu-title="Organize and Manage">
                                @if(Auth::user()->current_account === 'client')
                                <li class="{{ (Route::currentRouteName() == 'jobs.offers' || Route::currentRouteName() == 'post-job' || Route::currentRouteName() == 'manage-jobs' || Route::currentRouteName() == 'manage-bidders') ? 'active-submenu' : '' }}">
                                    <a href="#"><i class="icon-material-outline-business-center"></i> Jobs</a>
                                    <ul>
                                    <li><a href="{{ route('jobs.create') }}">Post a Job</a></li>
                                        <li><a href="{{ route('jobs.manage') }}">Manage Jobs</a></li>
                                    </ul>
                                </li>
                                @endif
                                @if(env('AGENCY_FEATURE'))
                                @if(Auth::user()->account_type === 'freelancer' || Auth::user()->subscription_status === 'agency')
                                <li class="{{ (Route::currentRouteName() == 'agencies.offers' || Route::currentRouteName() == 'agency.create' || Route::currentRouteName() == 'agency.invitation.view' || Route::currentRouteName() == 'agency.teams') ? 'active-submenu' : '' }}">
                                    <a href="#"><i class="icon-material-outline-business"></i> Agency</a>
                                    <ul>
                                        @if(Auth::user()->subscription_status === 'agency')
                                        <li><a href="{{ route('agencies.offers') }}">Offers</a></li>
                                        @endif
                                        @if(Auth::user()->account_type === 'freelancer')
                                        <li><a href="{{ route('agency.invitation.view') }}">Invitations</a></li>
                                        @endif
                                        @if(Auth::user()->subscription_status === 'agency')
                                        <li><a href="{{ route('agency.teams') }}">My Teams</a></li>
                                        <li><a href="{{ route('agency.create') }}">Create a Team</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                @endif
                            </ul>
                            @endif

                            <ul data-submenu-title="Account">
                                <li class="{{ (Route::currentRouteName() == 'settings') ? 'active' : '' }}"><a href="{{ route('settings') }}"><i class="icon-material-outline-settings"></i> Settings</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="icon-material-outline-power-settings-new"></i> Logout
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                        </form>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Navigation / End -->
                </div>
            </div>
        </div>
        <!-- Dashboard Sidebar / End -->
        <div class="dashboard-content-container" data-simplebar>
            <div class="dashboard-content-inner">
                @yield('content')
                <!-- Footer -->
                <div class="dashboard-footer-spacer"></div>
                <div class="small-footer margin-top-15">
                    <div class="small-footer-copyrights">
                        Copyright ©
                        @if(date('Y') == 2019)
                                {{ 2019 }}
                        @elseif(date('Y') > 2019)
                            2019 - {{ date('Y') }}
                        @endif
                        <strong>{{ config('app.name', 'Uplance') }}</strong>. All Rights Reserved.
                    </div>
                    <ul class="footer-social-links">
                        <li>
                            <a target="_blank" href="https://www.facebook.com/UplanceHQ/" title="Facebook" data-tippy-placement="top">
                                <i class="icon-brand-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://twitter.com/UplanceHQ" title="Twitter" data-tippy-placement="top">
                                <i class="icon-brand-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="https://www.linkedin.com/company/uplance" title="LinkedIn" data-tippy-placement="top">
                                <i class="icon-brand-linkedin-in"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <!-- Footer / End -->
            </div>
        </div>
        @guest
        <div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">
            <div class="sign-in-form">
                <ul class="popup-tabs-nav">
                    <li><a href="#login">Log In</a></li>
                    <li><a href="#register">Register</a></li>
                </ul>
                <div class="popup-tabs-container">
                <div class="popup-tab-content" id="login">
                        <div class="welcome-text">
                            <h3>We're glad to see you again!</h3>
                            <span>Don't have an account? <a href="#" class="register-tab">Sign Up!</a></span>
                        </div>
                        <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        @if($errors->any())
                        <div class="notification error closeable">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <a class="close" href="#"></a>
                        </div>
                        @endif
                        <div class="input-with-icon-left">
                            <i class="icon-material-baseline-mail-outline"></i>
                            <input type="text" class="input-text with-border" name="email" id="emailaddress" placeholder="Email Address" required/>
                        </div>

                        <div class="input-with-icon-left">
                            <i class="icon-material-outline-lock"></i>
                            <input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
                        </div>

                        <div class="input-with-icon-left">
                            <div class="checkbox">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    <span class="checkbox-icon"></span>
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                        <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit" form="login-form">Log In <i class="icon-material-outline-arrow-right-alt"></i></button>
                    </form>

                    <!-- Social Login -->
                    <div class="social-login-separator"><span>or</span></div>
                    <div class="social-login-buttons">
                        <button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
                        <button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
                    </div>
                    </div>
                    <div class="popup-tab-content" id="register">
                        <div class="welcome-text">
                            <h3>Let's create your account!</h3>
                        </div>
                        <!-- Form -->
                        <form method="POST" action="{{ route('register') }}" id="register-account-form">
                            <!-- Account Type -->
                            <div class="account-type">
                                <div>
                                    <input type="radio" name="account_type" value="freelancer" id="freelancer-radio" class="account-type-radio" checked/>
                                    <label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
                                </div>

                                <div>
                                    <input type="radio" name="account_type" value="employer" id="employer-radio" class="account-type-radio"/>
                                    <label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Employer</label>
                                </div>
                            </div>
                            @csrf
                            @if($errors->any())
                            <div class="notification error closeable">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                <a class="close" href="#"></a>
                            </div>
                            @endif
                            <div class="input-with-icon-left">
                                <i class="icon-feather-user"></i>
                                <input type="text" class="input-text with-border" name="name" placeholder="Name" required/>
                            </div>

                            <div class="input-with-icon-left">
                                <i class="icon-material-baseline-mail-outline"></i>
                                <input type="text" class="input-text with-border" name="email" placeholder="Email Address" required/>
                            </div>

                            <div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
                                <i class="icon-material-outline-lock"></i>
                                <input type="password" class="input-text with-border" name="password" placeholder="Password" required/>
                            </div>

                            <div class="input-with-icon-left">
                                <i class="icon-material-outline-lock"></i>
                                <input type="password" class="input-text with-border" name="password_confirmation" placeholder="Repeat Password" required/>
                            </div>
                            <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit">Register <i class="icon-material-outline-arrow-right-alt"></i></button>
                        </form>
                        <div class="social-login-separator"><span>or</span></div>
                        <div class="social-login-buttons">
                            <button class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Register via Facebook</button>
                            <button class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Register via Google+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endguest
    </div>
</div>
@yield('bottom')
<script src="/js/jquery-3.3.1.min.js"></script>
<script src="/js/jquery-migrate-3.0.0.min.js"></script>
<script src="/js/mmenu.min.js"></script>
<script src="/js/tippy.all.min.js"></script>
<script src="/js/simplebar.min.js"></script>
<script src="/js/bootstrap-slider.min.js"></script>
<script src="/js/bootstrap-select.min.js"></script>
<script src="/js/snackbar.js"></script>
<script src="/js/clipboard.min.js"></script>
@if(Route::currentRouteName() == 'home')
<script src="/js/counterup.min.js"></script>
@endif
<script src="/js/magnific-popup.min.js"></script>
<script src="/js/slick.min.js"></script>
<script src="/js/custom.js"></script>
<script>
$('#snackbar-user-status label').click(function() {
	Snackbar.show({
		text: 'Your status has been changed!',
		pos: 'bottom-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
		backgroundColor: '#383838'
	});
});
</script>
<script>
	function initAutocomplete() {
		 var options = {
		  types: ['(cities)'],
		  // componentRestrictions: {country: "us"}
		 };

		 var input = document.getElementById('autocomplete-input');
		 var autocomplete = new google.maps.places.Autocomplete(input, options);
	}

	// Autocomplete adjustment for homepage
	if ($('.intro-banner-search-form')[0]) {
	    setTimeout(function(){
	        $(".pac-container").prependTo(".intro-search-field.with-autocomplete");
	    }, 300);
	}

</script>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=&libraries=places"></script>-->
@if($errors->any())
@foreach ($errors->all() as $error)
<script>
    new Noty({
        text: '<strong>{{ $error }}</strong>',
        type: 'error',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endforeach
@endif
@if(Session::has('success'))
<script>
    new Noty({
        text: '<strong>{{ Session::get('success') }}</strong>',
        type: 'success',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endif
@if(Session::has('error'))
<script>
    new Noty({
        text: '<strong>{{ Session::get('error') }}</strong>',
        type: 'error',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endif
@if(Session::has('info'))
<script>
    new Noty({
        text: '<strong>{{ Session::get('info') }}</strong>',
        type: 'info',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endif
@if(Session::has('warning'))
<script>
    new Noty({
        text: '<strong>{{ Session::get('warning') }}</strong>',
        type: 'warning',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endif
@if(Route::currentRouteName() != 'home')
<script src="{{ mix('js/app.js') }}"></script>
@endif
@yield('js')
@yield('stripe')
</body>
</html>