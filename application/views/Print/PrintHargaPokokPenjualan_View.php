<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_hppenjualan')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_hppenjualan')));
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Harga Pokok Penjualan</title>
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
	HARGA POKOK PENJUALAN<br>
	Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
</p>
<table class="table table-bordered">
	<tbody>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Awal Proses</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($persediaan_dalam_proses['total_dalam_proses']); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="5">Biaya Bahan Baku:</td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Bahan Baku Awal</td>
								<td style="text-align: left; font-weight: bold;" colspan=""><?php echo format_rp(0); ?></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Pembelian</td>
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($pembelian['subtotal']); ?></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Bahan Baku yang Tersedia</td>
								<td style="text-align: left; font-weight: bold;" colspan=""><?php echo format_rp($pembelian['subtotal']+0); ?></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Bahan Baku Akhir</td>
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($bahan_baku_akhir); ?></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Bahan Baku yang Dipakai</td>
								<td></td>
								<td style="text-align: center; font-weight: bold;" colspan=""><?php echo format_rp($pembelian['subtotal']-$bahan_baku_akhir); ?></td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Biaya Tenaga Kerja Langsung</td>
								<td></td>
								<td style="text-align: center; font-weight: bold;" colspan=""><?php echo format_rp($btkl['total']); ?></td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Biaya <i>Overhead</i> Pabrik</td>
								<td></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($bop['nominal']); ?></td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Biaya Produksi</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black;"><?php echo format_rp(($pembelian['subtotal']-$bahan_baku_akhir) + $btkl['total'] + $bop['nominal']); ?></td>
							</tr>
							<?php $total_biaya_dalam_proses = $persediaan_dalam_proses['total_dalam_proses'] + (($pembelian['subtotal']-$bahan_baku_akhir) + $btkl['total'] + $bop['nominal']) ?>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Total Biaya Dalam Proses</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total_biaya_dalam_proses); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Akhir Barang Dalam Proses</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black;"><?php echo format_rp($persediaan_akhir_dalam_proses['total_akhir_dalam_proses']); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Produksi</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total_biaya_dalam_proses - $persediaan_akhir_dalam_proses['total_akhir_dalam_proses']); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Awal Barang Jadi</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($persediaan_barang_jadi_awal['saldo_persediaan_barang_jadi_awal']); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Akhir Barang Jadi</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold; border-bottom-color: black;"><?php echo format_rp($persediaan_barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir']); ?></td>
							</tr>
							<?php $hpproduksi = $total_biaya_dalam_proses - $persediaan_akhir_dalam_proses['total_akhir_dalam_proses']; 
								  $hppenjualan = ($hpproduksi + $persediaan_barang_jadi_awal['saldo_persediaan_barang_jadi_awal']) - $persediaan_barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir'];
							?>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Penjualan</td>
								<td colspan="3"></td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($hppenjualan); ?></td>
							</tr>
						</tbody>
</table>
</body>
</html>