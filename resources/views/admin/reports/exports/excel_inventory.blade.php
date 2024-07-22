<table>
	<thead>
		<tr>
		<th>Nama</th>
		<th>SKU</th>
		<th>Stok</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($products as $product)
			<tr>    
				<td>{{ $product->name }}</td>
				<td>{{ $product->sku }}</td>
				<td>{{ $product->stock }}</td>
			</tr>
		@endforeach
	</tbody>
</table>