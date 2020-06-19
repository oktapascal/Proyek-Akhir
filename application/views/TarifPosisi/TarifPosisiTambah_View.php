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
					<h2>TAMBAH DATA TARIF DAN POSISI<small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Tarif dan Posisi ini.</small></h2>
				</div>
				<div class="body">
					<?php echo form_open('TarifPosisi_Controller/SimpanTarifPosisi',array('id'=>'TambahTarifPosisi')); ?>
					<div class="form-group">
					<label for="nama_produk">Status Posisi</label>
					<div class="form-line">
						<select class="form-control selectpicker show-tick" name="status" id="status" required="true">
	                	<option value="" disabled selected>-- Pilih Status Posisi --</option>
	                	<option value="Tetap">Tetap</option>
	                	<option value="Kontrak">Kontrak</option>
                   		</select>
					</div>
					<div class="help-info align-left">Dimohon Untuk Memilih Status Posisi Terlebih Dahulu.</div>
						</div>
					  <div class="form-group">
					  	<label for="kode_posisi">Kode Posisi</label>
                        <div class="form-line">
                          <input type="text" class="form-control" name="kode_posisi" id="kode_posisi" maxlength="3" placeholder="Masukkan Kode Posisi" onkeyup="this.value = this.value.toUpperCase()" autocomplete="off">
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="nama_posisi">Nama Posisi</label>
                        <div class="form-line">
                          <input type="text" class="form-control" name="nama_posisi" id="nama_posisi" placeholder="Masukkan Nama Posisi" autocomplete="off">
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="makan_tunjangan">Tunjangan Makan</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="makan_tunjangan" name="makan_tunjangan" autocomplete="off" placeholder="Masukkan Tunjangan Makan" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="kesehatan_tunjangan">Tunjangan Kesehatan</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="kesehatan_tunjangan" name="kesehatan_tunjangan" autocomplete="off" placeholder="Masukkan Tunjangan Kesehatan" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="produk_tarif">Tarif Setiap Produk</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="produk_tarif" name="produk_tarif" autocomplete="off" placeholder="Masukkan Tarif Setiap Produk" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
                        </div>
                     </div>
                     <div class="form-group">
                     	<label for="hari_tarif">Tarif Setiap Hari</label>
                        <div class="form-line">
                          <input type="text" class="form-control" id="hari_tarif" name="hari_tarif" autocomplete="off" placeholder="Masukkan Tarif Harian" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
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

               //Submit Tambah Tarif Posisi//
             $('#TambahTarifPosisi').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahTarifPosisi').attr('action'),
                    type: $('#TambahTarifPosisi').attr('method'),
                    data: $('#TambahTarifPosisi').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahTarifPosisi')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Tarif dan Posisi Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('TarifPosisi_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Tarif dan Posisi Berhasil Dimasukkan',
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