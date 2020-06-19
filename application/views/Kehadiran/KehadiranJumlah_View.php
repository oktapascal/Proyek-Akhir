<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
	<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
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
            <i class="material-icons">how_to_reg</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-blue-grey">
    				<h2>Silahkan Pilih Bulan dan Tahun Kehadiran (Presensi)</h2>
    			</div>
    			<div class="body">
    			<?php echo form_open('Presensi_Controller/PilihJumlahPresensi', array('id'=>'PilihJumlahPresensi')); ?>
    				<label for="tanggal">Tanggal Presensi</label>
    				<div class="form-group input-group">
			    	<span class="input-group-addon">
	                <i class="material-icons">date_range</i>
	                </span>
					<div class="form-line">
					<input type="text" name="tanggal_jumlah_presensi" id="tanggal_jumlah_presensi" class="datepicker form-control" autocomplete="off" placeholder="Tanggal Presensi...">
					</div>
					</div>
					<button type="submit" class="btn btn-primary btn-lg btn-block">Cari Presensi</button>
    			<?php echo form_close(); ?>
    			</div>
    		</div>
    	</div>
    </div>
    <!--EndContent-->
    <script type="text/javascript">
    	$(document).ready(function(){

    		$('.datepicker').bootstrapMaterialDatePicker({
        	format: 'DD-MM-YYYY',
        	monthPicker: true,
            year: true,
        	time: false
    	});

    	$('#PilihJumlahPresensi').validate({
    			focusInvalid: false,
                rules:{
                    "tanggal_jumlah_presensi":{
                        required: true
                    }
                },
                messages:{
                    "tanggal_jumlah_presensi":{
                        required: "Tanggal Presensi Tidak Boleh Kosong"
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

    	  //Submit Pilih Tanggal//
             $('#PilihJumlahPresensi').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PilihJumlahPresensi').attr('action'),
                    type: $('#PilihJumlahPresensi').attr('method'),
                    data: $('#PilihJumlahPresensi').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Presensi_Controller/LihatPresensiJumlah'); ?>')
                        }
                        else{
                             var placementFrom = 'top';
                             var placementAlign = 'right';
                             var animateEnter = 'animated rotateInUpRight';
                             var animateExit = 'animated rotateOutUpRight';
                             var colorName = 'alert-danger';
                             var text      = "Tanggal Presensi Harus Dipilih";
                             showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                        }
                    }
                })
            });

    	});
    </script>
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>
</html>