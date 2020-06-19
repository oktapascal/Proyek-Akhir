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
    				<h2>Tabel <?php echo $sub_navigasi; ?> <?php echo $id_gaji; ?></h2><small>Silahkan Klik Lihat Untuk Melihat Slip Gaji Pegawai</small>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
    					<table class="table table-hover">
    						<thead>
    							<tr>
    								<th style="text-align: center;">Kode Pegawai</th>
    								<th style="text-align: center;">Nama Pegawai</th>
    								<th style="text-align: center;">Posisi Pegawai</th>
                                    <th style="text-align: center;">Jumlah Kehadiran</th>
                                    <th style="text-align: center;">Tarif Per Hari</th>
    								<th style="text-align: center;">Gaji Harian</th>
    								<th style="text-align: center;">Tunjangan</th>
    								<th style="text-align: center;">Bonus</th>
    								<th style="text-align: center;">Lembur</th>
    								<th style="text-align: center;">Subtotal Gaji</th>
    								<th style="text-align: center;">Aksi</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php
    							$total_gaji_harian = 0;
    							$total_tunjangan   = 0;
    							$total_bonus       = 0;
    							$total_lembur      = 0;   
    							$total_gaji        = 0;
    							foreach($data as $gaji){ ?>
								<tr>
									<td style="text-align: center;"><?php echo $gaji['id_pegawai']; ?></td>
									<td style="text-align: center;"><?php echo $gaji['nama_pegawai']; ?></td>
									<td style="text-align: center;"><?php echo $gaji['nama_posisi']; ?></td>
                                    <td style="text-align: center;"><?php echo $gaji['jumlah_kehadiran']; ?></td>
                                    <td style="text-align: right;"><?php echo format_rp($gaji['tarif_per_hari']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($gaji['subtotal_perhari']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($gaji['subtotal_tunjangan']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($gaji['bonus']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($gaji['lembur']); ?></td>
									<td style="text-align: right;"><?php echo format_rp($gaji['subtotal_gaji']); ?></td>
									<td style="text-align: center;"><a role="button" class="btn btn-info waves-effect waves-float" href="<?php echo site_url('/GajiUpah_Controller/DetailGajiPegawai') ?>/<?php echo $gaji['id_gaji']; ?>/<?php echo $gaji['id_pegawai']; ?>" title="Lihat Detail Gaji">Detail</a></td>
								</tr>
    							<?php 
    							$total_gaji = $total_gaji + $gaji['subtotal_gaji'];
    							$total_gaji_harian = $total_gaji_harian + $gaji['subtotal_perhari'];
    							$total_bonus = $total_bonus + $gaji['bonus'];
    							$total_tunjangan = $total_tunjangan + $gaji['subtotal_tunjangan'];
    							$total_lembur = $total_lembur + $gaji['lembur'];
    							} ?>
    						
    							<tr>
    								<td style="text-align: left; font-weight: bold;" colspan="5">Total Gaji Keseluruhan</td>
    								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_gaji_harian); ?></td>
    								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_tunjangan); ?></td>
    								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_bonus); ?></td>
    								<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_lembur); ?></td>
    								<td style="text-align: right; font-weight: bold;" colspan="1"><?php echo format_rp($total_gaji); ?></td>
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