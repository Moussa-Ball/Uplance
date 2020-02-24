@extends('layouts.app')

@section('content')
<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->

<!-- Page Content
================================================== -->
<div class="container" style="margin-top: 120px; margin-bottom: 120px;">
	<div class="row">
		<div class="col-xl-5 offset-xl-3">
			<div class="login-register-page">
				<!-- Welcome Text -->
				<div class="welcome-text">
					<h3>{{ __('Confirm Password') }}</h3>
					<span>{{ __('Please confirm your password before continuing.') }}</span>
				</div>
					
				<!-- Form -->
				<form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
					<div class="input-with-icon-left">
						<i class="icon-material-outline-lock"></i>
						<input type="password" value="{{ old('password') }}" class="input-text with-border" name="password"  placeholder="Password" required/>
                    </div>
                    
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit">{{ __('Confirm Password') }}<i class="icon-material-outline-arrow-right-alt"></i></button>
                </form>
			</div>
		</div>
	</div>
</div>

<!-- Spacer -->
<div class="margin-top-70"></div>
<!-- Spacer / End-->
@endsection
