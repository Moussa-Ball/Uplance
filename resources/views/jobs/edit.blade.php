@extends('layouts.dashboard')

@section('content')
<edit-job :data="{{ json_encode($job) }}"></edit-job>
@endsection
