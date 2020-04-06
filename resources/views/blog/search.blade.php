@extends('layouts.app')

@section('css')
<style>
    input.custom-search-input {
        height: 48px !important;
        line-height: 48px !important;
        padding: 0 20px !important;
        outline: none !important;
        font-size: 16px !important;
        color: gray !important;
        margin: 0 0 16px !important;
        max-width: 100% !important;
        width: 100% !important;
        box-sizing: border-box !important;
        display: block !important;
        background-color: #fff !important;
        font-weight: 500 !important;
        opacity: 1 !important;
        border-radius: 4px !important;
        border: none !important;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,.12) !important;
    }
</style>

@section('content')
<!-- Content
================================================== -->
<div id="titlebar" style="background: #F8F8F8; margin-bottom: 0;" class="gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Blog</h2>
                <span>Search an article</span>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="/">Blog</a></li>
                        <li>Search</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
    
<!-- Section -->
    <div class="section gray">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-8">

                <!-- Section Headline -->
                <div class="section-headline margin-bottom-35">
                    <h4>Search Results: {{ $articles->count() }}</h4>
                </div>

                <!-- Blog Post -->
                @foreach ($articles as $article)
                    <a href="{{ route('blog.read', $article->id) }}" class="blog-post">
                        <!-- Blog Post Thumbnail -->
                        <div class="blog-post-thumbnail">
                            <div class="blog-post-thumbnail-inner">
                            @foreach ($article->tags as $tag)
                                <span class="blog-item-tag">{{ $tag->name }}</span>
                            @endforeach
                                <img src="{{ asset('https://uplance.s3.ca-central-1.amazonaws.com/'.$article->image) }}" alt="{{ asset('https://uplance.s3.ca-central-1.amazonaws.com/'.$article->image) }}">
                            </div>
                        </div>
                        <!-- Blog Post Content -->
                        <div class="blog-post-content">
                            <span class="blog-post-date">{{ \Carbon\Carbon::parse($article->created_at)->isoFormat('dddd D Y') }}</span>
                            <h3>{{ $article->name }}</h3>
                            {!! substr($article->content, 0, 255) !!}
                        </div>
                        <!-- Icon -->
                        <div class="entry-icon"></div>
                    </a>
                @endforeach
                @if(!$articles->count())
                <span style="font-size: 20px;">Sorry, no articles were found.</span>
                @endif

                <!-- Pagination -->
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Pagination -->
                        <div class="pagination-container margin-top-10 margin-bottom-20">
                            {!! $articles->links('vendor/pagination/uplance') !!}
                        </div>
                    </div>
                </div>
                <!-- Pagination / End -->

            </div>


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

    <!-- Spacer -->
    <div class="padding-top-40"></div>
    <!-- Spacer -->

</div>
<!-- Section / End -->
@endsection
