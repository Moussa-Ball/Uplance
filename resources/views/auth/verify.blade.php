@extends('layouts.app')

@section('content')
<div id="titlebar" class="gradient">
	<div class="container text-center">
		<div class="row text-center">
			<div class="col-md-12 ">
				<h2>Verify Your Email</h2>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-xl-6 offset-xl-3">
			<div class="col-xl-12 margin-top-50">
				<div class="login-register-page text-center">
					<!-- Welcome Text -->
					<div class="welcome-text">
						<h3 style="font-size: 26px;">Verify your email address.</h3>
					</div>
					<p style="">{{ __('Before proceeding, please check your email for a verification link.') }}
					{{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</p>
				</div>
			</div>
		</div>
    </div>
</div>
<div style="margin-top: 100px;"></div>
@endsection