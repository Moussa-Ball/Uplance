
@extends('layouts.dashboard')

@section('content')
<div class="dashboard-headline">
    <h3>Get Paid</h3>
    <span>Your current balance</span>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-line-awesome-money"></i> Your Balance </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <li style="display: flex; justify-content: space-between;">
                        <strong>Your current balance is 
                        @php
                            $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
                            echo $fmt->formatCurrency($balance, 'USD');
                        @endphp.
                        </strong>
                        @if($balance && $methods && $methods->default_method)
                        @if($methods->default_method == 'paypal' && $methods->paypal_activation_date <= \Carbon\Carbon::now() || 
                            $methods->default_method == 'credit_card' && $methods->credit_card_activation_date <= \Carbon\Carbon::now())
                        <a href="{{ route('withdraw.get') }}" class="button">Get Paid</a>
                        @endif
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="margin-top-60"></div>

<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-line-awesome-money"></i> Add Methods </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <li style="display: flex; justify-content: space-between;">
                        <div>
                            <img src="/images/paypal.png" alt="paypal">
                        </div>
                        <div>
                            <div class="submit-field">
                                <br>
                                <strong>{{ Auth::user()->email }}</strong>
                                @if($methods && $methods->default_method == 'paypal')
                                <span>(Default)</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="submit-field">
                                <br>
                                @if(!$methods||$methods && !$methods->paypal)
                                <a href="{{ route('withdraw.enable.paypal') }}" class="button">Activate</a>
                                @elseif($methods && $methods->paypal && $methods->paypal_activation_date <= \Carbon\Carbon::now())
                                @if($methods && $methods->default_method != 'paypal')
                                <a href="{{ route('withdraw.default.paypal') }}" class="button">Default</a>
                                @endif
                                <a href="{{ route('withdraw.remove.paypal') }}" class="button red">Remove</a>
                                @elseif($methods && $methods->paypal && $methods->paypal_activation_date > \Carbon\Carbon::now())
                                <strong>Activation in  {{ \Carbon\Carbon::parse($methods->paypal_activation_date)->diffForHumans(\Carbon\Carbon::now()) }}</strong>
                                @endif
                            </div>
                        </div>
                    </li>
                    @if(Auth::user()->defaultPaymentMethod())
                    <li style="display: flex; justify-content: space-between;">
                        <div>
                            @if(Auth::user()->defaultPaymentMethod()->card->brand == 'visa')
                            <img src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @elseif(Auth::user()->defaultPaymentMethod()->card->brand == 'mastercard')
                            <img src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @elseif(Auth::user()->defaultPaymentMethod()->card->brand == 'amex')
                            <img src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @elseif(Auth::user()->defaultPaymentMethod()->card->brand == 'discover')
                            <img src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @elseif(Auth::user()->defaultPaymentMethod()->card->brand == 'diners')
                            <img src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @elseif(Auth::user()->defaultPaymentMethod()->card->brand == 'jcb')
                            <img src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @elseif(Auth::user()->defaultPaymentMethod()->card->brand == 'unionpay')
                            <img width="128px" height="80px" src="/images/{{ Auth::user()->defaultPaymentMethod()->card->brand }}.png" alt="{{ Auth::user()->defaultPaymentMethod()->card->brand }}">
                            @else
                            <br>
                            <strong>{{ ucfirst(Auth::user()->defaultPaymentMethod()->card->brand) }}</strong>
                            @endif
                        </div>
                        <div>
                            <div class="submit-field">
                                <br>
                                <strong>{{ ucfirst(Auth::user()->defaultPaymentMethod()->card->brand) }} ending in {{ Auth::user()->defaultPaymentMethod()->card->last4 }}</strong>
                                @if($methods && $methods->default_method == 'credit_card')
                                <span>(Default)</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="submit-field">
                                <br>
                                @if(!$methods||$methods && !$methods->credit_card)
                                <a href="{{ route('withdraw.enable.credit-card') }}" class="button">Activate</a>
                                @elseif($methods && $methods->credit_card && $methods->credit_card_activation_date <= \Carbon\Carbon::now())
                                @if($methods && $methods->default_method != 'credit_card')
                                <a href="{{ route('withdraw.default.credit-card') }}" class="button">Default</a>
                                @endif
                                <a href="{{ route('withdraw.remove.credit-card') }}" class="button red">Remove</a>
                                @elseif($methods && $methods->credit_card && $methods->credit_card_activation_date > \Carbon\Carbon::now())
                                <strong>Activation in  {{ \Carbon\Carbon::parse($methods->credit_card_activation_date)->diffForHumans(\Carbon\Carbon::now()) }}</strong>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
