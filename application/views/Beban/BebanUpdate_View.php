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
                            <h2>UPDATE DATA BEBAN<small>Sebelum Update, Pastikan Anda Sudah Yakin Untuk Mengubah Data Beban ini.</small></h2>
                        </div>
                        <div class="body">
                           <?php echo form_open('Beban_Controller/UpdateBeban', array('id'=>'UpdateBeban')); ?>
                           <div class="form-group">
                            <label for="kode_beban">Kode Beban</label>
                            <div class="form-line">
                            <input type="text" name="kode_beban" id="kode_beban" class="form-control" autocomplete="off" value="<?php echo $data['id_beban']; ?>" readonly>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="nama_beban">Nama Beban</label>
                            <div class="form-line">
                            <input type="text" name="nama_beban" id="nama_beban" class="form-control" autocomplete="off" value="<?php echo $data['nama_beban']; ?>">
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">UPDATE BEBAN</button>
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


            $('#UpdateBeban').validate({
                rules:{
                    "nama_beban":{
                        required: true,
                        maxlength: 50,
                        minlength: 5,
                        lettersonly: "[a-zA-Z()]+"
                    }
                },
                messages:{
                    "nama_beban":{
                        required: "Nama Beban Tidak Boleh Kosong",
                        maxlength: "Nama Beban Maksimal {0} Karakter",
                        minlength: "Nama Beban Minimal {0} Karakter",
                        lettersonly: "Nama Beban Tidak Mengandung Angka"
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

            //Submit Update Beban//
             $('#UpdateBeban').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#UpdateBeban').attr('action'),
                    type: $('#UpdateBeban').attr('method'),
                    data: $('#UpdateBeban').serialize(),
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
                            var text      = "Data Beban Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('Beban_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Beban Berhasil Diubah',
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