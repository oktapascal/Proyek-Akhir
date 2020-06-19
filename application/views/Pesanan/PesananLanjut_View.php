<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
	<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
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
            <i class="material-icons">rate_review</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--EndBreadChumbs-->
<!--Form-->
<div class="row clearfix">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="card masked-input">
			<div class="header bg-blue-grey">
              <h2>
                 FORMULIR PESANAN PRODUK
                 <small>Dimohon Untuk Mengisikan Data Dengan Benar</small>
              </h2>
            </div>
            <div class="body">
            	<?php echo form_open('Pesanan_Controller/SimpanPesanan', array('id'=>'LanjutkanPesanan')); ?>
				<label for="nama_pemesan">Nama Pemesan</label>
		    	<div class="form-group input-group">
		    	<span class="input-group-addon">
                <i class="material-icons">person</i>
                </span>
				<div class="form-line">
				<input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control" autocomplete="off" placeholder="Nama Pemesan...">
				</div>
				</div>
				<label for="no_telp">Nomor Telepon</label>
		    	<div class="form-group input-group">
		    	<span class="input-group-addon">
                <i class="material-icons">phone_iphone</i>
                </span>
				<div class="form-line">
				<input type="text" name="no_telp" id="no_telp" class="form-control" autocomplete="off" placeholder="Nomor Telepon...">
				</div>
				</div>
				<button type="submit" class="btn btn-success btn-lg">Konfirmasi Pesanan</button>
            	<?php echo form_close(); ?>
            </div>
		</div>
	</div>
	<div class="col-lg-6 col-md-5 col-sm-6 col-xs-6">
		<div class="card">
			<div class="header bg-orange">
              <h2>
                 DETAIL PESANAN
                 <small>Berikut Daftar Pesanan Anda</small>
              </h2>
            </div>
            <div class="body table-responsive">
            	<table class="table table-hover">
            		<tr>
            			<th style="text-align: center;">Nama Produk</th>
            			<th style="text-align: center;">Jumlah</th>
            			<th style="text-align: center;">Harga Jual</th>
            			<th style="text-align: center;">Subtotal</th>
            		</tr>
            		<?php 
            		$total = 0;
            		foreach($pesanan as $ps){ ?>
            		<tr>
            			<td style="text-align: center;"><?php echo $ps['nama_produk']; ?>&nbsp;(<?php echo $ps['ukuran']; ?>)</td>
            			<td style="text-align: center;"><?php echo $ps['jumlah']; ?></td>
            			<td style="text-align: center;"><?php echo format_rp($ps['harga_jual']); ?></td>
            			<td style="text-align: center;"><?php echo format_rp($ps['subtotal']); ?></td>
            		</tr>
            		<?php } ?>
            	</table>
            </div>
		</div>
	</div>
</div>
<!--EndFrom-->

<script type="text/javascript">
	$(document).ready(function(){
		var $masked = $('.masked-input');
		//Phone Number
    	$masked.find('#no_telp').inputmask('(9999)-9999-9999', { placeholder: '(____)-____-____' });

           jQuery.validator.addMethod("lettersonly", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
            });

          $('#LanjutkanPesanan').validate({
                rules:{
                    "nama_pemesan":{
                        required: true,
                        maxlength: 50,
                        minlength: 5,
                        lettersonly: "[a-zA-Z]+"
                    },
                    "no_telp":{
                        required: true,
                        minlength: 12,
                        maxlength: 16
                    },
                    "tanggal":{
                        required: true
                    }
                },
                messages:{
                    "nama_pemesan":{
                        required: "Nama Pemesan Tidak Boleh Kosong",
                        maxlength: "Nama Pemesan Maksimal {0} Karakter",
                        minlength: "Nama Pemesan Minimal {0} Karakter",
                        lettersonly: "Nama Pemesan Tidak Valid"
                    },
                    "no_telp":{
                        required: "Nomor Telepon Tidak Boleh Kosong",
                        maxlength: "Nomor Telepon Maksimal {0} Karakter",
                        minlength: "Nomor Telepon {0} Karakter"
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

          //Submit Simpan Pesanan//
             $('#LanjutkanPesanan').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#LanjutkanPesanan').attr('action'),
                    type: $('#LanjutkanPesanan').attr('method'),
                    data: $('#LanjutkanPesanan').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#LanjutkanPesanan')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Pesanan Berhasil Dikonfirmasi";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('Pesanan_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Pesanan Berhasil Dikonfirmasi',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/Pesanan_Controller/index'); ?>');
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