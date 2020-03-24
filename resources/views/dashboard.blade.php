@extends('layouts.dashboard')

@section('css')
<style>
.dashboard-box .mark-as-read {
    float: none;
    background-color: transparent !important;
    position: absolute;
    right: 30px;
    top: 18px;
}
</style>
@endsection

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
    <h3>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
    <span>{{ ucfirst(Auth::user()->tagline) }}</span>
</div>

<!-- Fun Facts Container -->
<div class="fun-facts-container">
    <div class="fun-fact" data-fun-fact-color="#36bd78">
        <div class="fun-fact-text">
            <span>Proposals</span>
            <h4>{{ $proposals }}</h4>
        </div>
        <div class="fun-fact-icon"><i class="icon-material-outline-gavel"></i></div>
    </div>
    <div class="fun-fact" data-fun-fact-color="#b81b7f">
        <div class="fun-fact-text">
            <span>Jobs published</span>
            <h4>{{ $jobs_published }}</h4>
        </div>
        <div class="fun-fact-icon"><i class="icon-material-outline-business-center"></i></div>
    </div>
    <div class="fun-fact" data-fun-fact-color="#efa80f">
        <div class="fun-fact-text">
            <span>Reviews</span>
            @php
                $review_count = 0;

                foreach($reviews as $review) 
                {
                    if (Auth::user()->account_type == 'freelancer' && $review->rated) 
                    {
                        $review_count += 1;
                    } 
                    elseif (Auth::user()->account_type == 'employer')  
                    {
                        $review_count += 1;
                    }
                }
            @endphp
            <h4>{{ $review_count }}</h4>
        </div>
        <div class="fun-fact-icon"><i class="icon-material-outline-rate-review"></i></div>
    </div>

    <!-- Last one has to be hidden below 1600px, sorry :( -->
    <div class="fun-fact" data-fun-fact-color="#2a41e6">
        @if(Auth::user()->account_type == 'freelancer')
        <div class="fun-fact-text">
            <span>Total Earnings</span>
            <h4>
            @php
                $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
                echo $fmt->formatCurrency(Auth::user()->budget, 'USD');
            @endphp
            </h4>
        </div>
        @else
        <div class="fun-fact-text">
            <span>Total Spent</span>
            <h4>
            @php
                $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
                echo $fmt->formatCurrency(Auth::user()->spent, 'USD');
            @endphp
            </h4>
        </div>
        @endif
        <div class="fun-fact-icon"><i class="icon-line-awesome-money"></i></div>
    </div>
</div>

<!-- Row -->
<div class="row">
	<!-- Dashboard Box -->
	<div class="col-xl-12">
		<div class="dashboard-box">
			<div class="headline">
				<h3><i class="icon-material-baseline-notifications-none"></i> Notifications ({{ $notifications->count() }}) </h3>
				@if(!$notifications->isEmpty())
				<a href="{{ route('notifications.destroy_all') }}" class="mark-as-read ripple-effect-dark" data-tippy-placement="left" title="Remove all notifications">
					<i class="icon-feather-trash"></i>
				</a>
				@endif
			</div>
			<div class="content">
				<ul class="dashboard-box-list">
				@foreach($notifications as $notification)
					<li>
						<span class="notification-text"> {{ $notification->data['content'] }} </span>
						<!-- Buttons -->
						<div class="buttons-to-right">
                            <a href="{{ $notification->data['link'] }}" class="button ripple-effect ico" title="See the link" data-tippy-placement="left"><i class="icon-feather-arrow-right"></i></a>
                            <a href="{{ route('notifications.destroy', $notification->id) }}" class="button ripple-effect ico" title="Remove notification" data-tippy-placement="left"><i class="icon-feather-trash"></i></a>
						</div>
					</li>
				@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>

{!! $notifications->links('vendor.pagination.uplance') !!}
@endsection
