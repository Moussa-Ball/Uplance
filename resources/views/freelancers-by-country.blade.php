@extends('layouts.app')

@section('content')
<div class="intro-banner">
	<div class="container">
	<div class="dashboard-headline">
	    <h3>{{ $title }}</h3>
	    <span>{{ $subtitle }}</span>
	    <!-- Breadcrumbs -->
	    <nav id="breadcrumbs" class="dark">
	        <ul>
	            <li><a href="/">Home</a></li>
	            <li>{{ $title }}</li>
	        </ul>
	    </nav>
	</div>

	@if(!$freelancers->isEmpty())
	<div class="freelancers-container freelancers-list-layout compact-list margin-top-60">
		@foreach($freelancers as $freelancer)
		<!--Freelancer -->
		<div class="freelancer">

			<!-- Overview -->
			<div class="freelancer-overview">
				<div class="freelancer-overview-inner">
					
					<!-- Bookmark Icon -->
					<span class="bookmark-icon"></span>
					
					<!-- Avatar -->
					<div class="freelancer-avatar">
						@if($freelancer->verified)
						<div class="verified-badge"></div>
						@endif
						<a target="_blank" href="{{ route('freelancers.show', $freelancer->hashid) }}"><img width="100px" height="100px" src="{{ $freelancer->avatar }}" alt="avatar"></a>
					</div>

					<!-- Name -->
					<div class="freelancer-name">
						<h4><a target="_blank" href="{{ route('freelancers.show', $freelancer->hashid) }}">{{ $freelancer->first_name }} {{ $freelancer->last_name }} <img class="flag" src="/images/flags/{{ $freelancer->country }}.svg" alt="" data-tippy-placement="top" data-tippy="" data-original-title="{{ $freelancer->country_name }}"></a></h4>
						<span>{{ $freelancer->tagline }}</span>
						<!-- Rating -->
						<div class="freelancer-rating">
							<div class="star-rating" data-rating="{{ $freelancer->rating }}">
								<star-rating :style="{position: 'relative', top: 1 + 'px'}" 
											 :star-size="20" 
											 :read-only="true"
                                             :show-rating="false"
                                             :increment="0.01" :fixed-points="2" 
                                             :rating="{{ $freelancer->rating }}">
                              </star-rating>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Details -->
			<div class="freelancer-details">
				<div class="freelancer-details-list">
					<ul>
						<li>Location <strong><i class="icon-material-outline-location-on"></i> {{ $freelancer->city }}</strong></li>
						<li>Rate <strong>${{ $freelancer->hourly_rate }} / hr</strong></li>
						<li>Job Success <strong>{{ $freelancer->job_success }}%</strong></li>
					</ul>
				</div>
				<a target="_blank" href="{{ route('freelancers.show', $freelancer->hashid) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
			</div>
		</div>
		<!-- Freelancer / End -->
		@endforeach
	</div>
	@else
	<div class="tasks-list-container margin-top-65 padding-top-30 padding-bottom-30">
		<h1 class="page-title">Sorry, we could not find any freelancers for this.</h1>
	</div>
	@endif

	<!-- Pagination -->
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12">
			<!-- Pagination -->
			<div class="pagination-container margin-top-60 margin-bottom-60">
				{!! $freelancers->links('vendor/pagination/uplance') !!}
			</div>
		</div>
	</div>
	<!-- Pagination / End -->
</div>
</div>

@endsection


@section('vue')
@guest
<script src="{{ mix('js/app.js') }}" defer></script>
@endguest
@endsection
