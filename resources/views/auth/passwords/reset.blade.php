@extends('layouts.app')

@section('content')
<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->

<!-- Page Content
================================================== -->
<div class="container">
	<div class="row">
		<div class="col-xl-5 offset-xl-3">
			<div class="login-register-page">
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3 style="font-size: 26px;">Let's reset your password!</h3>
				</div>
				<!-- Form -->
				<form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="input-with-icon-left">
						<i class="icon-material-baseline-mail-outline"></i>
						<input type="text" class="input-text with-border" value="{{ old('email') }}" name="email" placeholder="Email Address" required/>
					</div>
                    
                    <div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
						<i class="icon-material-outline-lock"></i>
						<input type="password" class="input-text with-border" value="{{ old('password') }}" name="password" placeholder="Password" required/>
					</div>
                    
                    <div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" value="{{ old('password_confirmation') }}" class="input-text with-border" name="password_confirmation" placeholder="Repeat Password" required/>
					</div>
                    
                    <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit">Reset Password <i class="icon-material-outline-arrow-right-alt"></i></button>
                
                </form>
			</div>
		</div>
	</div>
</div>


<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->

@endsection