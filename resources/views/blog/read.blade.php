
@extends('layouts.app')

@guest
@section('vue')
<script src="{{ mix('js/app.js') }}" defer></script>
@endsection
@endguest

@section('content')
<div id="titlebar" style="background: #F8F8F8; margin-bottom: 0;" class="gray">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Blog</h2>
				<span>{{ $article->name }}</span>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="dark">
					<ul>
						<li><a href="/">Home</a></li>
						<li><a href="{{ route('blog') }}">Blog</a></li>
						<li>{{ $article->name }}</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>

  <!-- Post Content -->
<div class="section gray">
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
						  <a href="javascript:void;" class="blog-post-info" style="cursor: default;">
							  {{ \Carbon\Carbon::parse($article->created_at)->isoFormat('dddd D Y') }}
						</a>
  					</div>

  					{!! nl2br($article->content) !!}

  					<!-- Share Buttons -->
  					<div class="margin-top-25"></div>
					<social-share url="{{ route('blog.read', $article->id) }}"></social-share>
  				</div>
  			</div>
  			<!-- Blog Post Content / End -->

			<div id="disqus_thread"></div>
			<div class="margin-bottom-60"></div>
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
                    <form method="get" action="{{ route('blog.search') }}">
                        <div class="sidebar-widget margin-bottom-40">
                            <div class="input-with-icon">
                                <input style="box-shadow: 0 1px 4px 0 rgba(0,0,0,.12) !important;" class="custom-search-input" name="q" value="{{ old('q', Request::get('q')) }}" id="autocomplete-input" type="text" placeholder="Search">
                                <i class="icon-material-outline-search"></i>
                            </div>
                        </div>
                    </form>

  				@if($trendings->count())
                    <!-- Widget -->
                    <div class="sidebar-widget">
                        <h3>Trending Posts</h3>
                        <ul class="widget-tabs">
                            @foreach($trendings as $key => $trending)
                            <li>
                                <a href="pages-blog-post.html" class="widget-content active">
                                    <img src="{{ Storage::url($trending->image) }}" alt="image-{{ $key }}">
                                    <div class="widget-text">
                                        <h5>{{ $trending->name }}</h5>
                                        <span>{{ $trending->created_at->format('d M Y') }}</span>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Widget / End-->
                    @endif


  				<!-- Widget -->
				<div class="sidebar-widget">
					<h3>Social Profiles</h3>
					<div class="freelancer-socials margin-top-25">
						<ul>
							<li><a target="_blank" href="https://www.facebook.com/UplanceHQ/" title="Facebook" data-tippy-placement="top"><i class="icon-brand-facebook"></i></a></li>
							<li><a target="_blank" href="https://twitter.com/UplanceHQ" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
							<li><a target="_blank" href="https://www.linkedin.com/company/uplance" title="Linkedin" data-tippy-placement="top"><i class="icon-brand-linkedin"></i></a></li>
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
</div>
@endsection

@section('js')
<script id="dsq-count-scr" src="//uplance.disqus.com/count.js" async></script>
@endsection

