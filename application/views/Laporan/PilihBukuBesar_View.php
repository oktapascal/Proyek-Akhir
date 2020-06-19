<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/js/bootstrap-select.js"></script>
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
    				<h2>Silahkan Pilih Periode Buku Besar</h2>
    			</div>
    			<div class="body">
    			<?php echo form_open('Laporan_Controller/PilihBukuBesar', array('id'=>'PilihBukuBesar')); ?>
    				<label for="tanggal">Pilih Periode Buku Besar</label>
    				<div class="form-group">
					<div class="form-line">
					<input type="text" name="tanggal_buku_besar" id="tanggal_buku_besar" class="datepicker form-control" autocomplete="off" placeholder="Periode Buku Besar...">
					</div>
					</div>
                    <label for="tanggal">Pilih Akun</label>
                    <div class="form-group">
                    <div class="form-line">
                    <select class="form-control selectpicker show-tick" name="akun" id="akun" data-live-search="true">
                        <option value="" selected>-- Pilih Akun --</option>
                        <?php foreach($akun as $akun){ ?>
                        <option value="<?php echo $akun['no_akun']; ?>"><?php echo $akun['nama_akun']; ?></option>
                        <?php } ?>
                    </select>
                    </div>
                    </div>
					<button type="submit" class="btn btn-primary btn-lg btn-block">Lihat Buku Besar</button>
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
        	clearButton: true,
        	weekStart: 1,
        	time: false
    	});

    	$('#PilihBukuBesar').validate({
    			focusInvalid: false,
                rules:{
                    "tanggal_buku_besar":{
                        required: true
                    },
                    "akun":{
                        required: true
                    }
                },
                messages:{
                    "tanggal_buku_besar":{
                        required: "Periode Buku Besar Tidak Boleh Kosong"
                    },
                    "akun":{
                        required: "Akun Wajib Dipilih"
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
             $('#PilihBukuBesar').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PilihBukuBesar').attr('action'),
                    type: $('#PilihBukuBesar').attr('method'),
                    data: $('#PilihBukuBesar').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Laporan_Controller/LihatBukuBesar'); ?>')
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