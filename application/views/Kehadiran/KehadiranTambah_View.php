<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Kehadiran Pegawai | BMR</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/AdminBSB'); ?>/logo_SAk_icon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>css/style.css" rel="stylesheet">
</head>

<body class="fp-page" style="background-image: url('<?php echo base_url('assets/AdminBSB/images/background2.png'); ?>'); position: relative; background-size: 100%; background-repeat: no-repeat;">
    <div class="fp-box">
        <div class="logo">
            <a href="javascript:void(0);">Presensi<b>BMR</b></a>
            <small>Pegawai Tetap Butik Malla Ramdani</small>
        </div>
        <div class="card">
            <div class="body">
                <?php echo form_open('Presensi_Controller/SubmitPresensi', array('id'=>'InputPresensi')); ?>
                    <div class="msg">
                        Tempelkan Kartu RFID Anda Untuk Menginputkan Presensi Anda Untuk Hari <?php echo hari_ini(date('w')) . ", " . tgl_indo(date('Y-m-d')); ?>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">account_box</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="id_pegawai" name="id_pegawai" placeholder="Tempelkan RFID Anda" maxlength="10" minlength="10" required autofocus autocomplete="off">
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>js/admin.js"></script>

     <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
        $('#id_pegawai').keyup(function() {
            if($('#id_pegawai').val().length == 10)
            {
               $('#id_pegawai').blur();
            }
        });

        $('#id_pegawai').blur(function(){
            $.ajax({
                    url:  $('#InputPresensi').attr('action'),
                    type: $('#InputPresensi').attr('method'),
                    data: $('#InputPresensi').serialize(),
                    dataType: 'json',
                    success: function(respon){
                       $('input[name=csrf_test_name]').val(respon.token);
                       setTimeout(function(){ location.reload(); }, 3000);
                       if(respon.success == true)
                        {
                           var placementFrom = 'top';
                           var placementAlign = 'right';
                           var animateEnter = 'animated rotateInUpRight';
                           var animateExit = 'animated rotateOutUpRight';
                           var colorName = 'alert-success';
                           var text      = "Presensi Berhasil Dimasukkan";
                           showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                        }
                        else{
                             var  report  = respon.messages;
                             if(report != '')
                             {
                             var element = $('#id_pegawai');
                             var placementFrom = 'top';
                             var placementAlign = 'right';
                             var animateEnter = 'animated rotateInUpRight';
                             var animateExit = 'animated rotateOutUpRight';
                             var colorName = 'alert-danger';
                             var text      = respon.messages;
                             showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);                   
                            } 
                        }          
                    }
               })
        });
    });
    </script>
     <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>

</html>