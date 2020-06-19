<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/js/bootstrap-select.js"></script>
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
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="card">
			<div class="header bg-blue">
				<h2>Data Pegawai <?php echo $this->session->userdata('pegawai_id'); ?></h2>
			</div>
			<div class="body">
				<div class="form-group">
					<label>Kode Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('pegawai_id'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Nama Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('pegawai_nama'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Kode Posisi Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('posisi_id'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Posisi Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('posisi_nama'); ?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label>Status Pegawai</label>
					<div class="form-line">
						<input type="text" class="form-control" value="<?php echo $this->session->userdata('status'); ?>" disabled>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="card">
			<div class="header bg-blue-grey">
				<h2>Formulir Pindah Jabatan Pegawai</h2>
			</div>
			<div class="body">
				<?php echo form_open('Pegawai_Controller/KonfirmasiPindahJabatan', array('id'=>'FormPindahJabatan')); ?>
				<div class="form-group">
					<label for="posisi">Posisi Baru Pegawai</label>
					<div class="form-line">
						<select class="form-control selectpicker show-tick" name="posisi" id="posisi" data-live-search="true">
							<option value="" selected>-- Pilih Posisi --</option>
							<?php foreach($posisi as $posisi){ ?>
            				<option value="<?php echo $posisi['id_posisi']; ?>"><?php echo $posisi['nama_posisi']; ?></option>
            				<?php } ?>
						</select>
					</div>
				</div>
					<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">KONFIRMASI PINDAH JABATAN PEGAWAI</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<!--ENDContent-->
<script type="text/javascript">
	$(document).ready(function(){
	

		 //Submit Pndah Jabatan//
             $('#FormPindahJabatan').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#FormPindahJabatan').attr('action'),
                    type: $('#FormPindahJabatan').attr('method'),
                    data: $('#FormPindahJabatan').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Pegawai_Controller/PindahJabatan'); ?>')
                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Pegawai Berhasil Dipindahkan',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     
                                }
                            });
                        });
                        }
                        else{
                             $.each(respon.messages, function(error, value){
                             var element = $('#' + error);
                             var report  = value;
                             if(report !== '')
                             {
                                var placementFrom = 'top';
                                var placementAlign = 'right';
                                var animateEnter = 'animated rotateInUpRight';
                                var animateExit = 'animated rotateOutUpRight';
                                var colorName = 'alert-danger';
                                var text      = value;
                                showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                             }              
                        });
                        }
                    }
                })
            });

	});
</script>
<script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>
</html>