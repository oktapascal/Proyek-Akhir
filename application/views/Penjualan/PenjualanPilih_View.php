<!DOCTYPE html>
<html>
<head>
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
            <i class="material-icons">shopping_basket</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-blue-grey">
    				<h2>PENJUALAN PRODUK PESANAN</h2>
    				<small>Silahkan Masukkan Nomor Pesanan</small>
    			</div>
    			<div class="body">
    			<?php echo form_open('Penjualan_Controller/SubmitPesanan', array('id'=>'PenjualanPesanan')); ?>
    		<div class="form-group">
        <label class="form-label">Nomor Pesanan</label> 
				<div class="form-line">
				<input type="text" name="no_pesanan" id="no_pesanan" class="form-control" autocomplete="off" autofocus="true" maxlength="10" placeholder="Nomor Pesanan">
				</div>
				</div>
				<?php echo form_close(); ?>
    			</div>
    		</div>
    	</div>
    </div>
    <!--ENDContent-->
    <script type="text/javascript">
    	$(document).ready(function(){
    		$('#no_pesanan').keyup(function(){
    			if($('#no_pesanan').val().length == $('#no_pesanan').attr('maxlength'))
    			{
    				$('#no_pesanan').blur();
    			}
    		});

    		$('#no_pesanan').blur(function(){
    			$.ajax({
    			 url:  $('#PenjualanPesanan').attr('action'),
                 type: $('#PenjualanPesanan').attr('method'),
                 data: $('#PenjualanPesanan').serialize(),
                 dataType: 'json',
                  success: function(respon){
                  	$('input[name=csrf_test_name]').val(respon.token);
                  	if(respon.success == true)
                  	{
                      window.location.replace('<?php echo site_url('Penjualan_Controller/LihatPesananSelesai'); ?>')
                  	}
                  	else{
                  		$('#PenjualanPesanan')[0].reset();
                  		$('#no_pesanan').focus();
                  		var  report  = respon.messages;
                        if(report != '')
                        {
                          var element = $('#no_pesanan');
                          var placementFrom = 'top';
                          var placementAlign = 'right';
                          var animateEnter = 'animated rotateInUpRight';
                          var animateExit = 'animated rotateOutUpRight';
                          var colorName = 'alert-danger';
                          var text      = respon.messages;
                          showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                  	}

                  }
              	}
    			})
    		});
    	});
    </script>
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
    <?php if ($this->session->flashdata('notifikasi_penjualan')){ ?>
    <script type="text/javascript">
    $(document).ready(function($){
    Push.Permission.request(() => {
    Push.create('NOTIFIKASI', {
    body: '<?php echo $this->session->flashdata('notifikasi_penjualan'); ?>',
    icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
    timeout: 10000,
    onClick: () => {
                                         
     }
    });
    });
    });
</script>
<?php } ?>
</body>
</html>