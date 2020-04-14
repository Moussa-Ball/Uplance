
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
                        @if($balance)
                        <a href="{{ route('withdraws.paid') }}" class="button">Get Paid</a>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="margin-top-60"></div>

@if(!$methods->isEmpty())
<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-line-awesome-money"></i> Deboursement Methods </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    @foreach ($methods as $method)
                    <li style="display: flex; justify-content: space-between;">
                        <div>
                            <div class="submit-field">
                                <br>
                                <strong>{{ ucfirst($method->brand) }}</strong>
                            </div>
                        </div>
                        <div>
                            <div class="submit-field">
                                <br>
                                <strong>Ending in {{ $method->last_four }}</strong>
                            </div>
                        </div>
                        @if(!$method->default && strtoupper($method->currency) == strtoupper($currency))
                        <div>
                            <div class="submit-field">
                                <br>
                                <a href="{{ route('withdraws.default', $method->id) }}" class="button">Default</a>
                                <a href="{{ route('withdraws.remove', $method->id) }}" class="button red">Remove</a>
                            </div>
                        </div>
                        @elseif(!$method->default && strtoupper($method->currency) != strtoupper($currency))
                        <div>
                            <div class="submit-field">
                                <br>
                                <a href="{{ route('withdraws.remove', $method->id) }}" class="button red">Remove</a>
                            </div>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<div class="margin-top-60"></div>

<form action="{{ route('withdraws.add') }}" method="post" id="form-add-card">
    @csrf
    <div class="row">
        <div class="col-xl-12">
            <div class="dashboard-box margin-top-0">
                <!-- Headline -->
                <div class="headline">
                    <div class="row">
                        <h3>
                            <i class="icon-line-awesome-money"></i> Add Credit Card
                        </h3>
                    </div>
                </div>
                <div class="content">
                    <div class="container margin-top-40 margin-top-40 margin-bottom-40">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Stripe Elements Placeholder -->
                                <div id="card-element"></div>

                                <input type="hidden" name="token" id="card_token">
                                <button class="button margin-top-40 margin-bottom-40" type="button" id="card-button">
                                    Add Method
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="margin-top-20"></div>

<form action="{{ route('withdraws.add') }}" method="post" id="form-add-iban">
    @csrf
    <div class="row">
        <div class="col-xl-12">
            <div class="dashboard-box margin-top-0">
                <!-- Headline -->
                <div class="headline">
                    <div class="row">
                        <h3>
                            <i class="icon-line-awesome-money"></i> Add Bank Account
                        </h3>
                    </div>
                </div>
                <div class="content">
                    <div class="container margin-top-40 margin-top-40 margin-bottom-40">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="section-headline margin-top-25 margin-bottom-12">
                                        <h5>IBAN</h5>
                                    </div>
                                    <div id="iban-element"></div>
                                </div>
                                <div id="bank-name"></div>
                                <input type="hidden" name="token" id="account_iban_token">
                                <button class="button margin-top-40 margin-bottom-40" type="button" id="iban-button">
                                    Add Method
                                </button>
                                <div id = "error-message" role = "alert"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="margin-top-20"></div>

<form action="{{ route('withdraws.add') }}" method="post" id="form-add-bank">
    @csrf
    <div class="row">
        <div class="col-xl-12">
            <div class="dashboard-box margin-top-0">
                <!-- Headline -->
                <div class="headline">
                    <div class="row">
                        <h3>
                            <i class="icon-line-awesome-money"></i> Add Bank Account
                        </h3>
                    </div>
                </div>
                <div class="content">
                    <div class="container margin-top-40 margin-top-40 margin-bottom-40">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="section-headline margin-top-25 margin-bottom-12">
                                    <h5>Routing Number</h5>
                                </div>
                                <input class="with-border" placeholder="Tape your routing number..." id="routing_number-number">
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="section-headline margin-top-25 margin-bottom-12">
                                    <h5>Account Number</h5>
                                </div>
                                <input class="with-border" placeholder="Tape your account number..." id="account-number">
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="token" id="account_bank_token">
                                <button class="button margin-top-40 margin-bottom-40" type="button" id="bank-button">
                                    Add Method
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('js')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements({
        locale: 'en'
    });

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '18px',
                '::placeholder': {
                color: '#aab7c4'
            },
            ':-webkit-autofill': {
                color: '#32325d',
            },
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a',
            ':-webkit-autofill': {
            color: '#fa755a',
            },
        }
    };
    
    const cardElement = elements.create('card', {style: style});
    cardElement.mount('#card-element');

    // Create an instance of the iban Element.
    var ibanElement = elements.create('iban', {
        style: style,
        supportedCountries: ['SEPA'],
    });
    ibanElement.mount('#iban-element');

    var bankName = document.getElementById('bank-name');
    var errorMessage = document.getElementById('error-message');

    const cardButton = document.getElementById('card-button');
    const ibanButton = document.getElementById('iban-button');
    const bankButton = document.getElementById('bank-button');

    cardButton.addEventListener('click', async function (e) {
        await stripe.createToken(cardElement, {
            currency: "{{ $currency }}",
            name: '{{ Auth::user()->name }}',
        }).then(function(result) {
            if (result.token) {
                $('#card_token').val(result.token.id)
                $('#form-add-card').submit()
            }

            if (result.error) {
                new Noty({
                    text: `<strong>${result.error.message}</strong>`,
                    type: 'error',
                    theme: 'metroui',
                    progressBar: true,
                    timeout: 5000,
                }).show();
            }
        });
    });

    ibanButton.addEventListener('click', async function (e) {
        await stripe.createToken(ibanElement, {
            currency: 'EUR',
            account_holder_name: '{{ Auth::user()->name }}',
            account_holder_type: 'individual',
        }).then(function(result) {
            if (result.token) {
                $('#account_iban_token').val(result.token.id)       
                $('#form-add-iban').submit()
            }

            if (result.error) {
                new Noty({
                    text: `<strong>${result.error.message}</strong>`,
                    type: 'error',
                    theme: 'metroui',
                    progressBar: true,
                    timeout: 5000,
                }).show();
            }
        })
    });

    bankButton.addEventListener('click', async function (e) {
        if (!document.getElementById('routing_number-number').value
            || !document.getElementById('account-number').value) {
                return new Noty({
                    text: `<strong>Not all fields are valid.</strong>`,
                    type: 'error',
                    theme: 'metroui',
                    progressBar: true,
                    timeout: 5000,
                }).show();
        }

        await stripe.createToken('bank_account', {
            country: '{{ Auth::user()->country }}',
            currency: '{{ $currency }}',
            routing_number: document.getElementById('routing_number-number').value,
            account_number: document.getElementById('account-number').value,
            account_holder_name: '{{ Auth::user()->name }}',
            account_holder_type: 'individual',
        }).then(function(result) {
            if (result.token) {
                $('#account_bank_token').val(result.token.id)       
                $('#form-add-bank').submit()
            }
            
            if (result.error) {
                new Noty({
                    text: `<strong>${result.error.message}</strong>`,
                    type: 'error',
                    theme: 'metroui',
                    progressBar: true,
                    timeout: 5000,
                }).show();
            }
        })
    });
</script>
@endsection
