<!DOCTYPE html>
<html>
<head>
	<title></title>
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
            <i class="material-icons">contact_mail</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-blue">
    				<h2>Tabel <?php echo $sub_navigasi; ?> <?php echo $id_upah; ?></h2><small>Silahkan Klik Lihat Untuk Melihat Slip Upah Pegawai</small>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
    					<table class="table table-hover">
    						<thead>
    							<tr>
    								<th style="text-align: center;">Kode Pegawai</th>
    								<th style="text-align: center;">Nama Pegawai</th>
    								<th style="text-align: center;">Posisi Pegawai</th>
                                    <th style="text-align: center;">Jumlah Produk Dihasilkan</th>
                                    <th style="text-align: center;">Tarif Per Produk</th>
    								<th style="text-align: center;">Upah Produk</th>
    								<th style="text-align: center;">Tunjangan</th>
    								<th style="text-align: center;">Subtotal Upah</th>
    								<th style="text-align: center;">Aksi</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php
    							$total_upah_produk = 0;
    							$total_tunjangan   = 0;
    							$total_upah        = 0;
    							foreach($data as $upah){ ?>
								<tr>
									<td style="text-align: center;"><?php echo $upah['id_pegawai']; ?></td>
									<td style="text-align: center;"><?php echo $upah['nama_pegawai']; ?></td>
									<td style="text-align: center;"><?php echo $upah['nama_posisi']; ?></td>
                                    <td style="text-align: center;"><?php echo $upah['jumlah_produk']; ?></td>
                                    <td style="text-align: right;"><?php echo format_rp($upah['tarif_per_produk']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($upah['subtotal_perproduk']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($upah['subtotal_tunjangan']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($upah['subtotal_upah']); ?></td>
									<td style="text-align: right;"><a role="button" class="btn btn-info waves-effect waves-float" href="<?php echo site_url('/GajiUpah_Controller/DetailUpahPegawai') ?>/<?php echo $upah['id_upah']; ?>/<?php echo $upah['id_pegawai']; ?>" title="Lihat Detail Upah">Detail</a></td>
								</tr>
    							<?php 
    							$total_upah = $total_upah + $upah['subtotal_upah'];
    							$total_upah_produk = $total_upah_produk + $upah['subtotal_perproduk'];
    							$total_tunjangan = $total_tunjangan + $upah['subtotal_tunjangan'];
    							} ?>
    						
    							<tr>
    								<td style="text-align: left; font-weight: bold;" colspan="5">Total Upah Keseluruhan</td>
    								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_upah_produk); ?></td>
    								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_tunjangan); ?></td>
    								<td style="text-align: right; font-weight: bold;" colspan="1"><?php echo format_rp($total_upah); ?></td>
    								<td style="text-align: center; font-weight: bold;">-</td>
    							</tr>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <!--EndContent-->
</body>
</html>