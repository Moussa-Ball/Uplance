@extends('layouts.dashboard')

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
    <h3>Bookmarks</h3>
</div>

<!-- Row -->
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-business-center"></i> Bookmarked Jobs ({{ count($bookmarkJobs) }})</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @foreach($bookmarkJobs as $bookmark)
                    <li>
                        <!-- Job Listing -->
                        <div class="job-listing">

                            <!-- Job Listing Details -->
                            <div class="job-listing-details">

                                <!-- Details -->
                                <div class="job-listing-description">
                                    <h3 class="job-listing-title"><a target="_blank" href="{{ route('jobs.show', $bookmark[0]->hashid) }}">{{ $bookmark[0]->project_name }}</a></h3>

                                    <!-- Job Listing Footer -->
                                    <div class="job-listing-footer">
                                        <ul>
                                            <li><i class="icon-material-outline-location-on"></i> {{ $bookmark[0]->user->country_name }}</li>
                                            <li><i class="icon-material-outline-access-time"></i> {{ \Carbon\Carbon::createFromDate((string)$bookmark[0]->created_at)->diffForHumans() }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Buttons -->
                        <div class="buttons-to-right">
                            <a href="{{ route('bookmarks.delete', $bookmark[1]->id) }}" class="button red ripple-effect ico" title="Remove" data-tippy-placement="left"><i class="icon-feather-trash-2"></i></a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-face"></i> Bookmarked Freelancers ({{ count($bookmarkFreelancers) }})</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @foreach($bookmarkFreelancers as $bookmark)
                    <li>
                        <!-- Overview -->
                        <div class="freelancer-overview">
                            <div class="freelancer-overview-inner">

                                <!-- Avatar -->
                                <div class="freelancer-avatar">
                                    @if($bookmark[0]->verified)
                                    <div class="verified-badge"></div>
                                    @endif
                                    <a target="_blank" href="{{ route('freelancers.show', $bookmark[0]->hashid) }}">
                                        <img style="border-radius: 50%; width: 90px; height: 80px;" src="{{ $bookmark[0]->avatar }}" alt="">
                                    </a>
                                </div>

                                <!-- Name -->
                                <div class="freelancer-name">
                                    <h4><a target="_blank" href="{{ route('freelancers.show', $bookmark[0]->hashid) }}">{{ $bookmark[0]->first_name }} {{ $bookmark[0]->last_name }}
                                    <img class="flag" src="/images/flags/{{ strtolower($bookmark[0]->country) }}.svg" alt="flag" title="{{ $bookmark[0]->country_name }}" data-tippy-placement="top"></a> </h4>
                                    <span>{{ $bookmark[0]->tagline }}</span>
                                    <!-- Rating -->
                                    <div class="freelancer-rating">
                                        <div data-rating="{{ $bookmark[0]->rating }}" class="star-rating">
                                            <star-rating :style="{position: 'relative', top: 2 + 'px'}" :star-size="18" :read-only="true"
                                                            :show-rating="false" :rating="{{ $bookmark[0]->rating }}">
                                            </star-rating>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="buttons-to-right">
                        <a href="{{ route('bookmarks.delete', $bookmark[1]->id) }}" class="button red ripple-effect ico" title="Remove" data-tippy-placement="left"><i class="icon-feather-trash-2"></i></a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Row / End -->
@endsection
