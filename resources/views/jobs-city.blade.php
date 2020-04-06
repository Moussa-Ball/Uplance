@extends('layouts.app')

@section('content')
<div class="intro-banner">
	<div class="container">

		<div class="dashboard-headline margin-bottom-60">
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
		
		<!-- Search Bar -->
		<div class="row">

			<div class="col-xl-12 col-lg-12">

				@if($jobs->isEmpty())
				<div class="margin-top-40"></div>
				<h1 class="page-title">Sorry, no jobs are found for this city.</h1>
				@endif

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
									<li><i class="icon-material-outline-location-on"></i> {{ $job->country_name }}</li>
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
								<span class="button button-sliding-icon ripple-effect">Bid Now <i class="icon-material-outline-arrow-right-alt"></i></span>
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
