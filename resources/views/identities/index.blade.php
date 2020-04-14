@extends('layouts.dashboard')

@section('content')
<div class="dashboard-headline">
    <h3>Identity</h3>
    <span>Verification of your identity.</span>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-feather-user-check"></i> Identities </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <li style="display: flex; justify-content: space-between;">
                        <div>
                            <div class="submit-field">
                                <br>
                                <img width="100px" height="45px" src="/images/stripe-brand/PNG/Stripe_logo_slate_sm.png" alt="stripe">
                            </div>
                        </div>
                        <div>
                            <div class="submit-field">
                                <br>
                                @if(Auth::user()->connect_verified)
                                <img height="35px" src="/images/pass.svg" alt="pass.svg">
                                @else
                                <a href="{{ route('identity.stripe.verify') }}" class="button">Check my identity</a>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
