@extends('layouts.app')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Log In</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="dark">
					<ul>
						<li><a href="{{ url('/') }}">Home</a></li>
						<li>Log In</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</div>


<!-- Page Content
================================================== -->
<div class="container">
	<div class="row">
		<div class="col-xl-5 offset-xl-3">
			<div class="login-register-page">
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>We're glad to see you again!</h3>
					<span>Don't have an account? <a href="{{ route('register') }}">Sign Up!</a></span>
				</div>
					
				<!-- Form -->
				<form method="POST" action="{{ route('login') }}">
                    @csrf
					<div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" value="{{ old('name') }}" class="input-text with-border" name="email" placeholder="Email Address" required/>
                    </div>

					<div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" value="{{ old('password') }}" class="input-text with-border" name="password"  placeholder="Password" required/>
                    </div>
                    
					<div class="input-with-icon-left">
						<div class="checkbox">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                <span class="checkbox-icon"></span> 
                                {{ __('Remember me') }}
                            </label>
                        </div>
					</div>
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit">Log In <i class="icon-material-outline-arrow-right-alt"></i></button>
                </form>
				
				<!-- Social Login -->
				<div class="social-login-separator"><span>or</span></div>
				<div class="social-login-buttons">
					<button onclick="window.location.replace('{{ route('socialite.auth', ['provider' => 'facebook']) }}');" class="facebook-login ripple-effect"><i class="icon-brand-facebook-f"></i> Log In via Facebook</button>
					<button onclick="window.location.replace('{{ route('socialite.auth', ['provider' => 'google']) }}');" class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log In via Google+</button>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->

@endsection