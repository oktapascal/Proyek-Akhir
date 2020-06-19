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
    			<div class="header bg-red">
    				<h2><?php echo $sub_navigasi; ?> <?php echo $id_pegawai; ?></h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                               <li><a href="<?php echo site_url('Laporan_Controller/Cetak_SlipGaji/'); ?><?php echo $this->uri->segment(3);?>/<?php echo $this->uri->segment(4); ?>" name="cetak" id="cetak">Cetak Slip Gaji</a></li>
                            </ul>
                        </li>
                    </ul>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
    					<table class="table table-hover">
    						<thead>
    							<tr>
    								<th style="text-align: center; font-size: 20px" colspan="4">SLIP GAJI</th>
    							</tr>
    							<tr>
    								<th style="text-align: center;" colspan="4">===========================================================================================================	</th>
    							</tr>
    							<tr>
    								<th style="text-align: left;">Nama Perusahaan</th>
    								<th style="text-align: left;">Butik Malla Ramdani</th>
    								<th style="text-align: left;">Kode Pegawai</th>
    								<th style="text-align: left;"><?php echo $id_pegawai; ?></th>
    							</tr>
    							<tr>
    								<th style="text-align: left;">Tanggal Gaji</th>
    								<th style="text-align: left;"><?php echo date('d-m-Y', strtotime($data['tanggal_gaji'])); ?></th>
    								<th style="text-align: left;">Nama Pegawai</th>
    								<th style="text-align: left;"><?php echo $data['nama_pegawai']; ?></th>
    							</tr>
    							<tr>
    								<th style="text-align: left">Kode Pembayaran Gaji</th>
    								<th style="text-align: left;"><?php echo $data['id_transaksi']; ?></th>
    								<th style="text-align: left;">Jabatan Pegawai</th>
    								<th style="text-align: left;"><?php echo $data['nama_posisi']; ?></th>
    							</tr>
    							<tr>
    								<th style="text-align: left">Kode Gaji</th>
    								<th style="text-align: left;"><?php echo $id_gaji; ?></th>
    								<th style="text-align: left;">Status Pegawai</th>
    								<th style="text-align: left;"><?php echo $data['status']; ?></th>
    							</tr>
    							<tr>
    								<th style="text-align: center;" colspan="4">===========================================================================================================	</th>
    							</tr>
    						</thead>
    						<tbody>
    							<tr>
    							<td style="text-align: left; font-weight: bold;" colspan="4">Gaji Pokok Harian :</td>
    							</tr>
    							<tr>
    							<td style="text-align: left" colspan="3"><?php echo $jumlah['jumlah_kehadiran']; ?> X <?php echo format_rp($data['tarif_per_hari']); ?></td>
    							<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['subtotal_perhari']); ?></td>
    							</tr>
    							<tr>
    							<td style="text-align: left; font-weight: bold;" colspan="4">Tunjangan Pegawai Tetap :</td>
    							</tr>
    							<tr>
    								<td style="text-align: left;">Tunjangan Kesehatan</td>
    								<td style="text-align: left; font-weight: bold;" colspan="3"><?php echo format_rp($data['tunjangan_kesehatan']); ?></td>
    							</tr>
    							<tr>
    								<td style="text-align: left;">Tunjangan Makan</td>
    								<td style="text-align: left; font-weight: bold;" colspan="3"><?php echo format_rp($data['tunjangan_makan']); ?></td>
    							</tr>
    							<tr>
    								<td style="text-align: left; font-weight: bold;" colspan="3">Total Tunjangan</td>
    								<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['subtotal_tunjangan']); ?></td>
    							</tr>
    							<tr>
    								<td style="text-align: left; font-weight: bold;" colspan="3">Bonus</td>
    								<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['bonus']); ?></td>
    							</tr>
    							<tr>
    								<td style="text-align: left; font-weight: bold;" colspan="3">Lembur</td>
    								<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['lembur']); ?></td>
    							</tr>
    							<tr>
    								<td style="text-align: left; font-weight: bold;" colspan="3">Total Gaji yang Diterima</td>
    								<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['subtotal_gaji']); ?></td>
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