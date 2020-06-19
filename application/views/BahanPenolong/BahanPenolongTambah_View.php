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
                            <h2>TAMBAH BAHAN PENOLONG<small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Bahan Penolong ini.</small></h2>
                        </div>
                        <div class="body">
                           <?php echo form_open('BahanPenolong_Controller/SimpanBahanPenolong', array('id'=>'TambahBahanPenolong')); ?>
							<div class="form-group form-float">
							<div class="form-line">
							<input type="text" name="nama_bahan" id="nama_bahan" class="form-control" autocomplete="off">
							<label class="form-label">Nama Bahan</label>
							</div>
							</div>
							<div class="form-group form-float">
							<div class="form-line">
							<input type="text" name="satuan" id="satuan" class="form-control" autocomplete="off">
							<label class="form-label">Satuan Bahan</label>
							</div>
							</div>
			    			<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SIMPAN BAHAN PENOLONG</button>
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

			$('#TambahBahanPenolong').validate({
				rules:{
					"nama_bahan":{
						required: true,
						maxlength: 50,
						minlength: 5,
						lettersonly: "[a-zA-Z]+"
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

			 //Submit Tambah Bahan Penolong//
             $('#TambahBahanPenolong').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahBahanPenolong').attr('action'),
                    type: $('#TambahBahanPenolong').attr('method'),
                    data: $('#TambahBahanPenolong').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahBahanPenolong')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Bahan Penolong Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Bahan Penolong Berhasil Dimasukkan',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/BahanPenolong_Controller/index'); ?>');
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