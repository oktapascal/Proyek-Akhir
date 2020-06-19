<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />    
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
      <!-- Autosize Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/autosize/autosize.js"></script>
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
                            <h2>UPDATE PEGAWAI TETAP<small>Sebelum Update, Pastikan Anda Sudah Yakin Untuk Menngubah Data Pegawai Tetap ini.</small></h2>
                        </div>
                        <div class="body">
                        <?php echo form_open('PegawaiTetap_Controller/UpdatePegawaiTetap', array('id'=>'UpdatePegawaiTetap')); ?>
                            <div class="form-group">
                            <label for="kode_pegawai">Kode Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="kode_pegawai" id="kode_pegawai" class="form-control" autocomplete="off" placeholder="Masukkan Kode Pegawai" value="<?php echo $data['id_pegawai']; ?>" readonly>
                            <div class="help-info align-left">Kode Pegawai Harus Sesuai dengan Kode Kartu RFID Pegawai.</div>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="nik_pegawai">NIK Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="nik_pegawai" id="nik_pegawai" class="form-control" autocomplete="off" placeholder="Masukkan NIK Pegawai" value="<?php echo $data['nik_pegawai']; ?>">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="nama_pegawai">Nama Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control" autocomplete="off" placeholder="Masukkan Nama Pegawai" value="<?php echo $data['nama_pegawai']; ?>">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="no_telpon">Nomor Telepon</label>
                            <div class="form-line">
                            <input type="text" name="no_telpon" id="no_telpon" class="form-control" autocomplete="off" placeholder="Masukkan Nomor Telepon" value="<?php echo $data['no_hp']; ?>" maxlength="12">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="alamat">Alamat Pegawai</label>
                            <div class="form-line">
                            <textarea rows="1" class="form-control no-resize auto-growth" name="alamat" id="alamat" placeholder="Masukkan Alamat Lengkap Pegawai"><?php echo $data['alamat']; ?></textarea>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir Pegawai</label>
                            <div class="form-line">
                            <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="datepicker form-control" autocomplete="off" placeholder="Tanggal Lahir..." value="<?php echo date('d-m-Y', strtotime($data['tanggal_lahir'])); ?>">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="status_pernikahan">Status Pernikahan</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="status_pernikahan" id="status_pernikahan">
                            <option value="" selected>-- Pilih Status Pernikahan --</option>
                            <?php if($data['status_pernikahan'] == "Menikah"){ ?>
                            <option value="Menikah" selected="selected">Menikah</option>
                            <option value="Belum Menikah">Belum Menikah</option>
                            <?php }else{ ?>
                            <option value="Menikah">Menikah</option>
                            <option value="Belum Menikah" selected="selected">Belum Menikah</option>
                            <?php } ?>
                            </select>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">UPDATE PEGAWAI TETAP</button>
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

                $('#UpdatePegawaiTetap').validate({
                rules:{
                    "kode_pegawai":{
                        required: true,
                        maxlength: 10,
                        minlength: 10,
                        digits: true
                    },
                     "nik_pegawai":{
                        required: true,
                        maxlength: 17,
                        minlength: 16,
                        digits: true
                    },
                    "nama_pegawai":{
                        required: true,
                        maxlength: 30,
                        minlength: 4,
                        lettersonly: "[a-zA-Z]+"
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
                     "status_pernikahan":{
                        required: true
                    },
                    "tanggal_lahir":{
                        required: true
                    }   
                },
                messages:{
                    "kode_pegawai":{
                        required: "Kode Pegawai Tidak Boleh Kosong",
                        maxlength: "Kode Pegawai Maksimal {0} Karakter",
                        minlength: "Kode Pegawai Minimal {0} Karakter",
                        digits: "Kode Pegawai Hanya Berupa Angka"
                    },
                     "nik_pegawai":{
                        required: "NIK Pegawai Tidak Boleh Kosong",
                        maxlength: "NIK Pegawai Maksimal {0} Karakter",
                        minlength: "NIK Pegawai Minimal {0} Karakter",
                        digits: "NIK Pegawai Hanya Berupa Angka"
                    },
                    "nama_pegawai":{
                        required: "Nama Pegawai Tidak Boleh Kosong",
                        maxlength: "Nama Pegawai Maksimal {0} Karakter",
                        minlength: "Nama Pegawai Minimal {0} Karakter",
                        lettersonly: "Nama Pegawai Tidak Mengandung Angka"
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

                //Submit Update Pegawai Tetap//
                $('#UpdatePegawaiTetap').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#UpdatePegawaiTetap').attr('action'),
                    type: $('#UpdatePegawaiTetap').attr('method'),
                    data: $('#UpdatePegawaiTetap').serialize(),
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
                            var text      = "Data Pegawai Tetap Berhasil Diubah";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('PegawaiTetap_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Pegawai Tetap Berhasil Diubah',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/PegawaiTetap_Controller/index'); ?>');
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