<!DOCTYPE html>
<html>
<head>
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
</head>
<body>
<!--Breadchumbs-->
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">supervisor_account</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--EndBreadchumbs-->
<!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>Data Pegawai <?php echo $this->session->userdata('pegawai_kode'); ?></h2><small>Pastikan Anda Yakin Untuk Mengeluarkan Pegawai ini.</small>
			</div>
			<div class="body">
				<div class="form-group">
					<label>Kode Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('pegawai_kode'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Nama Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('pegawai_nm'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Kode Posisi Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('posisi_kode'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Posisi Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('posisi_nm'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Status Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('status'); ?>" disabled>
					</div>
				</div>
				<a role="button" class="btn btn-block btn-lg btn-primary waves-effect waves-float" href="<?php echo site_url('/Pegawai_Controller/KonfirmasiPegawaiKeluar'); ?>">KONFIRMASI PEGAWAI KELUAR</a>
			</div>
		</div>
	</div>
</div>
<!--ENDContent-->

</body>
</html>