@extends('layouts.app')

@section('content')
    <!-- Titlebar
================================================== -->
    <div class="single-page-header freelancer-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="single-page-header-inner">
                        <div class="left-side">
                            <div class="header-image freelancer-avatar"><img src="{{ $freelancer->avatar }}" alt="avatar"></div>
                            <div class="header-details">
                                <h3>{{ $freelancer->name }} <span>{{ $freelancer->tagline }}</span></h3>
                                <ul>
                                    <div data-rating="{{ $freelancer->rating }}" class="star-rating">
                                        <star-rating :style="{position: 'relative', top: 3 + 'px'}" :star-size="18" :read-only="true"
                                                        :show-rating="false" :rating="{{ $freelancer->rating }}">
                                        </star-rating>
                                    </div>
                                    <li style="position: relative; left: 10px; top: 1px;">
                                        <img class="flag" src="/images/flags/{{ strtolower($freelancer->country) }}.svg" alt="flag"> 
                                        {{ ucfirst(\PragmaRX\Countries\Package\Countries::where('cca2', $freelancer->country)->first()->name->common) }} 
                                    </li>
                                    @if($freelancer->verified)
                                    <li><div class="verified-badge-with-title">Verified</div></li>
                                    @endif
                                    <div class="task-tags">
                                        @if($freelancer->subscribed('pro'))
                                        <span><strong>Pro</strong></span>
                                        @elseif($freelancer->subscribed('business'))
                                        <span><strong>Business</strong></span>
                                        @endif
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content
    ================================================== -->
     <div class="container">
        <div class="row">

            <!-- Content -->
            <div class="col-xl-8 col-lg-8 content-right-offset">
                <!-- Page Content -->
                <div class="single-page-section">
                    <h3 class="margin-bottom-25">About Me</h3>
                    {!! nl2br($freelancer->presentation) !!}
                </div>

                <!-- Boxed List -->
                @if(!$reviews->isEmpty())
                <div class="boxed-list margin-bottom-60">
                    <div class="boxed-list-headline">
                        <h3><i class="icon-material-outline-thumb-up"></i> Work History and Feedback</h3>
                    </div>
                    <ul class="boxed-list-ul">
                        @foreach($reviews as $review)
                        @if(!$review->contract->is_agency)
                        <li>
                            <div class="boxed-list-item">
                                <!-- Content -->
                                <div class="item-content">
                                    <h4>{{ $review->contract->title }} <span>Rated as Freelancer</span></h4>
                                    <div class="item-details margin-top-10">
                                        @if($review->rating)
                                        <div data-rating="{{ $review->rating }}" class="star-rating">
                                            <star-rating :style="{position: 'relative', top: 3 + 'px'}" :star-size="18" :read-only="true"
                                                            :show-rating="false" :rating="{{ $review->rating }}">
                                            </star-rating>
                                        </div>
                                        @endif
                                        <div class="detail-item"><i class="icon-material-outline-date-range"></i> {{ $review->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    @if($review->comment)
                                    <div class="item-description">
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endif
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    <div class="clearfix"></div>
                    <div class="pagination-container margin-top-40 margin-bottom-10">
                        {!! $reviews->links('vendor.pagination.uplance') !!}
                    </div>
                    <div class="clearfix"></div>
                    <!-- Pagination / End -->
                </div>
                @endif
                <!-- Boxed List / End -->
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-4">
                <div class="sidebar-container">
                    <!-- Profile Overview -->
                    <div class="profile-overview">
                        <div class="overview-item">
                        <strong>
                            <money-format :style="'display: inline-block;'" :value="{{ $freelancer->hourly_rate }}" locale='en' currency-code='USD' subunit-value=true :hide-subunits=true></money-format>
                        </strong><span>Hourly Rate</span></div>
                        <div class="overview-item"><strong>{{ $freelancer->jobs_done }}</strong><span>Jobs Done</span></div>
                        <div class="overview-item"><strong>{{ $freelancer->rehired }}</strong><span>Rehired</span></div>
                    </div>

                    <!-- Button -->
                    @auth
                    @if($freelancer->id != Auth::id() && Auth::user()->current_account == 'client')
                    <a href="{{ route('offers.new', $freelancer->hashid) }}" class="apply-now-button margin-bottom-50">Hire Freelancer <i class="icon-material-outline-arrow-right-alt"></i></a>
                    @endif
                    @else
                    <a href="{{ route('offers.new', $freelancer->hashid) }}" class="apply-now-button margin-bottom-50">Hire Freelancer <i class="icon-material-outline-arrow-right-alt"></i></a>
                    @endauth

                    <!-- Freelancer Indicators -->
                    <div class="sidebar-widget">
                        <div class="freelancer-indicators">

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->job_success }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->job_success }}"><span></span></div>
                                <span>Job Success</span>
                            </div>

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->recommendation }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->recommendation }}"><span></span></div>
                                <span>Recommendation</span>
                            </div>

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->on_time }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->on_time }}"><span></span></div>
                                <span>On Time</span>
                            </div>

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong>{{ $freelancer->on_budget }}%</strong>
                                <div class="indicator-bar" data-indicator-percentage="{{ $freelancer->on_budget }}"><span></span></div>
                                <span>On Budget</span>
                            </div>
                        </div>
                    </div>

                    <!-- Widget -->
                    <div class="sidebar-widget">
                        <h3>Skills</h3>
                        <div class="task-tags">
                            @foreach (explode(',', $freelancer->skills) as $skill)
                                <span>{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sidebar Widget -->
                    <div class="sidebar-widget">
                        @guest
                        <h3>Share</h3>
                        @else
                        <h3>Bookmark or Share</h3>
                        @endguest

                        <!-- Bookmark Button -->
                        @auth
                        @if($freelancer->id != Auth::id() && Auth::user()->current_account == 'client')
                        <bookmark hashid="{{ $freelancer->hashid }}" type="{{ User::class }}"></bookmark>
                        @endif
                        @endauth

                        <!-- Copy URL -->
                        <div class="copy-url">
                            <input id="copy-url" type="text" value="" class="with-border">
                            <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
                        </div>

                        <!-- Share Buttons -->
                        <social-share></social-share>
                    </div>
                </div>
            </div>

        </div>
     </div>
@endsection
