<!DOCTYPE html>
<html>
<head>
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
    <!--Numeric-->
    <script src="<?php echo base_url()."assets/autoNumeric/"?>autoNumeric.js"></script>    
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
            <i class="material-icons">assignment_ind</i> <?php echo $sub_navigasi; ?>
          </li>
          <li>
            <i class="material-icons">border_color</i> <?php echo $sub_navigasi2; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
	<!-- form -->
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header bg-blue-grey">
					<h2>UPDATE DATA TARIF DAN POSISI<small>Sebelum Update, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Tarif dan Posisi ini.</small></h2>
				</div>
				<div class="body">
				<?php echo form_open('TarifPosisi_Controller/UpdateTarifPosisi',array('id'=>'UpdateTarifPosisi')); ?>
						<div class="form-group">
					<label for="nama_produk">Status Posisi</label>
					<div class="form-line">
						<input type="text" class="form-control" name="status" id="status" value="<?php echo $data['status']; ?>" readonly>
					</div>
						</div>
					  <div class="form-group">
					  	<label for="kode_posisi">Kode Posisi</label>
                        <div class="form-line">
                          <input type="text" class="form-control" name="kode_posisi" id="kode_posisi" maxlength="3" value="<?php echo $data['id_posisi']; ?>" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="nama_posisi">Nama Posisi</label>
                        <div class="form-line">
                          <input type="text" class="form-control" name="nama_posisi" id="nama_posisi" placeholder="Masukkan Nama Posisi" value="<?php echo $data['nama_posisi']; ?>">
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="makan_tunjangan">Tunjangan Makan</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="makan_tunjangan" name="makan_tunjangan" autocomplete="off" placeholder="Masukkan Tunjangan Makan" value="<?php echo $data['tunjangan_makan']; ?>" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                          <input type="hidden" name="makan" value="<?php echo $data['tunjangan_makan']; ?>" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="kesehatan_tunjangan">Tunjangan Kesehatan</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="kesehatan_tunjangan" name="kesehatan_tunjangan" autocomplete="off" placeholder="Masukkan Tunjangan Kesehatan" value="<?php echo $data['tunjangan_kesehatan']; ?>" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                          <input type="hidden" name="kesehatan" value="<?php echo $data['tunjangan_kesehatan']; ?>" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="produk_tarif">Tarif Setiap Produk</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="produk_tarif" name="produk_tarif" autocomplete="off" placeholder="Masukkan Tarif Setiap Produk" value="<?php echo $data['tarif_per_produk']; ?>" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                          <input type="hidden" name="produk" value="<?php echo $data['tarif_per_produk']; ?>" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="hari_tarif">Tarif Setiap Hari</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="hari_tarif" name="hari_tarif" autocomplete="off" placeholder="Masukkan Tarif Harian" value="<?php echo $data['tarif_per_hari']; ?>" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                          <input type="hidden" name="hari" value="<?php echo $data['tarif_per_hari']; ?>" readonly>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SIMPAN TARIF DAN POSISI</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- Endform -->
    
    <script type="text/javascript">
        $(document).ready(function(){

            jQuery.validator.addMethod("lettersonly", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
            });

            $('#TambahTarifPosisi').validate({
                rules:{
                    "kode_posisi":{
                        required: true,
                        maxlength: 3,
                        minlength: 3,
                        lettersonly: "[a-zA-Z]+"
                    },
                    "nama_posisi":{
                        required: true,
                        maxlength: 30,
                        minlength: 5,
                        lettersonly: "[a-zA-Z]+"
                    },
                    "makan_tunjangan":{
                        required: true
                    },
                    "kesehatan_tunjangan":{
                        required: true
                    },
                    "produk_tarif":{
                        required: true
                    },
                    "hari_tarif":{
                        required: true
                    },
                    "status":{
                        required: true
                    }
                },
                messages:{
                    "kode_posisi":{
                        required: "Kode Posisi Tidak Boleh Kosong",
                        maxlength: "Kode Posisi Maksimal {0} Karakter",
                        minlength: "Kode Posisi Minimal {0} Karakter",
                        lettersonly: "Kode Posisi Tidak Mengandung Angka"
                    },
                    "nama_posisi":{
                        required: "Nama Posisi Tidak Boleh Kosong",
                        maxlength: "Nama Posisi Maksimal {0} Karakter",
                        minlength: "Nama Posisi Minimal {0} Karakter",
                        lettersonly: "Nama Posisi Tidak Mengandung Angka"
                    },
                    "makan_tunjangan":{
                        required: "Tunjangan Makan Tidak Boleh Kosong"
                    },
                    "kesehatan_tunjangan":{
                        required: "Tunjangan Kesehatan Tidak Boleh Kosong"
                    },
                    "produk_tarif":{
                        required: "Tarif Setiap Produk Tidak Boleh Kosong"
                    },
                    "hari_tarif":{
                        required: "Tarif Setiap Hari Tidak Boleh Kosong"
                    },
                    "status":{
                        required: "Status Posisi Wajib Dipilih!"
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

              //Submit Update Tarif Posisi//
             $('#UpdateTarifPosisi').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#UpdateTarifPosisi').attr('action'),
                    type: $('#UpdateTarifPosisi').attr('method'),
                    data: $('#UpdateTarifPosisi').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#UpdateTarifPosisi')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Tarif dan Posisi Berhasil Diubah";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('TarifPosisi_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Tarif dan Posisi Berhasil Diubah',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/TarifPosisi_Controller/index'); ?>');
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
    <!--Custome-->
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js-file/TarifPosisi.js'); ?>"></script>
</body>
</html>