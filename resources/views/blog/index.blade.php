@extends('layouts.app')

@section('content')
    <!-- Content
================================================== -->
    <div id="titlebar" style="background: #F8F8F8; margin-bottom: 0;" class="gray">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Blog</h2>
                    <span>List of articles</span>

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
    <!-- Section -->
    <div class="section gray">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">

                    <!-- Section Headline -->
                    <div class="section-headline margin-top-60 margin-bottom-35">
                        <h4>Recent Posts</h4>
                    </div>

                    <!-- Blog Post -->
                    @foreach ($articles as $article)
                      <a href="{{ route('blog.read', ['slug' => $article->slug, 'id' => $article->id]) }}" class="blog-post">
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
                              {!! substr($article->content, 0, 75) !!}
                          </div>
                          <!-- Icon -->
                          <div class="entry-icon"></div>
                      </a>
                    @endforeach

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
                    <div class="sidebar-container margin-top-65">

                        <!-- Location -->
                        <form method="get" action="{{ route('blog.search') }}">
                            <div class="sidebar-widget margin-bottom-40">
                                <div class="input-with-icon">
                                    <input name="q" id="autocomplete-input" type="text" placeholder="Search">
                                    <i class="icon-material-outline-search"></i>
                                </div>
                            </div>
                        </form>

                        <!-- Widget -->
                        <div class="sidebar-widget">
                            <h3>Trending Posts</h3>
                            <ul class="widget-tabs">
                                @foreach($trendings as $trending)
                                <li>
                                    <a href="pages-blog-post.html" class="widget-content active">
                                        <img src="images/blog-02a.jpg" alt="">
                                        <div class="widget-text">
                                            <h5>How to "Woo" a Recruiter and Land Your Dream Job</h5>
                                            <span>29 June 2019</span>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Widget / End-->

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
