<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laporan_upah')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laporan_upah')));
	?>
</head>
<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Laporan Upah</title>
	<style type="text/css">
		.line-title{
			border: 0;
			border-style: inset;
			border-top: 1px solid #000;
		}
		.table{
			font-size: 9px;
		}
	</style>
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
	Laporan Pengupahan<br>
	Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
</p>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="text-align: center;">Kode Pegawai</th>
			<th style="text-align: center;">Nama Pegawai</th>
			<th style="text-align: center;">Kode Jabatan</th>
			<th style="text-align: center;">Nama Jabatan</th>
			<th style="text-align: center;">Status</th>
			<th style="text-align: center;">Tarif Produk</th>
			<th style="text-align: center;">Jumlah Produksi</th>
			<th style="text-align: center;">Tunjangan Makan</th>
			<th style="text-align: center;">Subtotal</th>
		</tr>
	</thead>
	<tbody>
			<?php 
			$total = 0;
			foreach($pengupahan as $uph){ 
			$total = $total + $uph['subtotal_upah'];
			?>
		<tr>
			<td style="text-align: center;"><?php echo $uph['id_pegawai']; ?></td>
			<td style="text-align: center;"><?php echo $uph['nama_pegawai']; ?></td>
			<td style="text-align: center;"><?php echo $uph['id_posisi']; ?></td>
			<td style="text-align: center;"><?php echo $uph['nama_posisi']; ?></td>
			<td style="text-align: center;"><?php echo $uph['status']; ?></td>
			<td style="text-align: center;"><?php echo format_rp($uph['subtotal_perproduk']); ?></td>
			<td style="text-align: center;"><?php echo $uph['jumlah_produksi']; ?></td>
			<td style="text-align: center;"><?php echo format_rp($uph['tunjangan_makan']); ?></td>
			<td style="text-align: center;"><?php echo format_rp($uph['subtotal_upah']); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td style="text-align: center; font-weight: bold;" colspan="8">Total Upah</td>
			<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total); ?></td>
		</tr>
	</tbody>
</table>
</body>
</html>