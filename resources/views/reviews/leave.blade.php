@extends('layouts.dashboard')

@section('content')
<div class="dashboard-headline">
    <h3>Leave a review</h3>
</div>
<div class="popup-tab-content" id="tab2">
    <div class="welcome-text">
        <h3>Leave a review for</h3>
        <span>Rate 
            @if($review->contract->from_id == Auth::id())
            <a target="_blank" href="{{ route('freelancers.show', $review->contract->to->hashid) }}">
                {{ $review->contract->to->name }}
            </a>
            @else
            your client 
            @endif for the project: 
            <a target="_blank" href="{{ route('contracts.show', $review->contract->hashid) }}">
                {{ $review->contract->title }}
            </a>
        </span>
    </div>

<store-review :id="{{ $review->id }}" :freelancer="{{ ($review->contract->from_id == Auth::id()) ? true : false }}"></store-review>
</div>
@endsection
