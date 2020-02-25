@extends('layouts.dashboard')

@section('content')
<div class="dashboard-headline">
    <h3>Manage Bidders</h3>
    <span class="margin-top-7">Bids for <a target="_blank" href="{{ route('jobs.show', $job->hashid) }}">{{ (strlen($job->project_name ) > 50) ? substr($job->project_name, 0, 50).'...' : $job->project_name }}</a></span>
    </nav>
</div>

<!-- Row -->
<div class="row">

    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-supervisor-account"></i> {{ ($job->proposals->count() > 1) ? $job->proposals->count().' Bidders' : $job->proposals->count().' Bidder' }}</h3>
            </div>

            <div class="content">
                <ul class="dashboard-box-list">
                    @foreach ($proposals as $bid)
                    <li>
                        <!-- Overview -->
                        <div class="freelancer-overview manage-candidates">
                            <div class="freelancer-overview-inner">

                                <!-- Avatar -->
                                <div class="freelancer-avatar">
                                    @if($bid->user->verified)
                                    <div class="verified-badge"></div>
                                    @endif
                                    <a href="#"><img style="width: 78px; height: 78px; border-radius: 50%;" src="{{ $bid->user->avatar }}" alt=""></a>
                                </div>

                                <!-- Name -->
                                <div class="freelancer-name">
                                    <h4><a href="#">{{ $bid->user->name }} <img class="flag" src="/images/flags/{{strtolower($bid->user->country)}}.svg" alt="" title="{{ $bid->user->country_name }}" data-tippy-placement="top"></a>   
                                    @if($bid->accepted)
                                    <mark class="color">Hired</mark>
                                    @endif</h4>

                                    <!-- Rating -->
                                    <div data-rating="{{ $bid->user->rating }}" class="star-rating">
                                        <star-rating :style="{position: 'relative', top: 3 + 'px'}" :star-size="18" :read-only="true"
                                                     :show-rating="false" :rating="{{ $bid->user->rating }}">
                                        </star-rating>
                                    </div>

                                    <!-- Minimum Rating -->
                                    <!--<br>
                                    <span class="company-not-rated">Minimum of 3 votes required</span>-->

                                    <!-- Bid Details -->
                                    <ul class="dashboard-task-info bid-info">
                                        <li><strong>
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
                                        </strong><span>{{ $bid->proposal_type }}</span></li>
                                        @if($bid->due_date)
                                        <li>
                                            <strong>Due date</strong>
                                            <span>{{ \Carbon\Carbon::createFromDate($bid->due_date)->format('Y-m-d')  }}</span>
                                        </li>
                                        @endif
                                        <li>
                                            <strong>Since</strong>
                                            <span>{{ \Carbon\Carbon::createFromDate((string)$bid->created_at)->diffForHumans()  }}</span>
                                        </li>
                                    </ul>

                                    @if($bid->milestones)
                                    <br>
                                    <table class="basic-table">
                                        <tr>
                                            <th>Description</th>
                                            <th>Due date</th>
                                            <th>Amount</th>
                                        </tr>
                                        @foreach($bid->milestones as $milestone)
                                            <tr>
                                                <td data-label="Column 1">{{ $milestone['description']  }}</td>
                                                <td data-label="Column 2">{{ \Carbon\Carbon::createFromDate($milestone['due_date'])->format('Y-m-d')  }}</td>
                                                <td data-label="Column 3"><money-format :style="'display: inline-block;'" :value="{{ $milestone['amount'] }}" locale='en' currency-code='USD' subunit-value=true :hide-subunits=true></money-format></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    @endif

                                    <br>
                                    <br>

                                    <div class="single-page-section">
                                        {!! nl2br($bid->cover_letter) !!}
                                    </div>

                                    @if(!$bid->attachments->isEmpty())
                                    <div class="single-page-section">
                                        <div class="attachments-container">
                                            @foreach ($bid->attachments as $attachment)
                                            <a target="_blank" href="{{ $attachment->file }}" class="attachment-box ripple-effect">
                                                <span>{{ $attachment->name }}</span><i>{{ strtoupper($attachment->ext) }}</i>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Buttons -->
                                    @if(!$job->completed)
                                    <div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">
                                        @if(!$bid->accepted)
                                        <a href="{{ route('offers.proposal', ['job' => $job->hashid, 'proposal' => $bid->hashid]) }}" class="button ripple-effect"><i class="icon-material-outline-check"></i> Hire Freelancer</a>
                                        @endif
                                        <a href="{{ route('messages.create', ['job_id' => $job->hashid, 'proposal_id' => $bid->hashid]) }}" class="button dark ripple-effect"><i class="icon-feather-mail"></i> Send Message</a>
                                        @if(!$bid->accepted)
                                        <a href="{{ route('proposals.delete', ['job' => $job->hashid, 'proposal' => $bid->hashid]) }}" onclick="return confirm('Are you sure?')" class="button gray ripple-effect ico" title="Remove Bid" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
                                        @endif
                                    </div>
                                    @else
                                    <div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">
                                        <a href="{{ route('messages.create', ['job_id' => $job->hashid, 'proposal_id' => $bid->hashid]) }}" class="button dark ripple-effect"><i class="icon-feather-mail"></i> Send Message</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {!! $proposals->links('vendor/pagination/uplance') !!}
    </div>
</div>
@endsection
