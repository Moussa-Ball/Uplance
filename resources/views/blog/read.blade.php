@extends('layouts.app')

@guest
@section('vue')
<script src="{{ mix('js/app.js') }}" defer></script>
@endsection
@endguest

@section('content')
  <div id="titlebar" class="gradient">
  	<div class="container">
  		<div class="row">
  			<div class="col-md-12">
  				<h2>Blog</h2>
  				<span>Blog Post Page</span>

  				<!-- Breadcrumbs -->
  				<nav id="breadcrumbs" class="dark">
  					<ul>
  						<li><a href="#">Home</a></li>
  						<li><a href="#">Blog</a></li>
  						<li>Blog Post</li>
  					</ul>
  				</nav>
  			</div>
  		</div>
  	</div>
  </div>

  <!-- Post Content -->
  <div class="container">
  	<div class="row">

  		<!-- Inner Content -->
  		<div class="col-xl-8 col-lg-8">
  			<!-- Blog Post -->
  			<div class="blog-post single-post">

  				<!-- Blog Post Thumbnail -->
  				<div class="blog-post-thumbnail">
  					<div class="blog-post-thumbnail-inner">
              @foreach ($article->tags as $tag)
                <span class="blog-item-tag">{{ $tag->name }}</span>
              @endforeach
  						<img src="{{ asset('https://uplance.s3.ca-central-1.amazonaws.com/'.$article->image) }}" alt="{{ 'https://uplance.s3.ca-central-1.amazonaws.com/'.$article->image }}">
  					</div>
  				</div>

  				<!-- Blog Post Content -->
  				<div class="blog-post-content">
  					<h3 class="margin-bottom-10">{{ $article->name }}</h3>

  					<div class="blog-post-info-list margin-bottom-20">
  						<a href="#" class="blog-post-info">{{ \Carbon\Carbon::parse($article->created_at)->isoFormat('dddd D Y') }}</a>
  						<a href="#"  class="blog-post-info">5 Comments</a>
  					</div>

  					{!! nl2br($article->content) !!}

  					<!-- Share Buttons -->
  					<div class="share-buttons margin-top-25">
  						<div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
  						<div class="share-buttons-content">
  							<span>Interesting? <strong>Share It!</strong></span>
  							<ul class="share-buttons-icons">
  								<li><a href="#" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
  								<li><a href="#" data-button-color="#1da1f2" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
  								<li><a href="#" data-button-color="#dd4b39" title="Share on Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus-g"></i></a></li>
  								<li><a href="#" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
  							</ul>
  						</div>
  					</div>
  				</div>
  			</div>
  			<!-- Blog Post Content / End -->

  			<!-- Blog Nav -->
  			<ul id="posts-nav" class="margin-top-0 margin-bottom-40">
  				<li class="next-post">
  					<a href="#">
  						<span>Next Post</span>
  						<strong>16 Ridiculously Easy Ways to Find & Keep a Remote Job</strong>
  					</a>
  				</li>
  				<li class="prev-post">
  					<a href="#">
  						<span>Previous Post</span>
  						<strong>11 Tips to Help You Get New Clients Through Cold Calling</strong>
  					</a>
  				</li>
  			</ul>

  			<!-- Related Posts -->
  			<div class="row">

  				<!-- Headline -->
  				<div class="col-xl-12">
  					<h3 class="margin-top-40 margin-bottom-35">Related Posts</h3>
  				</div>

  				<!-- Blog Post Item -->
  				<div class="col-xl-6">
  					<a href="pages-blog-post.html" class="blog-compact-item-container">
  						<div class="blog-compact-item">
  							<img src="images/blog-02a.jpg" alt="">
  							<span class="blog-item-tag">Recruiting</span>
  							<div class="blog-compact-item-content">
  								<ul class="blog-post-tags">
  									<li>29 June 2019</li>
  								</ul>
  								<h3>How to "Woo" a Recruiter and Land Your Dream Job</h3>
  								<p>Appropriately empower dynamic leadership skills after business portals. Globally myocardinate interactive.</p>
  							</div>
  						</div>
  					</a>
  				</div>
  				<!-- Blog post Item / End -->

  				<!-- Blog Post Item -->
  				<div class="col-xl-6">
  					<a href="pages-blog-post.html" class="blog-compact-item-container">
  						<div class="blog-compact-item">
  							<img src="images/blog-03a.jpg" alt="">
  							<span class="blog-item-tag">Marketing</span>
  							<div class="blog-compact-item-content">
  								<ul class="blog-post-tags">
  									<li>10 June 2019</li>
  								</ul>
  								<h3>11 Tips to Help You Get New Clients Through Cold Calling</h3>
  								<p>Compellingly embrace empowered e-business after user friendly intellectual capital. Interactively front-end.</p>
  							</div>
  						</div>
  					</a>
  				</div>
  				<!-- Blog post Item / End -->
  			</div>
  			<!-- Related Posts / End -->

        <div id="disqus_thread"></div>
		<script>

		/**
		*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
		*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
		/*
		var disqus_config = function () {
		this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
		this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
		};
		*/
		(function() { // DON'T EDIT BELOW THIS LINE
		var d = document, s = d.createElement('script');
		s.src = 'https://uplance.disqus.com/embed.js';
		s.setAttribute('data-timestamp', +new Date());
		(d.head || d.body).appendChild(s);
		})();
		</script>
		<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                            

  		</div>
  		<!-- Inner Content / End -->


  		<div class="col-xl-4 col-lg-4 content-left-offset">
  			<div class="sidebar-container">

  				<!-- Location -->
  				<div class="sidebar-widget margin-bottom-40">
  					<div class="input-with-icon">
  						<input id="autocomplete-input" type="text" placeholder="Search">
  						<i class="icon-material-outline-search"></i>
  					</div>
  				</div>

  				<!-- Widget -->
  				<div class="sidebar-widget">

  					<h3>Trending Posts</h3>
  					<ul class="widget-tabs">

  						<!-- Post #1 -->
  						<li>
  							<a href="#" class="widget-content active">
  								<img src="images/blog-02a.jpg" alt="">
  								<div class="widget-text">
  									<h5>How to "Woo" a Recruiter and Land Your Dream Job</h5>
  									<span>29 June 2019</span>
  								</div>
  							</a>
  						</li>

  						<!-- Post #2 -->
  						<li>
  							<a href="#" class="widget-content">
  								<img src="images/blog-07a.jpg" alt="">
  								<div class="widget-text">
  									<h5>What It Really Takes to Make $100k Before You Turn 30</h5>
  									<span>3 June 2019</span>
  								</div>
  							</a>
  						</li>
  						<!-- Post #3 -->
  						<li>
  							<a href="#" class="widget-content">
  								<img src="images/blog-04a.jpg" alt="">
  								<div class="widget-text">
  									<h5>5 Myths That Prevent Job Seekers from Overcoming Failure</h5>
  									<span>5 June 2019</span>
  								</div>
  							</a>
  						</li>
  					</ul>

  				</div>
  				<!-- Widget / End-->


  				<!-- Widget -->
  				<div class="sidebar-widget">
  					<h3>Social Profiles</h3>
  					<div class="freelancer-socials margin-top-25">
  						<ul>
  							<li><a href="#" title="Dribbble" data-tippy-placement="top"><i class="icon-brand-dribbble"></i></a></li>
  							<li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
  							<li><a href="#" title="Behance" data-tippy-placement="top"><i class="icon-brand-behance"></i></a></li>
  							<li><a href="#" title="GitHub" data-tippy-placement="top"><i class="icon-brand-github"></i></a></li>
  						</ul>
  					</div>
  				</div>

  				<!-- Widget -->
  				<div class="sidebar-widget">
  					<h3>Tags</h3>
  					<div class="task-tags">
					@foreach ($tags as $tag)
						<a href="{{ route('blog.tag', $tag->slug) }}"><span>{{ $tag->name }}</span></a>
					@endforeach
  					</div>
  				</div>

  			</div>
  		</div>

  	</div>
  </div>

  <!-- Spacer -->
  <div class="padding-top-40"></div>
  <!-- Spacer -->
@endsection

@section('js')
<script id="dsq-count-scr" src="//uplance.disqus.com/count.js" async></script>
@endsection
