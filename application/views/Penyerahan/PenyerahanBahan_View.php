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
            <i class="material-icons">local_shipping</i> <?php echo $sub_navigasi; ?>
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
           <small>Silahkan Pilih Pesanan yang Akan Diserahkan Bahan-Bahannya</small>    
        </h2>
			</div>
      <div class="body">
        <?php echo form_open('Penyerahan_Controller/PilihPesanan', array('id'=>'PilihPesanan')); ?>
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
        <div class="form-group">
        <label for="pesanan">Produk Pesanan</label>
        <div class="form-line">
        <select class="form-control selectpicker show-tick" name="produk" id="produk" data-live-search="true">
          <option value="" disabled selected>-- Pilih Produk Pesanan --</option>
        </select>
        </div>
        </div>
        <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">LANJUTKAN PENYERAHAN</button>
        <?php echo form_close(); ?>
      </div>
		</div>
	</div>
</div>
<!--EndContent-->
<script type="text/javascript">
  jQuery(function($){
    $('#pesanan').on('change', function(){
        var pesanan = $('#pesanan').val();
        var select  = '';
        $.ajax({
          url: "<?php echo site_Url('Penyerahan_Controller/GetProduk'); ?>",
          type: "GET",
          data: {'no_pesanan':pesanan},
          dataType: 'json',
          success: function(respon){ 
            select += '<option value="" disabled selected>-- Pilih Produk Pesanan --</option>';
            $.each(respon, function(index, value){
            select += '<option value="'+value[0]+'">'+value[1]+'</option>';
            }); 
            $('#produk').html(select);
            $('#produk').selectpicker('refresh');
          }
        });
      });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
      $('#PilihPesanan').validate({
                rules:{
                    "pesanan":{
                        required: true
                    },
                    "produk":{
                        required: true
                    }
                },
                messages:{
                    "pesanan":{
                        required: "Pesanan Wajib Dipilih"
                    },
                    "produk":{
                        required: "Produk Pesanan Wajib Dipilih"
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
              var pesanan = $('#pesanan').val();
              var produk  = $('#produk').val();
              var csfr    = $( "input[name='csrf_test_name']" ).val();
                e.preventDefault();
                $.ajax({
                    url: $('#PilihPesanan').attr('action'),
                    type: $('#PilihPesanan').attr('method'),
                    data: {'pesanan':pesanan, 'produk':produk, 'csrf_test_name':csfr},
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Penyerahan_Controller/PilihPenyerahanBahan'); ?>')
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

<?php if ($this->session->flashdata('notifikasi_penyerahan')){ ?>
<script type="text/javascript">
$(document).ready(function($){
Push.Permission.request(() => {
Push.create('NOTIFIKASI', {
body: '<?php echo $this->session->flashdata('notifikasi_penyerahan'); ?>',
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