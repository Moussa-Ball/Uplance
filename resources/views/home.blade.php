@extends('layouts.app')

@section('css')
<style>
	.blog-compact-item-content p {
		color: #ffffff !important;
	}
</style>
@endsection

@section('content')
<!-- Intro Banner
================================================== -->
<div class="intro-banner" data-background-image="/images/man.jpg">

	<!-- Transparent Header Spacer -->
	<div class="transparent-header-spacer"></div>

	<div class="container">
		
		<!-- Intro Headline -->
		<div class="row">
			<div class="col-md-12">
				<div class="banner-headline">
					<h3>
						<strong>Hire experts freelancers for any job, any time.</strong>
						<br>
						<span>Huge community of designers, developers and creatives ready for your project.</span>
                    </h3>
				</div>
			</div>
		</div>
		
		<!-- Search Bar -->
		<div class="row">
			<div class="col-md-6">
				<form method="get" action="{{ route('search-job') }}" class="intro-banner-search-form margin-top-95">
					<!-- Search Field -->
					<div class="intro-search-field">
						<label for ="intro-keywords" class="field-title ripple-effect">What you need done?</label>
						<input name="q" id="intro-keywords" type="text" placeholder="Job title or Keywords">
					</div>

					<!-- Button -->
					<div class="intro-search-button">
						<button class="button ripple-effect" type="submit">Search</button>
					</div>
				</form>
			</div>
		</div>
		

		<!-- Stats -->
		<div class="row">
			<div class="col-md-12">
				<ul class="intro-stats margin-top-45 hide-under-992px">
					<li>
						<strong class="counter">{{ $job_posted }}</strong>
						<span>Jobs Posted</span>
					</li>
					<li>
						<strong class="counter">{{ $freelancers }}</strong>
						<span>Freelancers</span>
					</li>
					@if(env('AGENCY_FEATURE'))
					<li>
						<strong class="counter">{{ $agencies }}</strong>
						<span>Agencies</span>
					</li>
					@endif
				</ul>
			</div>
		</div>

	</div>
</div>



<!-- Content
================================================== -->
<!-- Category Boxes -->
<div class="section margin-top-65">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">

				<div class="section-headline centered margin-bottom-15">
					<h3>Popular Categories</h3>
				</div>

				<!-- Category Boxes Container -->
				<div class="categories-container">
					@foreach($tags as $tag)
					<!-- Category Box -->
					<a href="{{ route('freelancers.tag', $tag->slug) }}" class="category-box">
						<div class="category-box-icon">
							<i class="{{ $tag->icon }}"></i>
						</div>
						<div class="category-box-counter">{{ $tag->users()->count() }}</div>
						<div class="category-box-content">
						<h3>{{ $tag->name }}</h3>
						<p>{{ $tag->subtitle }}</p>
						</div>
					</a>
					@endforeach
				</div>

			</div>
		</div>
	</div>
</div>
<!-- Category Boxes / End -->


@if(!$recent_jobs->isEmpty())
<!-- Features Jobs -->
<div class="section gray margin-top-45 padding-top-65 padding-bottom-75">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				
				<!-- Section Headline -->
				<div class="section-headline margin-top-0 margin-bottom-35">
					<h3>Recent Jobs</h3>
					<a href="{{ route('search-job') }}" class="headline-link">Browse All Jobs</a>
				</div>
				
				<!-- Jobs Container -->
				<div class="tasks-list-container compact-list margin-top-35">
					@foreach($recent_jobs as $job)
					<!-- Task -->
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
				<!-- Jobs Container / End -->

			</div>
		</div>
	</div>
</div>
<!-- Featured Jobs / End -->
@endif

<!-- Icon Boxes -->
<div class="section padding-top-65 padding-bottom-65">
	<div class="container">
		<div class="row">

			<div class="col-xl-12">
				<!-- Section Headline -->
				<div class="section-headline centered margin-top-0 margin-bottom-5">
					<h3>How It Works?</h3>
				</div>
			</div>
			
			<div class="col-xl-4 col-md-4">
				<!-- Icon Box -->
				<div class="icon-box with-line">
					<!-- Icon -->
					<div class="icon-box-circle">
						<div class="icon-box-circle-inner">
							<i class="icon-line-awesome-lock"></i>
							<div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
						</div>
					</div>
					<h3>Create an Account</h3>
					<p>Create an account, complete your profile for additional information and start to work on Uplance</p>
				</div>
			</div>

			<div class="col-xl-4 col-md-4">
				<!-- Icon Box -->
				<div class="icon-box with-line">
					<!-- Icon -->
					<div class="icon-box-circle">
						<div class="icon-box-circle-inner">
							<i class="icon-line-awesome-legal"></i>
							<div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
						</div>
					</div>
					<h3>Post a Job</h3>
					<p>Publish your project online and start looking for experts for your project, we offer freelancers of different profiles.</p>
				</div>
			</div>

			<div class="col-xl-4 col-md-4">
				<!-- Icon Box -->
				<div class="icon-box">
					<!-- Icon -->
					<div class="icon-box-circle">
						<div class="icon-box-circle-inner">
							<i class=" icon-line-awesome-trophy"></i>
							<div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
						</div>
					</div>
					<h3>Choose an Expert</h3>
					<p>After you have chosen an expert for your project, you can hire him and manage your contract and your bills.</p>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Icon Boxes / End -->

<!-- Features Cities -->
<div class="section margin-top-65 margin-bottom-65">
	<div class="container">
		<div class="row">

			<!-- Section Headline -->
			<div class="col-xl-12">
				<div class="section-headline centered margin-top-0 margin-bottom-45">
					<h3>Featured Cities</h3>
				</div>
			</div>

			<div class="col-xl-3 col-md-6">
				<!-- Photo Box -->
				<a href="{{ route('jobs-in-san-francisco') }}" class="photo-box" data-background-image="/images/san-francisco.jpg">
					<div class="photo-box-content">
						<h3>San Francisco</h3>
						<span>{{ $jobsInCity[0] }} Jobs</span>
					</div>
				</a>
			</div>
			
			<div class="col-xl-3 col-md-6">
				<!-- Photo Box -->
				<a href="{{ route('jobs-in-new-york') }}" class="photo-box" data-background-image="images/architecture.jpg">
					<div class="photo-box-content">
						<h3>New York</h3>
						<span>{{ $jobsInCity[1] }} Jobs</span>
					</div>
				</a>
			</div>
			
			<div class="col-xl-3 col-md-6">
				<!-- Photo Box -->
				<a href="{{ route('jobs-in-los-angeles') }}" class="photo-box" data-background-image="images/los-angeles.jpg">
					<div class="photo-box-content">
						<h3>Los Angeles</h3>
						<span>{{ $jobsInCity[2] }} Jobs</span>
					</div>
				</a>
			</div>

			<div class="col-xl-3 col-md-6">
				<!-- Photo Box -->
				<a href="{{ route('jobs-in-miami') }}" class="photo-box" data-background-image="/images/miami.jpg">
					<div class="photo-box-content">
						<h3>Miami</h3>
						<span>{{ $jobsInCity[3] }} Jobs</span>
					</div>
				</a>
			</div>

		</div>
	</div>
</div>
<!-- Features Cities / End -->



<!-- Highest Rated Freelancers -->
@if(!$highest_rated_freelancers->isEmpty())
<div class="section border-top gray padding-top-65 padding-bottom-70 full-width-carousel-fix">
	<div class="container">
		<div class="row">

			<div class="col-xl-12">
				<!-- Section Headline -->
				<div class="section-headline margin-top-0 margin-bottom-25">
					<h3>Highest Top Freelancers</h3>
					<a href="{{ route('freelancers.index') }}" class="headline-link">Browse All Freelancers</a>
				</div>
			</div>

			<div class="col-xl-12">
				<div class="default-slick-carousel freelancers-container freelancers-grid-layout">
					@if($developer)
					<!--Freelancer -->
					<div class="freelancer">
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								
								<!-- Avatar -->
								<div class="freelancer-avatar">
									@if($developer->verified)
									<div class="verified-badge"></div>
									@endif
									<a href="{{ route('freelancers.show', $developer->hashid) }}">
										<img width="110px" height="110px" src="{{ $developer->avatar }}" alt="">
									</a>
								</div>

								<!-- Name -->
								<div class="freelancer-name">
									<h4><a target="_blank" href="{{ route('freelancers.show', $developer->hashid) }}">{{ $developer->name }} 
										<img class="flag" src="/images/flags/{{ strtolower($developer->country) }}.svg" alt="" 
											title="{{ $developer->country_name }}" data-tippy-placement="top"></a></h4>
									<span>{{ $developer->tagline }}</span>
								</div>

								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="{{ number_format($developer->rating, 1, '.', '') }}">
										<star-rating :style="{position: 'relative', top: 1 + 'px'}" 
													:star-size="20" 
													:read-only="true"
													:show-rating="false"
													:increment="0.01" :fixed-points="2" 
													:rating="{{ number_format($developer->rating, 1, '.', '') }}">
										</star-rating>
									</div>
								</div>
							</div>
						</div>
						
						<!-- Details -->
						<div class="freelancer-details">
							<div class="freelancer-details-list">
								<ul>
									<li>Location <strong><i class="icon-material-outline-location-on"></i> {{ $developer->city }}</strong></li>
									<li>Rate <strong>${{ $developer->hourly_rate }} / hr</strong></li>
									
									<hr class="margin-bottom-20" style="background: transparent; color: transparent; border-top: 0.1px solid rgba(20, 30, 40, .05);">
									
									<li>Job Success <strong>{{ $developer->job_success }}%</strong></li>
									<li>Recommendation <strong>{{ $developer->recommendation }}%</strong></li>
								</ul>
							</div>
							<a target="_blank" href="{{ route('freelancers.show', $developer->hashid) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
						</div>
					</div>
					@endif

					@foreach($highest_rated_freelancers as $freelancer)
					@if($freelancer && $freelancer->email != 'moiseball20155@gmail.com')
					<!--Freelancer -->
					<div class="freelancer">
						<!-- Overview -->
						<div class="freelancer-overview">
							<div class="freelancer-overview-inner">
								
								<!-- Avatar -->
								<div class="freelancer-avatar">
									@if($freelancer->verified)
									<div class="verified-badge"></div>
									@endif
									<a href="{{ route('freelancers.show', $freelancer->hashid) }}"><img width="110px" height="110px" src="{{ $freelancer->avatar }}" alt=""></a>
								</div>

								<!-- Name -->
								<div class="freelancer-name">
									<h4><a target="_blank" href="{{ route('freelancers.show', $freelancer->hashid) }}">{{ $freelancer->name }} <img class="flag" src="images/flags/{{ $freelancer->country }}.svg" alt="" title="{{ $freelancer->country_name }}" data-tippy-placement="top"></a></h4>
									<span>{{ $freelancer->tagline }}</span>
								</div>

								<!-- Rating -->
								<div class="freelancer-rating">
									<div class="star-rating" data-rating="{{ $freelancer->rating }}"></div>
								</div>
							</div>
						</div>
						
						<!-- Details -->
						<div class="freelancer-details">
							<div class="freelancer-details-list">
								<ul>
									<li>Location <strong><i class="icon-material-outline-location-on"></i> {{ $freelancer->city }}</strong></li>
									<li>Rate <strong>${{ $freelancer->hourly_rate }} / hr</strong></li>

									<hr class="margin-bottom-20" style="background: transparent; color: transparent; border-top: 0.1px solid rgba(20, 30, 40, .10);">
									
									<li>Job Success <strong>{{ $freelancer->job_success }}%</strong></li>
									<li>Recommendation <strong>{{ $freelancer->recommendation }}%</strong></li>
								</ul>
							</div>
							<a target="_blank" href="{{ route('freelancers.show', $freelancer->hashid) }}" class="button button-sliding-icon ripple-effect">View Profile <i class="icon-material-outline-arrow-right-alt"></i></a>
						</div>
					</div>
					@endif
					<!-- Freelancer / End -->
					@endforeach
				</div>
			</div>

		</div>
	</div>
</div>
@endif
<!-- Highest Rated Freelancers / End-->


<!-- Membership Plans -->
<div class="section border-top padding-top-60 padding-bottom-75">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<!-- Section Headline -->
				<div class="section-headline centered margin-top-0 margin-bottom-35">
					<h3>Membership Plans</h3>
				</div>
			</div>
			<div class="col-xl-12">
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
						<a href="{{ route('membership.subscribe.pro') }}" class="button full-width margin-top-20">Select</a>
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
						<a href="{{ route('membership.subscribe.business') }}" class="button full-width margin-top-20">Select</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Membership Plans / End-->


<!-- Recent Blog Posts -->
@if(!$posts->isEmpty())
<div class="section border-top padding-top-65 padding-bottom-50">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<!-- Section Headline -->
				<div class="section-headline margin-top-0 margin-bottom-45">
					<h3>From The Blog</h3>
					<a href="{{ route('blog') }}" class="headline-link">View Blog</a>
				</div>
				<div class="row">
					<!-- Blog Post Item -->
					@foreach($posts as $key => $post)
					<div class="col-xl-4">
						<a href="{{ route('blog.read', $post->id) }}" class="blog-compact-item-container">
							<div class="blog-compact-item">
								<img src="{{ Storage::url($post->image) }}" alt="image-{{ $key }}">
								@foreach ($post->tags as $tag)
								<span class="blog-item-tag">{{ $tag->name }}</span>
								@endforeach
								<div class="blog-compact-item-content">
									<ul class="blog-post-tags">
										<li>{{ $post->created_at->format('d M Y') }}</li>
									</ul>
									<h3>{{ $post->name }}</h3>
									<p style="color: #ffffff !important;">{!! substr($post->content, 0, 255) !!}...</p>
								</div>
							</div>
						</a>
					</div>
					@endforeach
					<!-- Blog post Item / End -->
				</div>
			</div>
		</div>
	</div>
</div>
@endif
<!-- Recent Blog Posts / End -->

@if(!$sponsors->isEmpty())
<div class="section border-top padding-top-45 padding-bottom-45">
	<!-- Logo Carousel -->
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<!-- Carousel -->
				<div class="col-md-12">
					<div class="logo-carousel">
						@foreach($sponsors as $key => $sponsor)
						<div class="carousel-item">
							<a href="{{ $sponsor->link }}" target="_blank" title="{{ $sponsor->title }}"><img src="{{ 'https://s3.' . 'ca-central-1' . '.amazonaws.com/'
                . 'uplance' . '/'. $sponsor->image }}" alt="sponsor{{ $key }}"></a>
						</div>
						@endforeach
					</div>
				</div>
				<!-- Carousel / End -->
			</div>
		</div>
	</div>
</div>
@endif
@endsection

@section('vue')
@guest
<script src="{{ mix('js/app.js') }}" defer></script>
@endguest
@endsection
