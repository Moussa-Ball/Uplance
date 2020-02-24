@extends('layouts.dashboard')

@section('content')
<submit-job :job="{{ json_encode($job) }}" :user="{{ json_encode($user) }}" url="{{ route('jobs.show', $job['id']) }}"></submit-job>
@endsection
