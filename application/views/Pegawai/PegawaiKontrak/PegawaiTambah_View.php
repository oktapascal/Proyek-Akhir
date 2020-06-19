<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />    
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/js/bootstrap-select.js"></script>
      <!-- Autosize Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/autosize/autosize.js"></script>    
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
            <i class="material-icons">supervisor_account</i> <?php echo $sub_navigasi; ?>
          </li>
          <li>
            <i class="material-icons">border_color</i> <?php echo $sub_navigasi2; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Form-->
	<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-blue-grey">
                            <h2>TAMBAH PEGAWAI KONTRAK<small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Pegawai Kontrak ini.</small></h2>
                        </div>
                        <div class="body">
                        <?php echo form_open('PegawaiKontrak_Controller/SimpanPegawaiKontrak', array('id'=>'TambahPegawaiKontrak')); ?>
                            <div class="form-group">
                            <label for="nik_pegawai">NIK Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="nik_pegawai" id="nik_pegawai" class="form-control" autocomplete="off" placeholder="Masukkan NIK Pegawai">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="nama_pegawai">Nama Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control" autocomplete="off" placeholder="Masukkan Nama Pegawai">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="no_telpon">Nomor Telepon</label>
                            <div class="form-line">
                            <input type="text" name="no_telpon" id="no_telpon" class="form-control" autocomplete="off" placeholder="Masukkan Nomor Telepon" maxlength="12">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="alamat">Alamat Pegawai</label>
                            <div class="form-line">
                            <textarea rows="1" class="form-control no-resize auto-growth" name="alamat" id="alamat" placeholder="Masukkan Alamat Lengkap Pegawai"></textarea>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="posisi">Posisi Pegawai</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="posisi" id="posisi">
                            <option disabled selected>-- Pilih Posisi Pegawai --</option>
                            <?php foreach($posisi as $ps){ ?>
							<option value="<?php echo $ps['id_posisi']; ?>"><?php echo $ps['nama_posisi']; ?></option>
                            <?php } ?>
                            </select>
                            </div>
                            </div>
                             <div class="form-group">
                            <label for="status_pernikahan">Status Pernikahan</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="status_pernikahan" id="status_pernikahan">
                            <option value="" selected>-- Pilih Status Pernikahan --</option>
                            <option value="Menikah">Menikah</option>
                            <option value="Belum Menikah">Belum Menikah</option>
                            </select>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="datepicker form-control" autocomplete="off" placeholder="Tanggal Lahir...">
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SIMPAN PEGAWAI KONTRAK</button>
                        <?php echo form_close(); ?>
                        </div>
                    </div>
               </div>
     </div>
    <!--ENDForm-->

    <script type="text/javascript">
        $(document).ready(function(){
                //Date
         $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD-MM-YYYY',
            clearButton: true,
            weekStart: 1,
            time: false,
            maxDate: new Date()
        });
             //Textarea auto growth
            autosize($('textarea.auto-growth'));

            jQuery.validator.addMethod("lettersonly", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
            });

                $('#TambahPegawaiKontrak').validate({
                rules:{
                    "nama_pegawai":{
                        required: true,
                        maxlength: 30,
                        minlength: 4,
                        lettersonly: "[a-zA-Z]+"
                    },
                     "nik_pegawai":{
                        required: true,
                        maxlength: 17,
                        minlength: 16,
                        digits: true
                    },
                    "no_telpon":{
                        required: true,
                        digits: true,
                        maxlength: 12,
                        minlength: 11
                    },
                    "alamat":{
                        required: true,
                        maxlength: 100
                    },
                    "posisi":{
                        required: true
                    },
                     "status_pernikahan":{
                        required: true
                    },

                    "tanggal_lahir":{
                        required: true
                    }
                },
                messages:{
                    "nama_pegawai":{
                        required: "Nama Pegawai Tidak Boleh Kosong",
                        maxlength: "Nama Pegawai Maksimal {0} Karakter",
                        minlength: "Nama Pegawai Minimal {0} Karakter",
                        lettersonly: "Nama Pegawai Tidak Mengandung Angka"
                    },
                     "nik_pegawai":{
                        required: "NIK Pegawai Tidak Boleh Kosong",
                        maxlength: "NIK Pegawai Maksimal {0} Karakter",
                        minlength: "NIK Pegawai Minimal {0} Karakter",
                        digits: "NIK Pegawai Hanya Berupa Angka"
                    },
                    "no_telpon":{
                        required: "Nomor Telepon Tidak Boleh Kosong",
                        digits: "Nomor Telepon Hanya Berupa Angka",
                        maxlength: "Nomor Telepon Maksimal {0} Karakter",
                        minlength: "Nomor Telepon Minimal {0} Karakter"
                    },
                    "alamat":{
                        required: "Alamat Pegawai Tidak Boleh Kosong",
                        maxlength: "Alamat Pegawai Maksimal {0} Karakter"
                    },
                    "posisi":{
                        required: "Posisi Harus Dipilih"
                    },
                     "status_pernikahan":{
                        required: "Status Pernikahan Harus Dipilih"
                    },
                     "tanggal_lahir":{
                        required: "Tanggal Lahir Pegawai Tidak Boleh Kosong"
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

                //Submit Tambah Pegawai Kontrak//
                $('#TambahPegawaiKontrak').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahPegawaiKontrak').attr('action'),
                    type: $('#TambahPegawaiKontrak').attr('method'),
                    data: $('#TambahPegawaiKontrak').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahPegawaiKontrak')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Pegawai Kontrak Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('PegawaiKontrak_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Pegawai Kontrak Berhasil Dimasukkan',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/PegawaiKontrak_Controller/index'); ?>');
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