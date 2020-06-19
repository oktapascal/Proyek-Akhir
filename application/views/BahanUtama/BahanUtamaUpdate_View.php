<!DOCTYPE html>
<html>
<head>
	<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
</head>
<body>
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li>
            <i class="material-icons">local_activity</i> <?php echo $sub_navigasi; ?>
          </li>
          <li class="active">
            <i class="material-icons">border_color</i> <?php echo $sub_navigasi2; ?>
          </li>
        </ol>
    </div>
 <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-blue-grey">
                            <h2>UPDATE DATA BAHAN UTAMA<small>Sebelum Update, Pastikan Anda Sudah Yakin Untuk Menngubah Data Bahan Utama ini.</small></h2>
                        </div>
            <div class="body">
            <?php echo form_open('BahanUtama_Controller/UpdateBahanUtama', array('id'=>'UpdateBahanUtama')); ?>
						<label for="kode_bahan">Kode Bahan</label>
		    			<div class="form-group">
						<div class="form-line">
						<input type="text" name="kode_bahan" class="form-control" autocomplete="off" readonly value="<?php echo $data['id_bahan'];?>">
						</div>
						</div>
						<label for="nama_bahan">Nama Bahan</label>
						<div class="form-group">
						<div class="form-line">
						<input type="text" name="nama_bahan" id="nama_bahan_update" class="form-control" autocomplete="off" placeholder="Masukkan Nama Bahan" value="<?php echo $data['nama_bahan']; ?>">
						</div>
						</div>
						<label for="satuan">Satuan Bahan</label>
						<div class="form-group">
						<div class="form-line">
						<input type="text" name="satuan" id="satuan_update" class="form-control" autocomplete="off" placeholder="Masukkan Satuan Bahan" value="<?php echo $data['satuan']; ?>">
						</div>
						</div>
			    		<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">UPDATE BAHAN UTAMA</button>
						<?php echo form_close(); ?>
                        </div>
                    </div>
               </div>
     </div>
     <script type="text/javascript">
     	$(document).ready(function(){

     		jQuery.validator.addMethod("lettersonly", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
            });

                  $('#UpdateBahanUtama').validate({
                rules:{
                    "nama_bahan":{
                        required: true,
                        lettersonly: "[a-zA-Z]+",
                        minlength: 5,
                        maxlength: 50
                    },
                    "satuan":{
                        required: true,
                        lettersonly: "[a-zA-Z]+",
                        minlength: 3,
                        maxlength: 10
                    }
                },
                messages:{
                    "nama_bahan":{
                        required: "Nama Bahan Tidak Boleh Kosong",
                        maxlength: "Nama Bahan Maksimal {0} Karakter",
                        minlength: "Nama Bahan Minimal {0} Karakter",
                        lettersonly: "Nama Bahan Tidak Mengandung Angka"
                    },
                    "satuan":{
                        required: "Satuan Bahan Tidak Boleh Kosong",
                        maxlength: "Satuan Bahan Maksimal {0} Karakter",
                        minlength: "Satuan Bahan Minimal {0} Karakter",
                        lettersonly: "Satuan Bahan Tidak Mengandung Angka"
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

            //Submit Update Bahan Utama//
             $('#UpdateBahanUtama').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#UpdateBahanUtama').attr('action'),
                    type: $('#UpdateBahanUtama').attr('method'),
                    data: $('#UpdateBahanUtama').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Bahan Utama Berhasil Diubah";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('BahanUtama_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Bahan Utama Berhasil Diubah',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/BahanUtama_Controller/index'); ?>');
                                }
                            });
                        });   
                        }
                        else{
                            $.each(respon.messages, function(error, value){
                             var element = $('#' + error);
                             $('#UpdateBahanUtama')[0].reset();
                             var report = value;
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