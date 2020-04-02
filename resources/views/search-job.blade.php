@extends('layouts.app')

@section('content')
<div id="titlebar" class="intro-banner">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Search Job</h2>
				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="dark">
					<ul>
						<li><a href="/">Home</a></li>
						<li>Search Job</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</div>

<div class="intro-banner" style="margin-top: -10em;">
	<div class="container">
		
		<!-- Search Bar -->
		<div class="row">
			<div class="col-md-12">
				<form method="get" action="{{ route('search-job') }}" class="intro-banner-search-form">

					<!-- Search Field -->
					<div class="intro-search-field with-autocomplete">
						<div class="input-with-icon">
							<input value="{{ old('location', Request::get('location')) }}" name="location" id="autocomplete-input" type="text" placeholder="Location">
							<i class="icon-material-outline-location-on"></i>
						</div>
					</div>

					<!-- Search Field -->
					<div class="intro-search-field">
						<input value="{{ old('q', Request::get('q')) }}" name="q" id="intro-keywords" type="text" placeholder="Job title or Keywords">
					</div>

					<!-- Button -->
					<div class="intro-search-button">
						<button class="button ripple-effect" type="submit">Search</button>
					</div>
				</form>
			</div>

			<div class="col-xl-12 col-lg-12">

				<div class="margin-top-40"></div>
				<h3 class="page-title">Search Results: {{ $jobs->count() }}</h3>
				
				<!-- Tasks Container -->
				<div class="tasks-list-container compact-list margin-top-35">
					
					<!-- Task -->
					@foreach($jobs as $job)
					<a target="_blank" href="{{ route('jobs.show', $job->hashid) }}" class="task-listing">

						<!-- Job Listing Details -->
						<div class="task-listing-details">

							<!-- Details -->
							<div class="task-listing-description">
								<h3 class="task-listing-title">{{ $job->project_name }}</h3>
								<ul class="task-icons">
									<li><i class="icon-material-outline-location-on"></i> {{ $job->user->city }}</li>
									<li><i class="icon-material-outline-access-time"></i> {{ Carbon\Carbon::createFromDate((string)$job->created_at)->diffForHumans() }}</li>
								</ul>
								<p class="task-listing-text">{{ (strlen($job->description) > 150) ? substr($job->description, 0, 150).'...' : $job->description }}</p>
								<div class="task-tags">
									@foreach(explode(',', $job->skills) as $skill)
									<span>{{ $skill }}</span>
									@endforeach
								</div>
							</div>

						</div>

						<div class="task-listing-bid">
							<div class="task-listing-bid-inner">
								<div class="task-offers">
									@if($job->minimum == $job->maximum)
									<strong>${{ $job->maximum }}</strong>
									@else
									<strong>${{ $job->minimum }} - ${{ $job->maximum }}</strong>
									@endif
									<span>Fixed Price</span>
								</div>
								<span class="button button-sliding-icon ripple-effect">See more <i class="icon-material-outline-arrow-right-alt"></i></span>
							</div>
						</div>
					</a>
					@endforeach

				</div>
				<!-- Tasks Container / End -->


				<!-- Pagination -->
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12">
						<!-- Pagination -->
						<div class="pagination-container margin-top-60 margin-bottom-60">
							{!! $jobs->links('vendor/pagination/uplance') !!}
						</div>
					</div>
				</div>
				<!-- Pagination / End -->

			</div>
		</div>
	</div>
</div>
@endsection
