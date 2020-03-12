@extends('layouts.dashboard')

@section('content')
<contract user="{{ $user }}" :contract="{{ $contract }}"></contract>
@endsection
