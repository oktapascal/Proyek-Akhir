<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laporan_gaji')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laporan_gaji')));
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Laporan Gaji</title>
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
	Laporan Penggajian<br>
	Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
</p>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="text-align: center;">Kode Pegawai</th>
			<th style="text-align: center;">Nama Pegawai</th>
			<th style="text-align: center;">Kode Jabatan</th>
			<th style="text-align: center;">Nama jabatan</th>
			<th style="text-align: center;">Status</th>
			<th style="text-align: center;">Gaji Harian</th>
			<th style="text-align: center;">Jumlah Kehadiran</th>
			<th style="text-align: center;">Tunjangan Makan</th>
			<th style="text-align: center;">Tunjangan Kesehatan</th>
			<th style="text-align: center;">Bonus</th>
			<th style="text-align: center;">Lembur</th>
			<th style="text-align: center;">Subtotal</th>
		</tr>
	</thead>
	<tbody>
			<?php 
			$total = 0;
			foreach($penggajian as $gj){ 
			$total = $total + $gj['subtotal_gaji'];
			?>
		<tr>
			<td style="text-align: center;"><?php echo $gj['id_pegawai']; ?></td>
			<td style="text-align: center;"><?php echo $gj['nama_pegawai']; ?></td>
			<td style="text-align: center;"><?php echo $gj['id_posisi']; ?></td>
			<td style="text-align: center;"><?php echo $gj['nama_posisi']; ?></td>
			<td style="text-align: center;"><?php echo $gj['status']; ?></td>
			<td style="text-align: center;"><?php echo format_rp($gj['subtotal_perhari']); ?></td>
			<td style="text-align: center;"><?php echo $gj['jumlah_kehadiran']; ?></td>
			<td style="text-align: center;"><?php echo format_rp($gj['tunjangan_makan']); ?></td>
			<td style="text-align: center;"><?php echo format_rp($gj['tunjangan_kesehatan']); ?></td>
			<td style="text-align: center;"><?php echo format_rp($gj['bonus']); ?></td>
			<td style="text-align: center;"><?php echo format_rp($gj['lembur']); ?></td>
			<td style="text-align: center;"><?php echo format_rp($gj['subtotal_gaji']); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td style="text-align: center; font-weight: bold;" colspan="11">Total Gaji</td>
			<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total); ?></td>
		</tr>
	</tbody>
</table>
</body>
</html>