<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_jurnal')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_jurnal')));
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Jurnal Umum</title>
	<style type="text/css">
		.line-title{
			border: 0;
			border-style: inset;
			border-top: 1px solid #000;
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
	JURNAL UMUM<br>
	Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
</p>
<table class="table table-bordered">
					<thead>
						<tr>
							<th style="text-align: center;">Tanggal Jurnal</th>
							<th style="text-align: center;">Keterangan</th>
							<th style="text-align: center;">Reff</th>
							<th style="text-align: center;">Debet</th>
							<th style="text-align: center;">Kredit</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total_debit  = 0;
						$total_kredit = 0; 
						//$jarak = '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
						foreach($jurnal as $jurnal){
						$nama_akun = $this->Laporan_Model->GetNamaAkun($jurnal['no_akun']);
						?>
						<tr>
							<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($jurnal['tanggal'])); ?></td>
							<?php 
							if($jurnal['posisi_db_cr'] == "Debet"){
							$total_debit = $total_debit + $jurnal['nominal'];
							?>
							<td><?php echo $nama_akun['nama_akun']; ?></td>
							<td style="text-align: center;"><?php echo $jurnal['no_akun']; ?></td>
							<td style="text-align: center;"><?php echo format_rp($jurnal['nominal']); ?></td>
							<td style="text-align: center;">-</td>
							<?php }else{ 
								$total_kredit = $total_kredit + $jurnal['nominal'];
							?>
							<td style='text-align: right'><?php echo "".$nama_akun['nama_akun'].""; ?></td>
							<td style="text-align: center;"><?php echo $jurnal['no_akun']; ?></td>
							<td style="text-align: center;">-</td>
							<td style="text-align: center;"><?php echo format_rp($jurnal['nominal']); ?></td>
							<?php } ?>
						</tr>
					<?php }?>
					<tr>
						<td style="text-align: center; font-weight: bold;" colspan="3">Total</td>
						<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total_debit); ?></td>
						<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total_kredit); ?></td>
					</tr>
					</tbody>
</table>
</body>
</html>