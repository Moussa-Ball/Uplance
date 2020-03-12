@extends('layouts.dashboard')

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
	<h3>Contract Offers</h3>
	<span class="margin-top-7">Offers from customers.</span>
</div>

<!-- Row -->
<div class="row">

	<!-- Dashboard Box -->
	<div class="col-xl-12">
		<div class="dashboard-box margin-top-0">

			<!-- Headline -->
			<div class="headline">
				<h3><i class="icon-material-outline-supervisor-account"></i> {{ ($offers->count() === 1) ? $offers->count().' Offer' : $offers->count().' Offers' }}</h3>
			</div>

			<div class="content">
				<ul class="dashboard-box-list">
					@foreach ($offers as $offer)
						<li>
							<!-- Overview -->
							<div class="freelancer-overview manage-candidates">
								<div class="freelancer-overview-inner">
									<!-- Avatar -->
									<div class="header-image freelancer-avatar">
										@if($offer->from->verified)
										<div class="verified-badge"></div>
										@endif
										@if($offer->from->current_account === 'freelancer')
										<a href="{{ route('freelancers.show', $offer->from->hashid) }}" target="_blank">
											<img style="width: 75px; height: 75px; border-radius: 50%;" src="{{ $offer->from->avatar }}" alt="avatar">
										</a>
										@else
										<a style="cursor: default;">
											<img style="width: 75px; height: 75px; border-radius: 50%; cursor: default;" src="{{ $offer->from->avatar }}" alt="avatar">
										</a>
										@endif
									</div>

									<!-- Name -->
									<div class="freelancer-name">
										<h4>
											@if($offer->from->current_account === 'freelancer')
											<a href="{{ route('freelancers.show', $offer->from->hashid) }}" target="_blank">
												{{ $offer->from->name }}
												<img class="flag" src="/images/flags/{{ $offer->from->country }}.svg" alt="" title="{{ $offer->from->country_name }}" data-tippy-placement="top">
											</a>
											@else
											<a style="cursor: default;">
												{{ $offer->from->name }}
												<img class="flag" src="/images/flags/{{ $offer->from->country }}.svg" alt="" title="{{ $offer->from->country_name }}" data-tippy-placement="top">
											</a>
											@endif
										</h4>

										<!-- Rating -->
										<div data-rating="{{ $offer->from->rating }}" class="star-rating">
											<star-rating :style="{position: 'relative', top: 2 + 'px'}" :star-size="18" :read-only="true"
															:show-rating="false" :rating="{{ $offer->from->rating }}">
											</star-rating>
										</div>

										<!-- Bid Details -->
										<ul class="dashboard-task-info bid-info">
											<li><strong>
											@php
												$amount = 0;
												if ($offer->offer_type == "Hourly Rate")
												{
													$amount = $offer->hourly_rate;
												} 
												else {	
													if ($offer->total_amount) {
														$amount = $offer->total_amount;
													} 
													else {
														foreach ($offer->milestones as $milestone) {
															$amount += $milestone['amount'];
														}
													}
												}
											@endphp
											<money-format :style="'display: inline-block;'" :value="{{ $amount }}" locale='en' currency-code='USD' subunit-value=true :hide-subunits=true></money-format>
											</strong><span>{{ $offer->offer_type }}</span></li>
											@if($offer->due_date)
											<li>
												<strong>Due date</strong>
												<span>{{ \Carbon\Carbon::createFromDate($offer->due_date)->format('Y-m-d')  }}</span>
											</li>
											@endif
											<li>
												<strong>Since</strong>
												<span>{{ \Carbon\Carbon::createFromDate((string)$offer->created_at)->diffForHumans()  }}</span>
											</li>
										</ul>

										@if($offer->milestones[0]['description'])
										<br>
										<table class="basic-table">
											<tr>
												<th>Description</th>
												<th>Due date</th>
												<th>Amount</th>
											</tr>
											@foreach($offer->milestones as $milestone)
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
											<h4 class="margin-bottom-40" style="text-decoration: underline">{{ $offer->contract_title }}</h4>
											{{ $offer->description }}
										</div>

										@if($offer->attachments)
										<div class="single-page-section">
											<div class="attachments-container">
												@foreach ($offer->attachments as $attachment)
												<a target="_blank" href="{{ $attachment->file }}" class="attachment-box ripple-effect">
													<span>{{ $attachment->name }}</span><i>{{ strtoupper($attachment->ext) }}</i>
												</a>
												@endforeach
											</div>
										</div>
										@endif

										<!-- Buttons -->
										@if(!$offer->accepted)
										<div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">
											<form action="{{ route('contracts.store', $offer->hashid) }}" method="POST" class="hire-form" style="display: none;">
												@csrf
											</form>
											<a href="{{ route('contracts.store', $offer->hashid) }}" class="button ripple-effect"
												onclick="event.preventDefault(); document.getElementsByClassName('hire-form')[0].submit(); $(this).remove();">
												<i class="icon-material-outline-check"></i> 
												Accept Offer
											</a>
                                            <a href="{{ route('messages.index') }}" class="button dark ripple-effect"><i class="icon-feather-mail"></i> Send Message</a>
											<a href="{{ route('offers.destroy', $offer->hashid) }}" onclick="return confirm('Are you sure?')" class="button gray ripple-effect ico" title="Decline the offer" data-tippy-placement="top"><i class="icon-feather-trash-2"></i></a>
										</div>
										@else
										<div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">
                                            <a disabled href="{{ route('messages.index') }}" class="button dark ripple-effect"><i class="icon-feather-mail"></i> Send Message</a>
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
	</div>
</div>
<!-- Row / End -->

{!! $offers->links('vendor.pagination.uplance') !!}
@endsection
