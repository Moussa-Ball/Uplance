<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {!! SEO::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <script src="{{ mix('js/app.js') }}" defer></script>
    @endauth
    @yield('vue')
    <script src="https://kit.fontawesome.com/5df05f9a53.js"></script>
    <link rel="shortcut icon" type="image/png" href="/internet.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
@if(Route::currentRouteName() === 'blog.tag')
<body class="gray">
@else
<body>
@endif
<div id="wrapper">
    <div id="app">
        <header id="header-container" class="fullwidth transparent">
            <div id="header">
                <div class="container">
                    <div class="left-side">
                        <div id="logo">
                            <a href="{{ url('/') }}"><img src="data:image/png;base64,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" alt="logo"></a>
                        </div>
                        <nav id="navigation">
                            <ul id="responsive">
                                @guest
                                <li style="padding-top: 4px;"><a class="not-dropdown" href="{{ route('login') }}">Log In</a></li>
                                <li style="padding-top: 4px;"><a class="not-dropdown" href="{{ route('register') }}">Register</a></li>
                                @else
                                <li><a class="not-dropdown" href="{{ route('jobs.index') }}">Find Work</a></li>
                                <li><a class="not-dropdown" href="{{ route('freelancers.index') }}">Find Freelancer</a></li>
                                @if(env('AGENCY_FEATURE'))
                                <li><a class="not-dropdown" href="#">Find Agency</a></li>
                                @endif
                                <li><a class="not-dropdown" href="#">Bookmarks</a></li>
                                <li><a class="not-dropdown" href="{{  route('contracts.index')}}">Contracts</a></li>
                                @if(Auth::user()->current_account == 'client')
                                <li><a class="not-dropdown" href="{{ route('jobs.create') }}">Post a Job</a></li>
                                @else
                                <li><a class="not-dropdown" href="#">Invoices</a></li>
                                @endif
                                @endguest
                            </ul>
                        </nav>
                        <div class="clearfix"></div>
                    </div>
                    <div class="right-side">
                        @guest
                        <div class="header-widget">
                            <a href="#sign-in-dialog" class="popup-with-zoom-anim log-in-button"><i class="icon-feather-log-in"></i> <span>Log In / Register</span></a>
                        </div>
                        @else
                        <!--  User Notifications -->
                        <div class="header-widget hide-on-mobile">
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
                                            <img style="border-radius: 50%; width: 40px; height: 40px; vertical-align: middle !important;" src="{{ Auth::user()->avatar }}" alt="avatar" />
                                            @else
                                            <img style="border-radius: 50%; width: 40px; height: 40px; vertical-align: middle;" src="{{ asset(Auth::user()->avatar) }}" alt="avatar" />
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
                                                <img style="border-radius: 50%; width: 40px; height: 40px;" src="{{ Auth::user()->avatar }}" alt="avatar" />
                                                @else
                                                <img style="border-radius: 50%; width: 40px; height: 40px;" src="{{ asset(Auth::user()->avatar) }}" alt="avatar" />
                                                @endif
                                            </div>
                                            <div class="user-name">
                                                {{ ucfirst(Auth::user()->first_name) }} {{ ucfirst(Auth::user()->last_name) }} <span>{{ ucfirst(Auth::user()->account_type) }}</span>
                                            </div>
                                        </div>

                                        <!-- Begin User Status Switcher -->
                                        <user-status-switcher :user="{{ json_encode(['id' => Auth::user()->hashid, 'switcher_status' => Auth::user()->switcher_status]) }}"></user-status-switcher>
                                        <!-- End User Status Switcher -->
                                    </div>

                                    <ul class="user-menu-small-nav">
                                        <li><a href="#"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>
                                        @if(env('SWITCH_ACCOUNT_FEATURE') && Auth::user()->current_account)
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
                        @endguest
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
        @yield('content')
        <div id="footer">
            <div class="footer-top-section">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">

                            <!-- Footer Rows Container -->
                            <div class="footer-rows-container">

                                <!-- Left Side -->
                                <div class="footer-rows-left">
                                    <div class="footer-row">
                                        <div class="footer-row-inner footer-logo">
                                            <img src="data:image/png;base64,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" alt="logo">
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side -->
                                <div class="footer-rows-right">

                                    <!-- Social Icons -->
                                    <div class="footer-row">
                                        <div class="footer-row-inner">
                                            <ul class="footer-social-links">
                                                <li>
                                                    <a target="_blank" href="https://www.facebook.com/UplanceHQ/" title="Facebook" data-tippy-placement="bottom" data-tippy-theme="light">
                                                        <i class="icon-brand-facebook-f"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="_blank" href="https://twitter.com/UplanceHQ" title="Twitter" data-tippy-placement="bottom" data-tippy-theme="light">
                                                        <i class="icon-brand-twitter"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a target="_blank" href="https://www.linkedin.com/company/uplance" title="LinkedIn" data-tippy-placement="bottom" data-tippy-theme="light">
                                                        <i class="icon-brand-linkedin-in"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <!-- Language Switcher -->
                                    <div class="footer-row">
                                        <div class="footer-row-inner">
                                            <select onchange="window.location.replace(document.getElementById('select__language').value);" id="select__language" class="selectpicker language-switcher" data-selected-text-format="count" data-size="5">
                                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <option value="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" @if(LaravelLocalization::getCurrentLocale() === $localeCode) selected @endif>{{ ucfirst($properties['native']) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- Footer Rows Container / End -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Top Section / End -->

            <!-- Footer Middle Section -->
            <div class="footer-middle-section">
                <div class="container">
                    <div class="row">

                        <!-- Links -->
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <div class="footer-links">
                                <h3>Company</h3>
                                <ul>
                                    <li><a href="#"><span>About</span></a></li>
                                    <li><a href="#"><span>Support</span></a></li>
                                    <li><a href="#"><span>Terms of Service</span></a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Links -->
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <div class="footer-links">
                                <h3>Resources</h3>
                                <ul>
                                    <li><a href="#"><span>FAQ</span></a></li>
                                    <li><a href="#"><span>Blog</span></a></li>
                                    <li><a href="#"><span>Contact Us</span></a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Links -->
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <div class="footer-links">
                                <h3>Browse</h3>
                                <ul>
                                    <li><a href="#"><span>Freelancer In UK</span></a></li>
                                    <li><a href="#"><span>Freelancer In USA</span></a></li>
                                    <li><a href="#"><span>Freelancer In France</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom-section">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                        Copyright ©
                        @if(date('Y') == 2019)
                                {{ 2019 }}
                        @elseif(date('Y') > 2019)
                            2019 - {{ date('Y') }}
                        @endif
                        <strong>{{ config('app.name', 'Uplance') }}</strong>. All Rights Reserved.
                        </div>
                    </div>
                </div>
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
                            <input type="text" autocomplete="off" class="input-text with-border" name="email" id="emailaddress" placeholder="Email Address" required/>
                        </div>

                        <div class="input-with-icon-left">
                            <i class="icon-material-outline-lock"></i>
                            <input type="password" autocomplete="off" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
                        </div>

                        <div class="input-with-icon-left">
                            <div class="checkbox">
                                <input type="checkbox" name="remember" id="remember-modal" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember-modal">
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
                        <button onclick="window.location.replace('{{ route('socialite.auth', ['provider' => 'facebook']) }}');" class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
                        <button onclick="window.location.replace('{{ route('socialite.auth', ['provider' => 'google']) }}');" class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
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
                                    <input type="radio" name="account_type" value="freelancer" id="freelancer-radio-modal" class="account-type-radio" checked/>
                                    <label for="freelancer-radio-modal" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
                                </div>

                                <div>
                                    <input type="radio" name="account_type" value="employer" id="employer-radio-modal" class="account-type-radio"/>
                                    <label for="employer-radio-modal" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Employer</label>
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
                                <input type="text" autocomplete="off" class="input-text with-border" name="name" placeholder="Name" required/>
                            </div>

                            <div class="input-with-icon-left">
                                <i class="icon-material-baseline-mail-outline"></i>
                                <input type="text" autocomplete="off" class="input-text with-border" name="email" placeholder="Email Address" required/>
                            </div>

                            <div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
                                <i class="icon-material-outline-lock"></i>
                                <input type="password" autocomplete="off" class="input-text with-border" name="password" placeholder="Password" required/>
                            </div>

                            <div class="input-with-icon-left">
                                <i class="icon-material-outline-lock"></i>
                                <input type="password" autocomplete="off" class="input-text with-border" name="password_confirmation" placeholder="Repeat Password" required/>
                            </div>
                            <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit">Register <i class="icon-material-outline-arrow-right-alt"></i></button>
                        </form>
                        <div class="social-login-separator"><span>or</span></div>
                        <div class="social-login-buttons">
                            <button onclick="window.location.replace('{{ route('socialite.auth', ['provider' => 'facebook']) }}');" class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
                            <button onclick="window.location.replace('{{ route('socialite.auth', ['provider' => 'google']) }}');" class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endguest
    </div>
</div>
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
@yield('js')
<script>
	function initAutocomplete() {
		 let options = {
		  types: ['(cities)'],
		  // componentRestrictions: {country: "us"}
		 };

        let input = document.getElementById('autocomplete-input');
        let autocomplete = new google.maps.places.Autocomplete(input, options);
	}

	// Autocomplete adjustment for homepage
	/*if ($('.intro-banner-search-form')[0]) {
	    setTimeout(function(){
	        $(".pac-container").prependTo(".intro-search-field.with-autocomplete");
	    }, 300);
	}*/
</script>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbu5dKxR7XfhrdHqoEVo4-tegSvwBmYY8&libraries=places&callback=initAutocomplete"></script>-->
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
@if(Session::has('status'))
<script>
    new Noty({
        text: '<strong>{{ Session::get('status') }}</strong>',
        type: 'success',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endif
@if(Session::has('info'))
<script>
    new Noty({
        text: '<strong>{{ Session::get('status') }}</strong>',
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
@if (session('resent'))
<script>
    new Noty({
        text: '<strong>{{ __('A fresh verification link has been sent to your email address.') }}</strong>',
        type: 'success',
        theme: 'metroui',
        progressBar: true,
        timeout: 5000,
    }).show();
</script>
@endif
</body>
</html>
