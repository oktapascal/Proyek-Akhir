<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
     <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/js/bootstrap-select.js"></script>  
</head>
<body>
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li>
            <i class="material-icons">bug_report</i> <?php echo $sub_navigasi; ?>
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
                            <h2>TAMBAH DATA BEBAN<small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Beban ini.</small></h2>
                        </div>
                        <div class="body">
                           <?php echo form_open('Beban_Controller/SimpanBeban', array('id'=>'TambahBeban')); ?>
                           <div class="form-group">
                            <label class="form-label">Nama Beban</label>
                            <div class="form-line">
                            <input type="text" name="nama_beban" id="nama_beban" class="form-control" autocomplete="off" placeholder="Nama Beban">
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="form-label">Akun Beban</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="no_akun" id="no_akun" data-live-search="true">
                            <option value="" disabled selected>-- Pilih Akun Beban --</option>
                            <?php foreach($akun->result_array() as $akun){ ?>
                            <option value="<?php echo $akun['no_akun']; ?>"><?php echo $akun['nama_akun']; ?></option>
                            <?php } ?>
                            </select>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SIMPAN BEBAN</button>
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

                $('#TambahBeban').validate({
                rules:{
                    "nama_beban":{
                        required: true,
                        maxlength: 50,
                        minlength: 5,
                        lettersonly: "[a-zA-Z()]+"
                    },
                    "no_akun":{
                        required: true
                    }
                },
                messages:{
                    "nama_beban":{
                        required: "Nama Beban Tidak Boleh Kosong",
                        maxlength: "Nama Beban Maksimal {0} Karakter",
                        minlength: "Nama Beban Minimal {0} Karakter",
                        lettersonly: "Nama Beban Tidak Mengandung Angka"
                    },
                    "no_akun":{
                        required: "Akun Beban Wajib Dipilih"
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

            //Submit Tambah Beban//
             $('#TambahBeban').submit(function(e){
                var beban = $('#nama_beban').val();
                var akun  = $('#no_akun').val();
                var csfr  = $( "input[name='csrf_test_name']" ).val();
                e.preventDefault();
                $.ajax({
                    url: $('#TambahBeban').attr('action'),
                    type: $('#TambahBeban').attr('method'),
                    data: {'nama_beban':beban, 'no_akun':akun, 'csrf_test_name':csfr},
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahBeban')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Beban Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Beban Berhasil Dimasukkan',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/Beban_Controller/index'); ?>');
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