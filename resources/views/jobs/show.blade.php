@extends('layouts.app')

@section('content')
<!-- Titlebar
==================================================-->
<div class="single-page-header">
    <div class="container">
    <div class="row">
        <div class="col-md-12">
        <div class="single-page-header-inner">
            <div class="left-side">
            <div class="header-details">
            <h3>{{ $job->project_name }}</h3>
                <h5>About the Employer</h5>
                <ul>
                <li v-tippy="{placement: 'bottom',  arrow: true, maxWidth: 350, theme: 'light'}"
                content="This client has a good hiring experience.">
                    <div class="star-rating" data-rating="5.0">
                        <star-rating :style="{position: 'relative', top: 3 + 'px'}" 
                            :star-size="18" :read-only="true"
                            :show-rating="false" 
                            :rating="5">
                        </star-rating>
                    </div>
                </li>
                <li>
                    <img style="position: relative; top: 5px;" class="flag" src="/images/flags/{{ strtolower($job->user->country) }}.svg" alt /> 
                    <span style="position: relative; top: 6px;">{{ $job->country }}</span>
                </li>
                @if($job->payment_verified)
                <li>
                    <div class="verified-badge-with-title">Verified</div>
                </li>
                @endif
                </ul>
            </div>
            </div>
            <div class="right-side">
            <div class="salary-box">
                <div class="salary-type">Project Budget</div>
                @if($job->minimum === $job->maximum)
                <div class="salary-amount">
                    <money-format :style="'display: inline-block;'" 
                        :value="{{ $job->maximum }}" 
                        locale='en' 
                        currency-code='USD' 
                        subunit-value=true 
                        :hide-subunits=true>
                    </money-format>
                </div>
                @else
                <div class="salary-amount">
                    <money-format :style="'display: inline-block;'" 
                        :value="{{ $job->minimum }}" 
                        locale='en' 
                        currency-code='USD' 
                        subunit-value=true 
                        :hide-subunits=true>
                    </money-format>
                    -
                    <money-format :style="'display: inline-block;'" 
                        :value="{{ $job->maximum }}" 
                        locale='en' 
                        currency-code='USD' 
                        subunit-value=true 
                        :hide-subunits=true>
                    </money-format>
                </div>
                @endif
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<!-- Page Content
==================================================-->
<div class="container">
    <div class="row">
        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">
            <!-- Description -->
            <div class="single-page-section">
                <h3 class="margin-bottom-25">Project Description</h3>
                @php
                    $linkify = new \Nahid\Linkify\Linkify(['attr' => ['target' => '_blank']]);
                    echo nl2br($linkify->process($job->description));
                @endphp
            </div>

            <!-- Atachments -->
            @if(!$job->attachments->isEmpty())
            <div class="single-page-section">
                <h3>Attachments</h3>
                <div class="attachments-container">
                    @foreach ($job->attachments as $attachment)
                    <a target="_blank" href="{{ $attachment->file }}" class="attachment-box ripple-effect">
                        <span>{{ $attachment->name }}</span>
                        <i>{{ strtoupper($attachment->ext) }}</i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Skills -->
            <div class="single-page-section">
            <h3>Skills Required</h3>
            <div class="task-tags">
                @foreach (explode(',', $job->skills) as $skill)
                <span>{{ $skill }}</span>
                @endforeach
            </div>
            </div>
            <div class="clearfix"></div>

            <!-- Freelancers Bidding -->
			@if(!$job->proposals->isEmpty())
			<div class="boxed-list margin-bottom-60">
				<div class="boxed-list-headline">
					<h3><i class="icon-material-outline-group"></i> Freelancers Bidding</h3>
				</div>
				<ul class="boxed-list-ul">
				@foreach ($job->proposals as $bid)
				<li>
					<div class="bid">
						<!-- Avatar -->
						<div class="bids-avatar">
							<div class="freelancer-avatar">
							@if($bid->user->verified)
							<div class="verified-badge"></div>
							@endif
							<a target="_blank" href="#">
								<img style="width: 80px; height: 80px;" src="{{ $bid->user->avatar }}" alt="avatar"></a>
							</div>
						</div>

						<!-- Content -->
						<div class="bids-content">
							<!-- Name -->
							<div class="freelancer-name">
                                <h4>
                                    <a target="_blank" href="#">
                                        {{ $bid->user->first_name }} {{ $bid->user->last_name }} 
                                        
                                    </a>
                                    <img style="position: relative; bottom: 5px;" class="flag" 
                                        src="/images/flags/{{strtolower($bid->user->country)}}.svg"
                                        alt="flag" data-tippy-placement="top" 
                                        data-tippy
                                        v-tippy="{placement: 'top',  arrow: true, maxWidth: 350, theme: 'dark'}"
                                        content="{{ ucfirst(\PragmaRX\Countries\Package\Countries::where('cca2', $bid->user->country)->first()->name->common) }}"
                                        data-original-title="{{ ucfirst(\PragmaRX\Countries\Package\Countries::where('cca2', $bid->user->country)->first()->name->common) }}">
                                </h4>
								<div data-rating="5.0" class="star-rating">
									<star-rating :style="{position: 'relative', top: 3 + 'px'}" :star-size="18" :read-only="true"
													:show-rating="false" :rating="5.0">
									</star-rating>
								</div>
							</div>
						</div>

						<!-- Bid -->
						<div class="bids-bid">
							<div class="bid-rate">
								<div class="rate">
									@php
									$amount = 0;
									if ($bid->proposal_type == "Hourly Rate")
									{
										$amount = $bid->hourly_amount;
									} else {
										$amount = $bid->fixed_amount;
									}
									@endphp
									<money-format :style="'display: inline-block;'" :value="{{ $amount }}" locale='en' currency-code='USD' subunit-value=true :hide-subunits=true></money-format>
								</div>
                                <span>{{ $bid->proposal_type }}</span>
							</div>
						</div>
					</div>
				</li>
        		@endforeach
				</ul>
			</div>
			@endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
                @if($job->proposals()->withTrashed()->where('user_id', \Auth::id())->first())
                <div class="notification notice">
                    <p>You have already submitted a proposal to this job.</p>
                    <a class="close"></a>
                </div>
                @else
                @if((int)Auth::id() !== (int)$job->user_id && Auth::user()->current_account == 'freelancer')
                <a target="_blank" href="{{ route('proposals.index', $job->hashid) }}" class="apply-now-button">
                    Submit a proposal
                    <i class="icon-material-outline-arrow-right-alt"></i>
                </a>
                @endif
                @endif
                

                <div class="sidebar-widget">
                    <div class="job-overview">
                        <div class="job-overview-headline">Details</div>
                        <div class="job-overview-inner">
                            <ul>
                                <li><i class="icon-material-outline-location-on"></i>
                                    <span>Client Location</span>
                                    <h5>{{ $job->user->city }}, {{ $job->country }}</h5>
                                </li>
                                @if($job->location)
                                <li>
                                    <i class="icon-material-outline-location-on"></i>
                                    <span>Required Location</span>
                                    <h5>{{ $job->location }}</h5>
                                </li>
                                @endif
                                <li>
                                    <i class="icon-material-outline-business-center"></i>
                                    <span>Job Type</span> <h5>{{ $job->project_type }}</h5>
                                </li>
                                @if($job->user->spent)
                                <li>
                                    <i class="icon-material-outline-local-atm"></i>
                                    <span>Total Spent</span>
                                    <h5>
                                        <money-format 
                                            :style="'display: inline-block;'" 
                                            :value="{{ $job->user->spent }}" 
                                            locale='en' 
                                            currency-code='USD' 
                                            subunit-value=true 
                                            :hide-subunits=true>
                                        </money-format>
                                    </h5>
                                </li>
                                @endif
                                <li>
                                    <i class="icon-material-outline-access-time"></i>
                                    <span>Date Posted</span> <h5>{{ \Carbon\Carbon::createFromDate((string)$job->created_at)->isoFormat('LL') }}</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    @guest
                    <h3>Share</h3>
                    @else
                    @if ($job->user->id != Auth::id())
                    <h3>Bookmark or Share</h3>
                    @else
                    <h3>Share</h3>
                    @endif
                    @endguest

                    <!-- Bookmark Button -->
                    @auth
                    @if ($job->user->id != Auth::id() && Auth::user()->current_account == 'freelancer')
                    <bookmark hashid="{{ $job->hashid }}" type="{{ Job::class }}"></bookmark>
                    @endif
                    @endauth

                    <!-- Copy URL -->
					<div class="copy-url">
						<input id="copy-url" type="text" value="" class="with-border">
						<button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
					</div>

                    <!-- Social Share -->
                    <social-share url="{{ route('jobs.show', $job->hashid) }}"></social-share>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
