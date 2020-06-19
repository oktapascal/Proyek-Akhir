<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_buku_besar')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_buku_besar')));
	$no_akun  = $this->session->userdata('akun');
	$nama_akun = $this->Laporan_Model->GetNamaAkun($no_akun);
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Buku Besar Akun <?php echo $no_akun; ?></title>
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
	BUKU BESAR<br>
	Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?> Akun <?php echo $nama_akun['nama_akun']; ?>
</p>
<table class="table table-bordered">
	<thead>
		<tr>
			<th style="text-align: left;" colspan="3">Nama Akun : <?php echo $nama_akun['nama_akun'] ?></th>
			<th style="text-align: right;" colspan="4">Nomor Akun : <?php echo $no_akun ?></th>
		</tr>
		<tr>
			<th rowspan="2" style="text-align: center;">Tanggal</th>
			<th rowspan="2" style="text-align: center;">Keterangan</th>
			<th rowspan="2" style="text-align: center;">Reff</th>
			<th rowspan="2" style="text-align: center;">Debet</th>
			<th rowspan="2" style="text-align: center;">Kredit</th>
			<th style="text-align: center;" colspan="2">Saldo</th>
		</tr>
		<tr>
			<th style="text-align: center;">Debet</th>
			<th style="text-align: center;">Kredit</th>
		</tr>
	</thead>
	<tbody>
						<?php 
					if($header['header_akun'] == 1 || $header['header_akun'] == 5 || $header['header_akun'] == 6 || $header['header_akun'] == 9)
					{ 
						?>
						<tr>
							<td style="text-align: center;">00-00-0000</td>
							<td style="text-align: center;">Saldo Awal</td>
							<td style="text-align: center;">-</td>
							<td style="text-align: center;">-</td>
							<td style="text-align: center;">-</td>
							<?php if(empty($saldo)){ 
							$saldo_awal = 0;
							?>
							<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
							<?php }else{ 
							$saldo_awal = $saldo;
								?>
							<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
							<?php } ?>
							<td style="text-align: center;">-</td>
						</tr>
						<?php }else{ ?>
						<tr>
							<td style="text-align: center;">00-00-0000</td>
							<td style="text-align: center;">Saldo Awal</td>
							<td style="text-align: center;">-</td>
							<td style="text-align: center;">-</td>
							<td style="text-align: center;">-</td>
							<td style="text-align: center;">-</td>
							<?php if(empty($saldo)){ 
							$saldo_awal = 0;
								?>
							<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
							<?php }else{ 
							$saldo_awal = $saldo;
							?>
							<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php foreach($buku_besar as $bukbes){ ?>
						<tr>
						<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($bukbes['tanggal'])); ?></td>
						<td style="text-align: center;"><?php echo $bukbes['nama_akun']; ?></td>
						<td style="text-align: center;"><?php echo $bukbes['id_transaksi']; ?></td>
						<?php 
						if($bukbes['posisi_db_cr'] == 'Debet'){
							if($bukbes['header_akun'] == 1 || $bukbes['header_akun'] == 5 || $bukbes['header_akun'] == 6 || $bukbes['header_akun'] == 9){ 
							$saldo_awal = $saldo_awal + $bukbes['nominal'];
						?>
						<td style="text-align: center;"><?php echo format_rp($bukbes['nominal']); ?></td>
						<td style="text-align: center;">-</td>
						<?php 
						}else{
						   $saldo_awal = $saldo_awal - $bukbes['nominal'];
						?>
						<?php } 
						if($saldo_awal >= 0 && $bukbes['header_akun'] == 1 || $bukbes['header_akun'] == 5 || $bukbes['header_akun'] == 6 || $bukbes['header_akun'] == 9){
						?>
						<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
						<td style="text-align: center;">-</td>
						<?php }else{ ?>
						<td style="text-align: center;">-</td>
						<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
						<?php } ?>
						<?php }else{ 
							if($bukbes['header_akun'] == 1 || $bukbes['header_akun'] == 5 || $bukbes['header_akun'] == 6 || $bukbes['header_akun'] == 9){
							$saldo_awal = $saldo_awal - $bukbes['nominal'];
							}else{
							$saldo_awal = $saldo_awal + $bukbes['nominal'];
							}
						?>
						<td style="text-align: center;">-</td>
						<td style="text-align: center;"><?php echo format_rp($bukbes['nominal']); ?></td>
						<?php if($saldo_awal >= 0 && $bukbes['header_akun'] == 1 || $bukbes['header_akun'] == 5 || $bukbes['header_akun'] == 6 || $bukbes['header_akun'] == 9){ ?>
						<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
						<td style="text-align: center;">-</td>
						<?php }else{ ?>
						<td style="text-align: center;">-</td>
						<td style="text-align: center;"><?php echo format_rp($saldo_awal); ?></td>
						<?php } ?>
						<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
</table>
</body>
</html>