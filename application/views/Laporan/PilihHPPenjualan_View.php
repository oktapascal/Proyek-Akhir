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
    				<h2>Silahkan Pilih Periode Harga Pokok Penjualan</h2>
    			</div>
    			<div class="body">
    			<?php echo form_open('Laporan_Controller/PilihHPPenjualan', array('id'=>'PilihHPPenjualan')); ?>
    				<label for="tanggal">Pilih Periode Harga Pokok Penjualan</label>
    				<div class="form-group input-group">
			    	<span class="input-group-addon">
	                <i class="material-icons">date_range</i>
	                </span>
					<div class="form-line">
					<input type="text" name="tanggal_hppenjualan" id="tanggal_buku_besar" class="datepicker form-control" autocomplete="off" placeholder="Periode Harga Pokok Penjualan...">
					</div>
					</div>
					<button type="submit" class="btn btn-primary btn-lg btn-block">Lihat Harga Pokok Penjualan</button>
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

    	$('#PilihHPPenjualan').validate({
    			focusInvalid: false,
                rules:{
                    "tanggal_hppenjualan":{
                        required: true
                    }
                },
                messages:{
                    "tanggal_hppenjualan":{
                        required: "Periode Harga Pokok Penjualan Tidak Boleh Kosong"
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
             $('#PilihHPPenjualan').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PilihHPPenjualan').attr('action'),
                    type: $('#PilihHPPenjualan').attr('method'),
                    data: $('#PilihHPPenjualan').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Laporan_Controller/LihatHPPenjualan'); ?>')
                        }
                        else{
                             var placementFrom = 'top';
                             var placementAlign = 'right';
                             var animateEnter = 'animated rotateInUpRight';
                             var animateExit = 'animated rotateOutUpRight';
                             var colorName = 'alert-danger';
                             var text      = "Periode Harga Pokok Penjualan Harus Dipilih";
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