@extends('layouts.dashboard')

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
    <h3>Manage Jobs</h3>
</div>

<!-- Row -->
<div class="row">
<!-- Dashboard Box -->
    <div class="col-xl-12">
        @if(!$jobs->isEmpty())
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-business-center"></i> My Projects</h3>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    @foreach ($jobs as $job)
                    <li>
                        <!-- Job Listing -->
                        <div class="job-listing width-adjustment">

                            <!-- Job Listing Details -->
                            <div class="job-listing-details">

                                <!-- Details -->
                                <div class="job-listing-description">
                                <h3 class="job-listing-title"><a target="_blank" href="{{ route('jobs.show', $job->hashid) }}">{{ $job->project_name }}</a></h3>
                                    <!-- Job Listing Footer -->
                                    <div class="job-listing-footer">
                                        <ul>
                                        <li><i class="icon-material-outline-access-time"></i>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($job->created_at))->diffForHumans() }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <!-- Task Details -->
                        <ul class="dashboard-task-info">
                            <li><strong>{{ $job->proposals->count() }}</strong><span>Bids</span></li>
                            <li><strong>
                                @php
                                    $min = 0;
                                    if ($job->project_type == "Hourly Rate")
                                    {
                                        $avgs = array();

                                        foreach($job->proposals as $i => $bid)
                                        {
                                            $avgs[$i] = $bid['hourly_amount'];
                                            $min = min($avgs);
                                        }

                                        if (!$min)
                                            $min = 0;
                                    } else {
                                        $avgs = array();

                                        foreach($job->proposals as $i => $bid)
                                        {
                                            $avgs[$i] = $bid['fixed_amount'];
                                            $min = min($avgs);
                                        }

                                        if (!$min)
                                            $min = 0;
                                    }
                                @endphp
                                <money-format :style="'display: inline-block;'" :value="{{ $min }}" locale='en' currency-code='USD' subunit-value=true :hide-subunits=true></money-format>
                                </strong><span>Avg. Bid</span>
                            </li>
                            <li>
                                <strong>
                                    @if($job->minimum === $job->maximum)
                                    <money-format :style="'display: inline-block;'" 
                                        :value="{{ $job->maximum }}" 
                                        locale='en' 
                                        currency-code='USD' 
                                        subunit-value=true 
                                        :hide-subunits=true>
                                    </money-format>
                                    @else
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
                                    @endif
                                </strong>
                                <span>{{ $job->project_type }}</span>
                            </li>
                        </ul>
                        <!-- Buttons -->
                        <div class="buttons-to-right always-visible">
                            @if(!$job->proposals->isEmpty())
                            <a href="{{ route('proposals.list', $job->hashid) }}" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> Manage Bidders <span class="button-info">{{ $job->proposals->count() }}</span></a>
                            @endif
                            <a href="{{ route('jobs.edit', $job) }}" class="button gray ripple-effect ico" title="Edit" data-tippy-placement="top"><i class="icon-feather-edit"></i></a>
                            <a href="{{ route('jobs.delete', $job) }}"class="button gray ripple-effect ico"  title="Remove" data-tippy-placement="top" onclick="return confirm('Are you sure?')"><i class="icon-feather-trash-2"></i></a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @else
        <br>
        <h1>Sorry, but you have not published any projects.</h1>
        @endif
    </div>
</div>

{!! $jobs->links('vendor.pagination.uplance') !!}
@endsection
