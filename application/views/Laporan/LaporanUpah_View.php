<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_laporan_upah')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_laporan_upah')));
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
            <i class="material-icons">card_membership</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
	<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-red">
				<h2>Laporan Gaji</h2>
				<ul class="header-dropdown m-r--5">
    					<li class="dropdown">
    						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    							<i class="material-icons">more_vert</i>
    						</a>
    						<ul class="dropdown-menu pull-right">
                               <li><a href="<?php echo site_url('Laporan_Controller/Cetak_Pengupahan/'); ?><?php echo $no_bulan?>/<?php echo $tahun; ?>" name="cetak" id="cetak">Cetak Laporan Upah</a></li>
                            </ul>
    					</li>
    				</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th style="text-align: center;" colspan="9">Butik Malla Ramdani</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="9">Laporan Upah</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="9">
								Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
							</th>
						</tr>
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
							<td style="text-align: right;"><?php echo format_rp($uph['subtotal_perproduk']); ?></td>
							<td style="text-align: center;"><?php echo $uph['jumlah_produksi']; ?></td>
							<td style="text-align: right;"><?php echo format_rp($uph['tunjangan_makan']); ?></td>
							<td style="text-align: right;"><?php echo format_rp($uph['subtotal_upah']); ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td style="text-align: center; font-weight: bold;" colspan="8">Total Upah</td>
							<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total); ?></td>
						</tr>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>	
	</div>
    <!--ENDCONTENT-->
</body>
</html>