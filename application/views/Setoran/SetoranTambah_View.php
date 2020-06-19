<!DOCTYPE html>
<html>
<head>
	 <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
     <!--Numeric-->
    <script src="<?php echo base_url()."assets/autoNumeric/"?>autoNumeric.js"></script>  
</head>
<body>
<!--Breadchumb-->
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">money</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--ENDBreadchumb-->
<!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue-grey">
				<h2>SETORAN MODAL</h2>
			</div>
			<div class="body">
				<?php echo form_open('Setoran_Controller/SimpanSetoran', array('id'=>'SetoranModal')); ?>
				<div class="form-group">
					<label for="nominal">Jumlah Setoran Modal</label>
					<div class="form-line">
						<input type="text" class="form-control" id="nominal" name="nominal" autocomplete="off" placeholder="Masukkan Jumlah Setoran Modal" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
					</div>
				</div>
				<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">TAMBAH SETORAN MODAL</button>
			</div>
		</div>
	</div>
</div>
<!--EndContent-->
<script type="text/javascript">
	$(document).ready(function(){
		$('#SetoranModal').validate({
                rules:{
                    "nominal":{
                        required: true
                    }
                },
                messages:{
                    "nominal":{
                        required: "Jumlah Setoran Modal Tidak Boleh Kosong"
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

		         //Submit Tambah Setoran//
             $('#SetoranModal').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#SetoranModal').attr('action'),
                    type: $('#SetoranModal').attr('method'),
                    data: $('#SetoranModal').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#SetoranModal')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Setoran Modal Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                                body: 'Setoran Modal Berhasil Dimasukkan',
                                icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                                timeout: 10000,
                                onClick: () => {
                                     location.replace('<?php echo site_Url('/Setoran_Controller/LihatSetoran'); ?>');
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
<script type="text/javascript">
	jQuery(function($){
		 $('#nominal').autoNumeric('init');
	});
</script>
 <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>
</html>