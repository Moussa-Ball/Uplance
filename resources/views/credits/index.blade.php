@extends('layouts.dashboard')

@section('content')
<div class="dashboard-headline">
    <h3>Buy Credit</h3>
    <span>Your current credit</span>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="dashboard-box margin-top-0">
            <!-- Headline -->
            <div class="headline">
                <div class="row">
                    <h3><i class="icon-line-awesome-money"></i> Your Credit </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <li style="display: flex; justify-content: space-between;">
                        <strong>Your current credit is {{ Auth::user()->credit }}.</strong>
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
                    <h3><i class="icon-line-awesome-money"></i> Add Credit </h3>
                </div>
            </div>
            <div class="content">
                <ul class="dashboard-box-list">
                    <li>
                        <form method="POST" action="{{ route('credit.buy') }}" style="display: flex; justify-content: space-between; width: 100%;">
                            @csrf
                            <div style="width: 250px;">
                                <select name="option" class="selectpicker">
                                    <option value="">Choose an option</option>
                                    <option value="1">10 credits/$1</option>
                                    <option value="2">20 credits/$2</option>
                                    <option value="3">30 credits/$3</option>
                                    <option value="4">100 credits/$10</option>
                                </select>
                            </div>
                            <button type="submit" style="width: 250px; margin-left; 50px;" class="button big">Add Credit</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
