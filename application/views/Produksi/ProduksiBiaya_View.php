<!DOCTYPE html>
<html>
<head>
		<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
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
            <i class="material-icons">assignment_turned_in</i> <?php echo $sub_navigasi; ?>
          </li>
      </ol>
</div>
<!--EndBreadChumbs-->
<!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header">
				<h2>Rincian Biaya Produksi Pesanan <?php echo $this->session->userdata('no_pesanan_konfirmasi'); ?> Produk <?php echo $this->session->userdata('produk_konfirmasi'); ?></h2>
			</div>
			<div class="body">
				<div id="wizard_horizontal">
					<h2>Bahan Baku</h2>
					<section>
						<div class="table-responsive">
							<table class="table table-hover">
							<thead>
								<tr>
									<th style="text-align: center;">Kode Bahan</th>
									<th style="text-align: center;">Nama Bahan</th>
									<th style="text-align: center;">Jumlah Bahan</th>
									<th style="text-align: center;">Subtotal</th>
								</tr>
								<tbody>
									<?php 
									$total_bbb = 0;
									foreach($bbb as $bbb){ ?>
									<tr>
										<td style="text-align: center;"><?php echo $bbb['id_bahan']; ?></td>
										<td style="text-align: center;"><?php echo $bbb['nama_bahan']; ?></td>
										<td style="text-align: center;"><?php echo $bbb['jumlah']; ?></td>
										<td style="text-align: center;"><?php echo format_rp($bbb['subtotal']); ?></td>
									</tr>
									<?php
									$total_bbb = $total_bbb + $bbb['subtotal']; 
									} ?>
									<tr>
										<td style="font-weight: bold; text-align: center;" colspan="3">Total Biaya Bahan Baku</td>
										<td style="font-weight: bold; text-align: center;"><?php echo format_rp($total_bbb); ?></td>
									</tr>
								</tbody>
							</thead>
							</table>
						</div>
					</section>
					<h2>Tenaga Kerja Langsung</h2>
					<section>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th style="text-align: center;">Kode Posisi</th>
										<th style="text-align: center;">Nama Posisi</th>
										<th style="text-align: center;">Subtotal</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$total_btkl = 0;
									foreach($btkl as $btkl){
									 ?>
									<tr>
										<td style="text-align: center;"><?php echo $btkl['id_posisi']; ?></td>
										<td style="text-align: center;"><?php echo $btkl['nama_posisi']; ?></td>
										<td style="text-align: center;"><?php echo format_rp($btkl['subtotal']); ?></td>
									</tr>
									<?php 
									$total_btkl = $total_btkl + $btkl['subtotal'];
									} ?>
									<tr>
										<td style="font-weight: bold; text-align: center;" colspan="2">Total Biaya Tenaga Kerja Langsung</td>
										<td style="font-weight: bold; text-align: center;"><?php echo format_rp($total_btkl); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</section>
					<h2>Overhead Pabrik</h2>
					<section>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th style="text-align: center;">Kode Bahan</th>
										<th style="text-align: center;">Nama Bahan</th>
										<th style="text-align: center;">Jumlah Bahan</th>
										<th style="text-align: center;">Subtotal</th>
										<th style="text-align: center;">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$total_bpp = 0;
									foreach($bpp as $bpp){
									?>
									<tr>
										<td style="text-align: center;"><?php echo $bpp['id_bahan']; ?></td>
										<td style="text-align: center;"><?php echo $bpp['nama_bahan']; ?></td>
										<td style="text-align: center;"><?php echo $bpp['jumlah']; ?></td>
										<td style="text-align: center;"><?php echo format_rp($bpp['subtotal']); ?></td>
										<td style="text-align: center;">-</td>
									</tr>
									<?php 
									$total_bpp = $total_bpp + $bpp['subtotal'];
									} ?>
									<tr>
										<td style="font-weight: bold; text-align: left;" colspan="4">Total Biaya Bahan Penolong</td>
										<td style="font-weight: bold; text-align: center;"><?php echo format_rp($total_bpp); ?></td>
									</tr>
								</tbody>
								<tbody>
									<?php 
									$taksiran  = $this->session->userdata('taksiran_bop');
									$taksiran_jumlah = $this->session->userdata('taksiran_jumlah');
									$jumlah_produk = $jumlah['jumlah']; 
									$satuan_produk = $taksiran/$taksiran_jumlah;
									$total_bbn     = $satuan_produk*$jumlah_produk;
									?>
								  <tr>
								  	<td style="font-weight: bold; text-align: center; border-bottom-color: black;">Taksiran BOP</td>
								  	<td rowspan="2" style="text-align: center;">=</td>
								  	<td style="font-weight: bold; text-align: center; border-bottom-color: black;"><?php echo format_rp($taksiran); ?></td>
								  	<td rowspan="2" style="text-align: center;">=</td>
								  	<td rowspan="2" style="text-align: center; font-weight: bold;"><?php echo format_rp($satuan_produk); ?>/produk</td>
								  </tr>
								  <tr>
								  	<td style="font-weight: bold; text-align: center;">Taksiran Jumlah yang Dihasilkan</td>
								  	<td style=" font-weight: bold; text-align: center;"><?php echo $taksiran_jumlah; ?></td>
								  </tr>
								  <tr>
								  	<td style="font-weight: bold; text-align: center;">Tarif BOP per Satuan x Jumlah Produk</td>
								  	<td style="text-align: center;">=</td>
								  	<td style="text-align: center; font-weight: bold;"><?php echo format_rp($satuan_produk); ?> x <?php echo $jumlah_produk; ?></td>
								  	<td style="text-align: center;">=</td>
								  	<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total_bbn); ?></td>
								  </tr>
								  <tr>
								  	<td colspan="3" style="font-weight: bold; text-align: left;">Total Biaya <i>Overhead Pabrik</i></td>
								  	<td></td>
								  	<td style="font-weight: bold; text-align: center;"><?php echo format_rp($total_bbn + $total_bpp); ?></td>
								  </tr>
								</tbody>
							</table>
						</div>
					</section>
					<h2>Biaya Produksi</h2>
					<?php 
					$total_bop = $total_bbn + $total_bpp;
					$total_produksi = $total_bbb + $total_btkl + $total_bop;
					?>
					<section>
						<?php echo form_open('Produksi_Controller/KonfirmasiProduksi', array('id'=>'KonfirmasiProduksi')); ?>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th style="text-align: center; font-weight: bold;" colspan="3">Biaya Produksi</th>
									</tr>
									<tr>
										<th style="text-align: center; font-weight: bold;" colspan="3">Nomor Pesanan <?php echo $this->session->userdata('no_pesanan_konfirmasi'); ?></th>
									</tr>
									<tr>
										<th style="text-align: center; font-weight: bold;" colspan="3">Produk <?php echo $this->session->userdata('produk_konfirmasi'); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>Biaya Bahan Baku</b></td>
										<td style="text-align: center;"><b><?php echo format_rp($total_bbb); ?></b></td>
										<input type="hidden" name="bbb" readonly="true" value="<?php echo $total_bbb; ?>">
										<td></td>
									</tr>
									<tr>
										<td><b>Biaya Tenaga Kerja Langsung</b></td>
										<td style="text-align: center;"><b><?php echo format_rp($total_btkl); ?></b></td>
										<input type="hidden" name="btkl" readonly="true" value="<?php echo $total_btkl; ?>">
										<td style="text-align: "></td>
									</tr>
									<tr>
										<td><b>Biaya <i>Overhead</i> Pabrik</b></td>
										<td style="text-align: center;"><b><?php echo format_rp($total_bop); ?></b></td>
										<input type="hidden" name="bop" readonly="true" value="<?php echo $total_bop; ?>">
										<td></td>
									</tr>
									<tr>
										<td><b>Biaya Produksi</b></td>
										<td></td>
										<td style="text-align: center;"><b><?php echo format_rp($total_produksi); ?></b></td>
										<input type="hidden" name="total" readonly="true" value="<?php echo $total_produksi; ?>">
									</tr>
								</tbody>
							</table>
						</div>
						<?php echo form_close(); ?>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
<!--EndContent-->
<script type="text/javascript">
	$(document).ready(function(){
		 //Horizontal form basic
    $('#wizard_horizontal').steps({
        headerTag: 'h2',
        bodyTag: 'section',
        transitionEffect: 'slideLeft',
        onInit: function (event, currentIndex) {
            setButtonWavesEffect(event);
        },
        onStepChanged: function (event, currentIndex, priorIndex) {
            setButtonWavesEffect(event);
        },
         onFinished: function (event, currentIndex) {
         	$.ajax({
         		url:  $('#KonfirmasiProduksi').attr('action'),
                type: $('#KonfirmasiProduksi').attr('method'),
                data: $('#KonfirmasiProduksi').serialize(),
                dataType: 'json',
                success: function(respon){
                	$('input[name=csrf_test_name]').val(respon.token);
                	setTimeout(function(){location.href="<?php echo site_url('Pesanan_Controller/KonfirmasiPilihPesanan') ?>"} , 1000);
            		if(respon.success == true)
            		{
            			Push.Permission.request(() => {
                        Push.create('NOTIFIKASI', {
                        body: 'Biaya Produksi Pesanan Berhasil Dikonfirmasi',
                        icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                        timeout: 10000,
                        onClick: () => {
                                     
                        }
                        });
                        });

            		}else{
            			Push.Permission.request(() => {
                        Push.create('NOTIFIKASI', {
                        body: 'Terjadi Kesalahan',
                        icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                        timeout: 10000,
                        onClick: () => {
                                     
                        }
                        });
                        });
            		}    	
                 }
         	});
            
        }
    });
	});
	function setButtonWavesEffect(event) {
    $(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
    $(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
	}
</script>
</body>
</html>