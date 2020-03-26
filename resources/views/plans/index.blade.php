@extends('layouts.app')

@section('content')
<div id="titlebar" class="gradient">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Membership</h2>
				<span>Boost your profile and become your own boss.</span>
			</div>
		</div>
	</div>
</div>
<premium user_id="{{ $user->hashid }}" 
		:subscribed_to_pro="{{ $subscribed_to_pro }}" 
		:subscribed_to_business="{{ $subscribed_to_business }}"></premium>
<div class="margin-top-80"></div>
@endsection
