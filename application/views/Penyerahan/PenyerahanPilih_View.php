<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>Detail Penyerahan Bahan Untuk Pesanan <?php echo $this->session->userdata('pesanan'); ?> (Produk <?php echo $this->session->userdata('produk'); ?>)</h2><small>Sebelum Konfirmasi, Pastikan Anda Yakin Untuk Menyimpan Penyerahan Bahan Ini.</small>
			</div>
			<div class="body">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th style="text-align: center;">Kode Bahan</th>
							<th style="text-align: center;">Nama Bahan</th>
							<th style="text-align: center;">Stok yang Tersedia</th>
							<th style="text-align: center;">Jumlah yang Dibutuhkan</th>
							<th style="text-align: center;">Harga per Satuan</th>
							<th style="text-align: center;">Subtotal</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$total  = 0;
						foreach($bahan as $bhn){
						$subtotal = $bhn['total'] * $bhn['harga'];
						?>
							<tr>
							<td style="text-align: center;"><?php echo $bhn['id_bahan']; ?></td>
							<td style="text-align: center;"><?php echo $bhn['nama_bahan']; ?></td>
							<td style="text-align: center;"><?php echo $bhn['stok']; ?></td>
							<td style="text-align: center;"><?php echo $bhn['kebutuhan']; ?></td>
							<td style="text-align: center;"><?php echo format_rp($bhn['harga']); ?></td>
							<td style="text-align: center;"><?php echo format_rp($subtotal); ?></td>	
							</tr>
						<?php 
						$total = $subtotal + $total;
						} ?>
						<tr>
							<td style="text-align: center; font-weight: bold;" colspan="5">Total Nilai Bahan yang Diserahkan</td>
							<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total); ?></td>
						</tr>
					</tbody>
					<?php if($stok->num_rows() > 0) { ?>
					<tfoot><tr><th colspan="6"><a role="button" class="btn btn-danger btn-lg waves-effect">Stok Bahan Tidak Mencukupi</a></th></tr></tfoot>
					<?php 
					}else{ 
				?>
				<tfoot><tr><th colspan="6"><a role="button" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Penyerahan_Controller/KonfirmasiPenyerahan'); ?>">Konfirmasi Penyerahan</a></th></tr></tfoot>
				<?php } ?>
				</table>
			</div>
		</div>
		</div>
	</div>
</div>
</body>
</html>