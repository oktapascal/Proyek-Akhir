<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Bukti Pesanan</title>
	<style type="text/css">
		.line-title{
			border: 0;
			border-style: inset;
			border-top: 1px solid #000;
		}
		.table{
			font-size: 12px;
		}
	</style>
</head>
<body>
<table style="width: 100%;">
	<tr>
		<td align="center">
			<span style="line-height: 1.6; font-weight: bold; ">
				BUTIK MALLA RAMDANI
				<br>
				Studio Butik Dan Jahit
				<br>
				BANDUNG
			</span>
		</td>
	</tr>
</table>
<br class="line-title">
<p align="center">
	Bukti Pesanan <?php echo $nomor; ?>
</p>
<table class="table table-bordered">
	<thead>
		<tr>
		<th style="text-align: center;">Tanggal Pesan</th>
		<th style="text-align: center;">Tanggal Selesai</th>
		<th style="text-align: center;">Nama Produk</th>
		<th style="text-align: center;">Jumlah</th>
		<th style="text-align: center;">Subtotal</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$pesan = 0;
		$total = 0;
		foreach($detail as $dt){ ?>
		<tr>
		<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($dt['tanggal_pesan'])); ?></td>
		<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($dt['tanggal_selesai'])); ?></td>
		<td style="text-align: center;"><?php echo $dt['nama_produk']; ?></td>
		<td style="text-align: center;"><?php echo $dt['jumlah']; ?></td>
		<td style="text-align: center;"><?php echo format_rp($dt['subtotal']); ?></td>
		</tr>
		<?php
		$pesan = $pesan + $dt['jumlah']; 
		$total = $total + $dt['subtotal'];
		} ?>
		<tr>
		<td style="text-align: center; font-weight: bold;" colspan="3">Total Pesanan</td>
		<td style="text-align: center; font-weight: bold;"><?php echo $pesan; ?></td>
		<td style="text-align: center;">-</td>
		</tr>
		<tr>
		<td style="text-align: center; font-weight: bold;" colspan="3">Total Nilai Pesanan</td>
		<td style="text-align: center;">-</td>
		<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total); ?></td>
		</tr>
		<tr>
		<th style="text-align: center;" colspan="5"></th>
		</tr>
		<tr>
		<th style="text-align: center;" colspan="5">Barcode Pesanan</th>
		</tr>
		<tr>
		<td style="text-align: center;" colspan="5">
		<img src="<?php echo base_url().$barcode['barcode'];?>">
		</td>
		</tr>
	</tbody>
</table>
</body>
</html>