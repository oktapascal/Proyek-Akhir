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
			<div class="header bg-blue-grey">
				<h2>Formulir Pindah Jabatan Pegawai</h2><small>Mohon Masukkan Kode Pegawai</small>
			</div>
			<div class="body">
				<?php echo form_open('Pegawai_Controller/LanjutPindahJabatan', array('id'=>'PindahJabatanPegawai')); ?>
				<div class="form-group form-float">
				<div class="form-line">
				<input type="text" name="id_pegawai" id="id_pegawai" class="form-control" autocomplete="off" maxlength="20">
                <label class="form-label">Kode Pegawai</label>
				</div>
				</div>
				<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SELANJUTNYA</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<!--ENDContent-->
<script type="text/javascript">
	$(document).ready(function(){
		$('#PindahJabatanPegawai').validate({
                rules:{
                    "id_pegawai":{
                        required: true,
                    }
                },
                messages:{
                    "id_pegawai":{
                        required: "Kode Pegawai Tidak Boleh Kosong"
                    }
                },
            highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
            }
            });

		 //Submit Pndah Jabatan//
             $('#PindahJabatanPegawai').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PindahJabatanPegawai').attr('action'),
                    type: $('#PindahJabatanPegawai').attr('method'),
                    data: $('#PindahJabatanPegawai').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Pegawai_Controller/PindahJabatanLanjut'); ?>')
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