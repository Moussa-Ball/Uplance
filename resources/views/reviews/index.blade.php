@extends('layouts.dashboard')

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
    <h3>Reviews</h3>
</div>

<!-- Row -->
<div class="row">
    <!-- Dashboard Box -->
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <h3><i class="icon-material-outline-face"></i>Reviews ({{ $reviews->count() }})</h3>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
					@foreach($reviews as $review)						
						<li>
							<div class="boxed-list-item">
								<!-- Content -->
								<div class="item-content">
									<h4>{{$review->contract->title }}</h4>
									@if(!$review->rated)
									<span class="company-not-rated margin-bottom-5">Not Rated</span>
									@else
									<div class="item-details margin-top-10">
										<div class="star-rating" data-rating="{{ number_format($review->rating, 1, '.', '') }}"></div>
										<star-rating :style="{position: 'relative', top: 1 + 'px', 'right': 15 + 'px'}"
											:star-size="18" :read-only="true"
											:show-rating="false"
											:increment="0.01" :fixed-points="2"
											:rating="{{ number_format($review->rating, 1, '.', '') }}">
										</star-rating>
										<div class="detail-item"><i class="icon-material-outline-date-range"></i>{{ \Carbon\Carbon::createFromDate((string)$review->updated_at)->isoFormat('LLLL') }}</div>
									</div>
									<div class="item-description">
										<p>{{ $review->comment }}</p>
									</div>
									@endif
								</div>
							</div>
							@if(!$review->rated)
							<a href="{{ route('reviews.leave', $review->id) }}" class="button ripple-effect margin-top-5 margin-bottom-10"><i class="icon-material-outline-thumb-up"></i> Leave a Review</a>
							@endif
						</li>
					@endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
{{ $reviews->links('vendor.pagination.uplance') }}
@endsection
