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
</head>
<body>
<!--Beadchumbs-->
	<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li>
            <i class="material-icons">description</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-red">
    				<h2>Laporan Laba Rugi</h2>
    				<ul class="header-dropdown m-r--5">
    					<li class="dropdown">
    						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    							<i class="material-icons">more_vert</i>
    						</a>
    						<ul class="dropdown-menu pull-right">
                               <li><a href="<?php echo site_url('Laporan_Controller/Cetak_LabaRugi/'); ?><?php echo $no_bulan?>/<?php echo $tahun; ?>" name="cetak" id="cetak">Cetak Laba Rugi</a></li>
                            </ul>
    					</li>
    				</ul>
    			</div>
    			<div class="body">
    				<table class="table table-hover table-bordered">
    					<thead>
						<tr>
							<th style="text-align: center;" colspan="6">Butik Malla Ramdani</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="6">Laporan Laba Rugi</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="6">
								Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
							</th>
						</tr>
						</thead>
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
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($hpproduksi); ?> (+)</td>
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
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir']); ?> (-)</td>
								<td colspan="4"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Penjualan</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($hppenjualan); ?> (-)</td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Laba Kotor</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold;" colspan=""><?php echo format_rp($laba_kotor); ?></td>
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
								<td style="text-align: right; font-weight: bold; border-bottom-color: black"><?php echo format_rp($total_beban); ?> (-)</td>
							</tr>
							<?php $laba_sebelum_bunga_dan_pajak = $laba_kotor - $total_beban; ?>
							<tr>
								<td style="text-align: left; font-weight: bold;">Laba Sebelum Bunga dan Pajak</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold;" colspan=""><?php echo format_rp($laba_sebelum_bunga_dan_pajak); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Beban Bunga</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black" colspan=""><?php echo format_rp(0); ?> (-)</td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Laba Setelah Bunga</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold;" colspan=""><?php echo format_rp($laba_sebelum_bunga_dan_pajak
									-0); ?></td>
							</tr>
							<?php $pajak = abs($laba_sebelum_bunga_dan_pajak)*(10/100); 
								  $laba_setelah_pajak = ($laba_sebelum_bunga_dan_pajak - 0) - $pajak;
							?>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Pajak (10%)</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black" colspan=""><?php echo format_rp($pajak); ?> (-)</td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Laba Setelah Pajak</td>
								<td colspan="4"></td>
								<td style="text-align: right; font-weight: bold;" colspan=""><?php echo format_rp($laba_setelah_pajak); ?></td>
							</tr>
						</tbody>
    				</table>
    			</div>
    		</div>
    	</div>
    </div>
    <!--ENDContent-->
</body>
</html>

