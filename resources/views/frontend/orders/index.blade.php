@extends('frontend.layout')

@section('content')
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="background-image: url({{ asset('themes/ezone/assets/img/bg/breadcrumb.jpg') }})">
		<div class="container-fluid">
			<div class="breadcrumb-content text-center">
				<h2>Pesanan Saya</h2>
				<ul>
					<li><a href="{{ url('/') }}">home</a></li>
					<li>Pesanan Saya</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="shop-page-wrapper shop-page-padding ptb-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					@include('frontend.partials.user_menu')
				</div>
				<div class="col-lg-9">
					<div class="shop-product-wrapper res-xl">
						<div class="table-content table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
									<th> id pesanan</th>
									<th>Jumlah Total</th>
									<th>Nomer Resi</th>
									<th>Status</th>
									<th>Pembayaran</th>
									<th>Aksi</th>
								</thead>
								<tbody>
									@forelse($orders as $order)
										<tr>    
											<td>
												{{ $order->code }}<br>
												<span style="font-size: 12px; font-weight: normal"> {{ date('d M Y', strtotime($order->order_date)) }}</span>
											</td>
											<td>Rp{{ number_format($order->grand_total, 0, ",", ".") }}</td>
											<td>{{ $order->shipment->track_number }}</td>
											<td>{{ $order->status }}</td>
											<td>{{ $order->payment_status }}</td>
											<td>
												<a href="{{ url('orders/'. $order->id) }}" class="btn btn-info btn-sm">details</a>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="5">tidak ada catatan ditemukan</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection