@extends('layouts.dashboard')

@section('content')
<!-- Dashboard Headline -->
<div class="dashboard-headline">
	<h3>Invoices</h3>
	<span>The list of all your invoices.</span>
</div>

<div class="row">
	<div class="col-xl-12">
		<div class="dashboard-box">
			<div class="headline">
				<h3><i class="icon-material-outline-assignment"></i> Invoices ({{ $invoices->count() }})</h3>
			</div>
			<div class="content">
				<ul class="dashboard-box-list">
					@foreach($invoices as $invoice)
					<li>
						<div class="invoice-list-item">
						<strong>{{ $invoice->description }}</strong>
							<ul>
								@if($invoice->paid_at)
								<li><span class="paid">Paid</span></li>
								@else
								<li><span class="unpaid">Unpaid</span></li>
								@endif
								<li>Order: #{{ $invoice->order }}</li>
								<li>Date: {{ \Carbon\Carbon::createFromDate((string)$invoice->created_at)->isoFormat('dddd, MMMM Do YYYY') }}</li>
							</ul>
						</div>
						<div class="buttons-to-right">
							@if($invoice->paid_at)
							<a target="_blank" href="{{ route('invoices.show', $invoice->hashid) }}" class="button gray">View Invoice</a>
							@endif
							<a target="_blank" href="{{ route('contracts.show', $invoice->contract->hashid) }}" class="button gray">See the associated contract</a>
						</div>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>
{{ $invoices->links('vendor/pagination/uplance') }}
@endsection
