<!DOCTYPE html>
<html>
<head>
	<title>Laporan Produk</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

	<div class="container-fluid">
		<center>
			<a target="_blank" href="https://www.zuramai.net/">
            </a>
			<h4>Daftar Produk</h4>
		</center>
		<br/>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Produk</th>
					<th>Nama Produk</th>
					<th>Harga</th>
					<th>PPN</th>
					<th>Kategori</th>
					<th>Stok</th>
				</tr>
			</thead>
			<tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price) }}</td>
                    <td>{{ $product->ppn }}%</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->stock }}</td>
                </tr>
            @endforeach
			</tbody>
		</table>

	</div>

</body>
</html>