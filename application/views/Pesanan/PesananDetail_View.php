<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
	<!--BreadChumbs-->
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">rate_review</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--EndBreadChumbs-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
		<div class="header bg-blue">
				<h2>Detail <?php echo $navigasi; ?> <?php echo $nomor; ?></h2>
		<ul class="header-dropdown m-r--5">
    		<li class="dropdown">
    		<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    			<i class="material-icons">more_vert</i>
    		</a>
    		<ul class="dropdown-menu pull-right">
                  <li><a role="button" onclick="window.history.go(-1); return false;">Kembali</a></li>
                  <li><a href="<?php echo site_url('Pesanan_Controller/Cetak_Pesanan/'); ?><?php echo $this->uri->segment(3);?>" name="cetak" id="cetak">Cetak Bukti Pesanan</a></li>
            </ul>
    		</li>
    		</ul>
		</div>
		<div class="body table-responsive">
			<table class="table table-hover">
				<thead>
				<tr>
					<th style="text-align: center;">Tanggal Pesan</th>
					<th style="text-align: center;">Tanggal Selesai</th>
					<th style="text-align: center;">Nama Produk</th>
					<th style="text-align: center;">Ukuran Produk</th>
					<th style="text-align: center;">Jumlah</th>
					<th style="text-align: center;">Subtotal</th>
				</tr>
				</thead>
				<tbody>
				<?php 
				$pesan = 0;
				$total = 0;
				foreach($detail as $dt){ ?>
				<tr>
					<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($dt['tanggal_pesan'])); ?></td>
					<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($dt['tanggal_selesai'])); ?></td>
					<td style="text-align: center;"><?php echo $dt['nama_produk']; ?></td>
					<td style="text-align: center;"><?php echo $dt['ukuran']; ?></td>
					<td style="text-align: center;"><?php echo $dt['jumlah']; ?></td>
					<td style="text-align: center;"><?php echo format_rp($dt['subtotal']); ?></td>
				</tr>
				<?php
				$pesan = $pesan + $dt['jumlah']; 
				$total = $total + $dt['subtotal'];
				} ?>
				<tr>
					<td style="text-align: center; font-weight: bold;" colspan="4">Total Pesanan</td>
					<td style="text-align: center; font-weight: bold;"><?php echo $pesan; ?></td>
					<td style="text-align: center;">-</td>
				</tr>
				<tr>
					<td style="text-align: center; font-weight: bold;" colspan="4">Total Nilai Pesanan</td>
					<td style="text-align: center;">-</td>
					<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total); ?></td>
				</tr>
				<tr>
					<th style="text-align: center;" colspan="6"></th>
				</tr>
				<tr>
					<th style="text-align: center;" colspan="6">Barcode Pesanan</th>
				</tr>
				<tr>
					<td style="text-align: center;" colspan="6">
						<img src="<?php echo base_url().$barcode['barcode'];?>">
					</td>
				</tr>
			</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
</body>
</html>