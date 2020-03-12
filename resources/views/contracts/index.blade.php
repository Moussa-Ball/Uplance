@extends('layouts.dashboard')

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
    <h3>Contracts</h3>
    <span>Your contracts</span>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">

            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-material-outline-assessment"></i> {{ ($contracts->count() > 1) ? $contracts->count().' Contracts' : $contracts->count().' Contract' }} {{ (!$contracts) ? '0 Contract' : ''}}</h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <li>
                        <table class="basic-table">
                            <tr>
                                <th>Contract Title</th>
                                <th>Client</th>
                                <th>Freelancer</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($contracts as $contract)
                                <tr>
                                    <td data-label="Column 1">
                                        @if($contract->job)
                                        <a target="_blank" href="/find-work/{{ $contract->job->slug }}-{{ $contract->job->id }}">{{ $contract->job->project_name }}</a>
                                        @else
                                        {{ $contract->title }}
                                        @endif
                                    </td>
                                    <td data-label="Column 2">{{ $contract->from->first_name }} {{ $contract->from->last_name }}</td>
                                    <td data-label="Column 2"><a target="_blank" href="/find-freelancer/profile/freelancer~{{ $contract->to->id }}">{{ $contract->to->first_name }} {{ $contract->to->last_name }}</a></td>
                                    <td data-label="Column 2">{{ $contract->type }}</a></td>
                                    <td data-label="Column 3"><a href="{{ route('contracts.show', $contract->hashid) }}" class="button">See</a></td>
                                </tr>
                            @endforeach
                        </table>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{!! $contracts->links('vendor.pagination.uplance') !!}
@endsection
