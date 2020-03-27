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
<div class="container">
    <div class="row">
      <div class="col-xl-12">
        <!-- Billing Cycle  -->
        <div class="margin-bottom-70"></div>

        <!-- Pricing Plans Container -->
        <div class="pricing-plans-container">
          <!-- Plan -->
          <div class="pricing-plan">
            <h3>Basic Free</h3>
            <p class="margin-top-10">The minimum to start on uplance and start winning.</p>
            <div class="pricing-plan-label billed-monthly-label">
              <strong>$0</strong>
            </div>
            <div class="pricing-plan-features">
              <strong>Features of Basic Free</strong>
              <ul>
                <li>30 credits / monthly</li>
                <li>2 credits = 1 Bid</li>
                <li>Unlimited Portfolio (Soon)</li>
                <li>Unlimited Bookmark</li>
                <li>Automatic offers</li>
              </ul>
            </div>
          </div>

          <!-- Plan -->
          <div class="pricing-plan recommended">
            <div class="recommended-badge">Recommended</div>
            <h3>Pro</h3>
            <p class="margin-top-10">For freelancers or clients seeking to stand out from others.</p>
            <div class="pricing-plan-label billed-monthly-label">
              <strong>$29</strong>/ monthly
            </div>
            <div class="pricing-plan-features">
              <strong>Features of Pro Plan</strong>
              <ul>
                <li>100 credits / monthly</li>
                <li>2 credits = 1 Bid</li>
                <li>Unlimited Portfolio (Soon)</li>
                <li>Unlimited Bookmark</li>
                <li>Profile Badge</li>
                <li>Automatic offers</li>
              </ul>
			</div>
			
			@if(Auth::user()->subscription('business') && !Auth::user()->subscription('business')->onGracePeriod())
			<a href="{{ route('membership.switch.business') }}" class="button full-width margin-top-20">Switch to Pro</a>
			@endif

			@if(!Auth::user()->subscription('pro') && !Auth::user()->subscription('business'))
			<a href="{{ route('membership.subscribe.pro') }}" class="button full-width margin-top-20">Select</a>
			@endif
			
			@if(Auth::user()->subscription('pro') && Auth::user()->subscription('pro')->onGracePeriod())
			<a href="{{ route('membership.resume.pro') }}" class="button full-width margin-top-20">Resume</a>
			<br>
			<mark>Will be automatically canceled at the end of the due date without billing.</mark>
			@endif

            @if(Auth::user()->subscription('pro') && !Auth::user()->subscription('pro')->onGracePeriod())
			<a href="{{ route('membership.cancel.pro') }}" class="button full-width margin-top-20">Cancel</a>
			@endif
          </div>

          <!-- Plan -->
          <div class="pricing-plan">
            <h3>Business</h3>
            <p class="margin-top-10">For freelancers or clients who want to do their business.</p>
            <div class="pricing-plan-label billed-monthly-label">
              <strong>$99</strong>/ monthly
            </div>
            <div class="pricing-plan-features">
              <strong>Features of Business Plan</strong>
              <ul>
                <li>Unlimited credits</li>
                <li>Unlimited proposals</li>
                <li>Unlimited Portfolio (Soon)</li>
                <li>Unlimited Bookmark</li>
                <li>Profile Badge</li>
                <li>Automatic offers</li>
              </ul>
			</div>
			@if(Auth::user()->subscription('pro') && !Auth::user()->subscription('pro')->onGracePeriod())
			<a href="{{ route('membership.switch.pro') }}" class="button full-width margin-top-20">Switch to Business</a>
			@endif

			@if(!Auth::user()->subscription('pro') && !Auth::user()->subscription('business'))
			<a href="{{ route('membership.subscribe.pro') }}" class="button full-width margin-top-20">Select</a>
			@endif
			
			@if(Auth::user()->subscription('business') && Auth::user()->subscription('business')->onGracePeriod())
			<a href="{{ route('membership.resume.business') }}" class="button full-width margin-top-20">Resume</a>
			<br>
			<mark>Will be automatically canceled at the end of the due date without billing.</mark>
			@endif

            @if(Auth::user()->subscription('business') && !Auth::user()->subscription('business')->onGracePeriod())
			<a href="{{ route('membership.cancel.business') }}" class="button full-width margin-top-20">Cancel</a>
			@endif
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="margin-top-80"></div>
@endsection
