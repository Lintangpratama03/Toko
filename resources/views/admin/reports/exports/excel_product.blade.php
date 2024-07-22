<table>
	<thead>
		<tr>
		<th>Nama</th>
		<th>SKU</th>
		<th>Produk Terjual</th>
		<th>Pendapatan Bersih</th>
		<th>Pesanan</th>
		<th>Stok</th>
		</tr>
	</thead>
	<tbody>
		@php
			$totalNetRevenue = 0;
		@endphp
		@foreach ($products as $product)
			<tr>    
				<td>{{ $product->name }}</td>
				<td>{{ $product->sku }}</td>
				<td>{{ $product->items_sold }}</td>
				<td>{{ $product->net_revenue }}</td>
				<td>{{ $product->num_of_orders }}</td>
				<td>{{ $product->stock }}</td>
			</tr>

			@php
				$totalNetRevenue += $product->net_revenue;
			@endphp
		@endforeach
		<tr>    
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{{ $totalNetRevenue }}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>