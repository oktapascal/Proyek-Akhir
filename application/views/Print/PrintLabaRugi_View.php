<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laba_rugi')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laba_rugi')));

	$bbb      = $pembelian['subtotal']-$bahan_baku_akhir;
	$btkl     = $btkl['total'];
	$bop      = $bop['nominal'];
	$biaya_produksi = $bbb + $btkl + $bop;
	$hpproduksi = ($biaya_produksi + $persediaan_dalam_proses['total_dalam_proses']) - $persediaan_akhir_dalam_proses['total_akhir_dalam_proses'];
	$barang_dijual = $hpproduksi + $barang_jadi_awal['saldo_persediaan_barang_jadi_awal'];
	$hppenjualan = $barang_dijual - $barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir'];
	if($penjualan['total_penjualan'] >= $hppenjualan)
	{
		$laba_kotor  = $penjualan['total_penjualan'] - $hppenjualan;
	}else if($hppenjualan > $penjualan['total_penjualan']){
		$laba_kotor  = $hppenjualan - $penjualan['total_penjualan'];
	}
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Laba Rugi</title>
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
	LAPORAN LABA RUGI<br>
	Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
</p>
<table class="table table-bordered">
	<tbody>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Penjualan</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($penjualan['total_penjualan']); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="6">Harga Pokok Penjualan:</td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Barang Jadi Awal</td>
								<td style="text-align: left; font-weight: bold;" colspan=""><?php echo format_rp($barang_jadi_awal['saldo_persediaan_barang_jadi_awal']); ?></td>
								<td colspan="4"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Produksi</td>
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($hpproduksi); ?></td>
								<td colspan="4"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Barang Tersedia untuk Dijual</td>
								<td colspan=""></td>
								<td style="text-align: left; font-weight: bold;" colspan=""><?php echo format_rp($hpproduksi + $barang_jadi_awal['saldo_persediaan_barang_jadi_awal']); ?></td>
								<td colspan="4"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Barang Jadi Akhir</td>
								<td colspan=""></td>
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir']); ?></td>
								<td colspan="4"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Penjualan</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($hppenjualan); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Laba Kotor</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold;" colspan=""><?php echo format_rp($laba_kotor); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="6">Biaya-Biaya Operasional:</td>
							</tr>
							<?php 
							$total_beban = 0;
							foreach($beban as $bbn){ 
							$total_beban = $total_beban + $bbn['subtotal'];
							?>
							<tr>
								<td style="text-align: left; font-weight: bold;"><?php echo $bbn['nama_beban']; ?></td>
								<td style="text-align: left; font-weight: bold;"><?php echo format_rp($bbn['subtotal']); ?></td>
								<td colspan="4"></td>
							</tr>
							<?php } ?>
							<tr>
								<td style="text-align: left; font-weight: bold;">Total Biaya Operasional</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black"><?php echo format_rp($total_beban); ?></td>
							</tr>
							<?php $laba_sebelum_bunga_dan_pajak = $laba_kotor - $total_beban; ?>
							<tr>
								<td style="text-align: left; font-weight: bold;">Laba Sebelum Bunga dan Pajak</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold;" colspan=""><?php echo format_rp($laba_sebelum_bunga_dan_pajak); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Beban Bunga</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black" colspan=""><?php echo format_rp(0); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Laba Setelah Bunga</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold;" colspan=""><?php echo format_rp($laba_sebelum_bunga_dan_pajak
									-0); ?></td>
							</tr>
							<?php $pajak = abs($laba_sebelum_bunga_dan_pajak)*(10/100); 
								  $laba_setelah_pajak = ($laba_sebelum_bunga_dan_pajak - 0) - $pajak;
							?>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Pajak (10%)</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black" colspan=""><?php echo format_rp($pajak); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Laba Setelah Pajak</td>
								<td colspan="4"></td>
								<td style="text-align: center; font-weight: bold;" colspan=""><?php echo format_rp($laba_setelah_pajak); ?></td>
							</tr>
						</tbody>
</table>
</body>
</html>