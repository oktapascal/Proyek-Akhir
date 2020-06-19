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
                            <h2>TAMBAH DATA AKUN<small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Akun ini.</small></h2>
                        </div>
                        <div class="body">
                           <?php echo form_open('Akun_Controller/SimpanAkun', array('id'=>'TambahAkun')); ?>
							<div class="form-group form-float">
							<div class="form-line">
							<input type="text" name="kode_akun" id="kode_akun" class="form-control" autocomplete="off">
                            <label class="form-label">Kode Akun</label>
							</div>
							</div>
							<div class="form-group form-float">
							<div class="form-line">
							<input type="text" name="nama_akun" id="nama_akun" class="form-control" autocomplete="off">
                            <label class="form-label">Nama Akun</label>
							</div>
							</div>
			    			<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SIMPAN AKUN</button>
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

            $('#TambahAkun').validate({
                rules:{
                    "kode_akun":{
                        required: true,
                        maxlength: 3,
                        minlength: 3,
                        digits: true
                    },
                    "nama_akun":{
                        required: true,
                        lettersonly: "[a-zA-Z]+",
                        minlength: 3,
                        maxlength: 50
                    }
                },
                messages:{
                    "kode_akun":{
                        required: "Kode Akun Tidak Boleh Kosong",
                        maxlength: "Kode Akun Maksimal {0} Karakter",
                        minlength: "Kode Akun Minimal {0} Karakter",
                        digits: "Kode Akun Hanya Berupa Angka"
                    },
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

            //Submit Tambah Akun//
             $('#TambahAkun').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahAkun').attr('action'),
                    type: $('#TambahAkun').attr('method'),
                    data: $('#TambahAkun').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahAkun')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Akun Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                                body: 'Data Akun Berhasil Dimasukkan',
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