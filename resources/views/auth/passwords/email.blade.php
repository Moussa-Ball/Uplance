@extends('layouts.app')

@section('content')
<div id="titlebar" class="gradient">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Reset Password</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="dark">
					<ul>
						<li><a href="{{ url('/') }}">Home</a></li>
						<li><a href="{{ route('login') }}">Login</a></li>
						<li>Forgot Password</li>
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
					<h3 style="font-size: 26px;">Reset Your Password</h3>
				</div>
					
				<!-- Form -->
				<form method="POST" action="{{ route('password.email') }}">
					@csrf
					<div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" value="{{ old('email') }}" class="input-text with-border" name="email" placeholder="Email Address" required/>
					</div>
					<button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit">Register <i class="icon-material-outline-arrow-right-alt"></i></button>
				</form>
			</div>
		</div>
	</div>
</div>


<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->
@endsection