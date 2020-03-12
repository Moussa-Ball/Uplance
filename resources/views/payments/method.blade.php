@extends('layouts.dashboard')

@section('css')
<style>
    /**
    * The CSS shown here will not be introduced in the Quickstart guide, but shows
    * how you can use CSS to style your Element's container.
    */
    .StripeElement {
        padding: 15px 20px;
        outline: none;
        font-size: 22px;
        color: gray;
        margin: 0 0 16px;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
        display: block;
        background-color: #fff;
        font-weight: 500;
        opacity: 1;
        border-radius: 4px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,.05);
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement input {
        line-height: 48px;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 4px 0 rgba(0,0,0,.05);
    }

    .StripeElement--invalid {
        border-color: #e0e0e0;
    }

    .StripeElement--webkit-autofill {
        background-color: #fff !important;
    }   
</style>
@endsection

@section('content')
<form method="POST" action="{{ route('payments.add') }}" id="form-add-billing">
    @csrf
    <div class="dashboard-headline">
        <h3>Billing Method</h3>
        <span>Manage your billing method.</span>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="dashboard-box margin-top-0">
                <!-- Headline -->
                <div class="headline">
                    <div class="row">
                        <h3>
                            <i class="icon-line-awesome-money"></i> Add a credit card
                        </h3>
                    </div>
                </div>
                <div class="content">
                    <div class="container margin-top-40 margin-top-40 margin-bottom-40">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Stripe Elements Placeholder -->
                                <div id="card-element"></div>

                                <input type="hidden" name="payment_method" id="payment_method">
                                <button class="button margin-top-40 margin-bottom-40" type="button" id="card-button" data-secret="{{ $intent->client_secret }}">
                                    Add Payment Method
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@if(!$payment_methods->isEmpty())
<div class="margin-top-40"></div>
<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-line-awesome-money"></i> Payment Methods Details </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    @foreach ($payment_methods as $key => $method)
                        <li style="display: flex; justify-content: space-between;">
                        <div class="col-xl-4">
                            @if($method->card->brand == 'visa')
                            <img src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @elseif($method->card->brand == 'mastercard')
                            <img src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @elseif($method->card->brand == 'amex')
                            <img src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @elseif($method->card->brand == 'discover')
                            <img src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @elseif($method->card->brand == 'diners')
                            <img src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @elseif($method->card->brand == 'jcb')
                            <img src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @elseif($method->card->brand == 'unionpay')
                            <img width="128px" height="80px" src="/images/{{ $method->card->brand }}.png" alt="{{ $method->card->brand }}">
                            @else
                            <br>
                            <strong>{{ ucfirst($method->card->brand) }}</strong>
                            @endif
                        </div>
                        <div class="col-xl-4">
                            <div class="submit-field">
                                <br>
                                <strong>{{ ucfirst($method->card->brand) }} ending in {{ $method->card->last4 }}</strong>
                                @if(Auth::user()->defaultPaymentMethod() && Auth::user()->defaultPaymentMethod()->id == $method->id)
                                <span>(Default)</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="submit-field">
                                <br>
                                @if(!Auth::user()->defaultPaymentMethod() || Auth::user()->defaultPaymentMethod()->id != $method->id)
                                <form method="POST" action="{{ route('payments.default')}}" class="form-default-payment-method-{{$key}}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="{{ $method->id }}">
                                </form>
                                <button class="button" onclick="event.preventDefault(); document.getElementsByClassName('form-default-payment-method-{{$key}}')[0].submit();">Default</button>
                                @endif
                                <form method="POST" action="{{ route('payments.remove')}}" class="form-remove-payment-method-{{$key}}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="{{ $method->id }}">
                                </form>
                                <button class="button red" onclick="event.preventDefault(); document.getElementsByClassName('form-remove-payment-method-{{$key}}')[0].submit();">Remove</button>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('js')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const cardButton = document.getElementById('card-button');
    const clientSecret = document.getElementById('card-button').dataset.secret;

    cardButton.addEventListener('click', async function (e) {
        const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: { name: "{{ Auth::user()->name }}" }
                }
            }
        );

        if (error) {
            console.log(error.message)
            new Noty({
                text: `<strong>${error.message}</strong>`,
                type: 'error',
                theme: 'metroui',
                progressBar: true,
                timeout: 5000,
            }).show();
        } else {
            const payment_method = setupIntent.payment_method
            $('#payment_method').val(payment_method)       
            $('#form-add-billing').submit()
        }
    });
</script>
@endsection
