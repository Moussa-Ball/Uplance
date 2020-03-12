@extends('layouts.app')

@section('content')
<div id="titlebar" class="gradient"></div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="order-confirmation-page">
				<div class="breathing-icon"><i class="icon-feather-check"></i></div>
				<h2 class="margin-top-30">Thank you for your payment!</h2>
				<p>Your payment has been processed successfully.</p>
				<a target="_blank" href="{{ route('invoices.view', $invoice->order) }}" class="button ripple-effect-dark button-sliding-icon margin-top-30">View Invoice <i class="icon-material-outline-arrow-right-alt"></i></a>
			</div>

		</div>
	</div>
</div>
@endsection
