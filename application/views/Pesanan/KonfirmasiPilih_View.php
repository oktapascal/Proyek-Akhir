<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
	<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
     <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/js/bootstrap-select.js"></script>
     <!--Numeric-->
    <script src="<?php echo base_url()."assets/autoNumeric/"?>autoNumeric.js"></script>    
</head>
<body>
<!--BreadChumbs-->
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">assignment_turned_in</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--EndBreadChumbs-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue-grey">
				<h2>Pilih Pesanan dan Produk</h2><small>Silahkan Pilih Nomor Pesanan dan Produk yang Akan Dikonfirmasi</small>
			</div>
			<div class="body">
			  <?php echo form_open('Pesanan_Controller/PilihPesanan', array('id'=>'PilihPesananProses')); ?>
			  <div class="form-group">
			  	<label for="pesanan">Nomor Pesanan</label>
			  	<div class="form-line">
			  		<select class="form-control selectpicker show-tick" name="nomor_pesanan" id="nomor_pesanan" data-live-search="true">
			  			<option value="" selected>-- Pilih Nomor Pesanan --</option>
			  			<?php foreach($pesanan as $psn){ ?>
						<option value="<?php echo $psn['no_pesanan']; ?>"><?php echo $psn['no_pesanan']; ?> (<?php echo $psn['nama_pemesan']; ?>)</option>
			  			<?php } ?>
			  		</select>
			  	</div>
			  </div>
			  <div class="form-group">
			  	<label for="produk">Produk Pesanan</label>
			  	<div class="form-line">
			  		<select class="form-control selectpicker show-tick" name="produk_pesanan" id="produk_pesanan" data-live-search="true">
			  			<option value="" selected>-- Pilih Produk Pesanan --</option>
			  		</select>
			  	</div>
			  </div>
              <div class="form-group">
                <label for="nominal">Taksiran BOP</label>
                <div class="form-line">
                    <input type="text" class="form-control" id="nominal" name="nominal" autocomplete="off" placeholder="Masukkan Jumlah Setoran Modal" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                </div>
              </div>
              <div class="form-group">
                <label for="nominal">Taksiran Jumlah Produk yang Dihasilkan</label>
                <div class="form-line">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" autocomplete="off" placeholder="Masukkan Taksiran Jumlah Produk yang Dihasilkan" min="1">
                </div>
              </div>
			  <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">LANJUTKAN KONFIRMASI PESANAN</button>
			  <?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(function($){
		$('#nomor_pesanan').on('change', function(){
		  var pesanan = $('#nomor_pesanan').val();
		  var select  = '';
		  $.ajax({
		  	 url: "<?php echo site_Url('Pesanan_Controller/GetProdukPesanan'); ?>",
	         type: "GET",
	         data: {'no_pesanan':pesanan},
	         dataType: 'json',
	         success: function(respon){ 
            select += '<option value="" disabled selected>-- Pilih Produk Pesanan --</option>';
            $.each(respon, function(index, value){
            select += '<option value="'+value[0]+'">'+value[1]+'</option>';
            }); 
            $('#produk_pesanan').html(select);
            $('#produk_pesanan').selectpicker('refresh');
          }
		  });
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){
		 $('#PilihPesananProses').validate({
                rules:{
                    "nomor_pesanan":{
                        required: true
                    },
                    "produk_pesanan":{
                        required: true
                    },
                    "nominal":{
                        required: true
                    },
                    "jumlah":{
                        required: true
                    }
                },
                messages:{
                    "nomor_pesanan":{
                        required: "Pesanan Wajib Dipilih"
                    },
                    "produk_pesanan":{
                        required: "Produk Pesanan Wajib Dipilih"
                    },
                    "nominal":{
                        required: "Taksiran BOP Tidak Boleh Kosong"
                    },
                    "jumlah":{
                        required: "Taksiran Jumlah Produk yang Dihasilkan Tidak Boleh Kosong"
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
             $('#PilihPesananProses').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PilihPesananProses').attr('action'),
                    type: $('#PilihPesananProses').attr('method'),
                    data: $('#PilihPesananProses').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Pesanan_Controller/KonfirmasiPesanan'); ?>');
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

<?php if ($this->session->flashdata('notifikasi_konfirmasi')){ ?>
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
<script type="text/javascript">
    jQuery(function($){
         $('#nominal').autoNumeric('init');
    });
</script>
</body>
</html>