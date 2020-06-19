<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_hppenjualan')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_hppenjualan')));
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
            <i class="material-icons">assignment_return</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class=" header bg-red">
					<h2>Laporan Harga Pokok Penjualan</h2>
					<ul class="header-dropdown m-r--5">
    					<li class="dropdown">
    						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    							<i class="material-icons">more_vert</i>
    						</a>
    						<ul class="dropdown-menu pull-right">
                               <li><a href="<?php echo site_url('Laporan_Controller/Cetak_HargaPokokPenjualan/'); ?><?php echo $no_bulan?>/<?php echo $tahun; ?>" name="cetak" id="cetak">Cetak Harga Pokok Penjualan</a></li>
                            </ul>
    					</li>
    				</ul>
				</div>
				<div class="body">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th style="text-align: center;" colspan="5">Butik Malla Ramdani</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="5">Laporan Harga Pokok Penjualan</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="5">
								Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
							</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Awal Proses</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($persediaan_dalam_proses['total_dalam_proses']); ?></td>
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
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($pembelian['subtotal']); ?> (+)</td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Bahan Baku yang Tersedia</td>
								<td style="text-align: left; font-weight: bold;" colspan=""><?php echo format_rp($pembelian['subtotal']+0); ?></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Bahan Baku Akhir</td>
								<td style="text-align: left; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($bahan_baku_akhir); ?> (-)</td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Bahan Baku yang Dipakai</td>
								<td></td>
								<td style="text-align: right; font-weight: bold;" colspan=""><?php echo format_rp($pembelian['subtotal']-$bahan_baku_akhir); ?></td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Biaya Tenaga Kerja Langsung</td>
								<td></td>
								<td style="text-align: right; font-weight: bold;" colspan=""><?php echo format_rp($btkl['total']); ?></td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Biaya <i>Overhead</i> Pabrik</td>
								<td></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black;" colspan=""><?php echo format_rp($bop['nominal']); ?> (+)</td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Biaya Produksi</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black;"><?php echo format_rp(($pembelian['subtotal']-$bahan_baku_akhir) + $btkl['total'] + $bop['nominal']); ?> (+)</td>
							</tr>
							<?php $total_biaya_dalam_proses = $persediaan_dalam_proses['total_dalam_proses'] + (($pembelian['subtotal']-$bahan_baku_akhir) + $btkl['total'] + $bop['nominal']) ?>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Total Biaya Dalam Proses</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_biaya_dalam_proses); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Akhir Barang Dalam Proses</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black;"><?php echo format_rp($persediaan_akhir_dalam_proses['total_akhir_dalam_proses']); ?> (-)</td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Produksi</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_biaya_dalam_proses - $persediaan_akhir_dalam_proses['total_akhir_dalam_proses']); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Awal Barang Jadi</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black;"><?php echo format_rp($persediaan_barang_jadi_awal['saldo_persediaan_barang_jadi_awal']); ?>(+)</td>
							</tr>
							<tr>
								<?php $harga_pokok = $total_biaya_dalam_proses - $persediaan_akhir_dalam_proses['total_akhir_dalam_proses']; ?>
								<td style="text-align: left; font-weight: bold;" colspan=""></td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($persediaan_barang_jadi_awal['saldo_persediaan_barang_jadi_awal'] + $harga_pokok); ?></td>
							</tr>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Persediaan Akhir Barang Jadi</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold; border-bottom-color: black;"><?php echo format_rp($persediaan_barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir']); ?> (-)</td>
							</tr>
							<?php $hpproduksi = $total_biaya_dalam_proses - $persediaan_akhir_dalam_proses['total_akhir_dalam_proses']; 
								  $hppenjualan = ($hpproduksi + $persediaan_barang_jadi_awal['saldo_persediaan_barang_jadi_awal']) - $persediaan_barang_jadi_akhir['saldo_persediaan_barang_jadi_akhir'];
							?>
							<tr>
								<td style="text-align: left; font-weight: bold;" colspan="">Harga Pokok Penjualan</td>
								<td colspan="3"></td>
								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($hppenjualan); ?></td>
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