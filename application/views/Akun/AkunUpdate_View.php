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
            <i class="material-icons">local_atm</i> <?php echo $sub_navigasi; ?>
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
                            <h2>UPDATE DATA AKUN<small>Sebelum Update, Pastikan Anda Sudah Yakin Untuk Mengubah Data Akun ini.</small></h2>
                        </div>
                        <div class="body">
                      <?php echo form_open('Akun_Controller/UpdateAkun', array('id'=>'UpdateAkun')); ?>
        							<div class="form-group">
        			    			<label for="header_akun">Kepala Akun</label>
        							<div class="form-line">
        							<input type="text" name="header_akun" id="header_akun" class="form-control" autocomplete="off" value="<?php echo $data['header_akun']; ?>" readonly>
        							</div>
        							</div>
        							<div class="form-group">
        							<label for="kode_akun">Kode Akun</label>
        							<div class="form-line">
        							<input type="text" name="kode_akun" id="kode_akun" class="form-control" autocomplete="off" value="<?php echo $data['no_akun']; ?>" readonly>
        							</div>
        							</div>
        							<div class="form-group">
        							<label for="nama_akun">Nama Akun</label>
        							<div class="form-line">
        							<input type="text" name="nama_akun" id="nama_akun" class="form-control" autocomplete="off" placeholder="Masukkan Nama Akun" value="<?php echo $data['nama_akun']; ?>">
        							</div>
        							</div>
        			    			<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">UPDATE AKUN</button>
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

            $('#UpdateAkun').validate({
                rules:{
                    "nama_akun":{
                        required: true,
                        lettersonly: "[a-zA-Z]+",
                        minlength: 3,
                        maxlength: 50
                    }
                },
                messages:{
                    "nama_akun":{
                        required: "Nama Akun Tidak Boleh Kosong",
                        maxlength: "Nama Akun Maksimal {0} Karakter",
                        minlength: "Nama Akun Minimal {0} Karakter",
                        lettersonly: "Nama Akun Tidak Mengandung Angka"
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

            //Submit Update Akun//
             $('#UpdateAkun').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#UpdateAkun').attr('action'),
                    type: $('#UpdateAkun').attr('method'),
                    data: $('#UpdateAkun').serialize(),
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
                            var text      = "Data Akun Berhasil Diubah";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('Akun_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Akun Berhasil Diubah',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/Akun_Controller/index'); ?>');
                                }
                            });
                        });   
                        }
                        else{
                            $.each(respon.messages, function(error, value){
                             var element = $('#' + error);
                              $('#UpdateAkun')[0].reset();
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