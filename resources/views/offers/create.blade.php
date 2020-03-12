@extends('layouts.dashboard')

@section('content')
@if($proposal)
<hire-freelancer 
    :freelancer="{{ json_encode($user) }}" 
    :proposal="{{ json_encode($proposal->hashid) }}" 
    :job="{{ json_encode($proposal->job->hashid) }}">
</hire-freelancer>
@else
<hire-freelancer 
    :freelancer="{{ json_encode($user) }}" 
    :proposal="{{ json_encode(null) }}" 
    :job="{{ json_encode(null) }}">
</hire-freelancer>
@endif
@endsection
