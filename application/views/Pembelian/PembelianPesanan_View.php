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
	<!--Beadchumbs-->
	<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li>
            <i class="material-icons">redeem</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue-grey">
				<h2>
          PILIH PESANAN
           <small>Silahkan Pilih Pesanan yang Akan Dibeli Bahan-Bahannya</small>    
        </h2>
			</div>
      <div class="body">
        <?php echo form_open('Pembelian_Controller/PilihPesanan', array('id'=>'PilihPesanan')); ?>
        <div class="form-group">
        <label for="pesanan">Nomor Pesanan</label>
        <div class="form-line">
           <select class="form-control selectpicker show-tick" name="pesanan" id="pesanan" data-live-search="true">
            <option value="" disabled selected>-- Pilih Nomor Pesanan --</option>
            <?php foreach($pesanan as $ps){ ?>
            <option value="<?php echo $ps['no_pesanan']; ?>"><?php echo $ps['no_pesanan']; ?> (<?php echo $ps['nama_pemesan'] ?>)</option>
            <?php } ?>
           </select>
        </div>
        </div>
        <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">LANJUTKAN PEMBELIAN</button>
        <?php echo form_close(); ?>
      </div>
		</div>
	</div>
</div>
<!--EndContent-->
<script type="text/javascript">
  $(document).ready(function(){
      $('#PilihPesanan').validate({
                rules:{
                    "pesanan":{
                        required: true
                    }
                },
                messages:{
                    "pesanan":{
                        required: "Pesanan Wajib Dipilih"
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

      //Submit Pilih Pesanan//
             $('#PilihPesanan').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PilihPesanan').attr('action'),
                    type: $('#PilihPesanan').attr('method'),
                    data: $('#PilihPesanan').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Pembelian_Controller/PilihPesananProduk'); ?>')
                        }
                        else{
                             var placementFrom = 'top';
                             var placementAlign = 'right';
                             var animateEnter = 'animated rotateInUpRight';
                             var animateExit = 'animated rotateOutUpRight';
                             var colorName = 'alert-danger';
                             var text      = "Pesanan Harus Dipilih";
                             showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                        }
                    }
                })
            });

  });
</script>
<script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>

<?php if ($this->session->flashdata('notifikasi')){ ?>
<script type="text/javascript">
$(document).ready(function($){
Push.Permission.request(() => {
Push.create('NOTIFIKASI', {
body: '<?php echo $this->session->flashdata('notifikasi'); ?>',
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