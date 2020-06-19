<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Login | Admin PerHarProPen</title>
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
<body class="login-page" style="background-image: url('<?php echo base_url('assets/AdminBSB/images/background.png'); ?>');, position: relative;, background-size: 100%">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin<b><?php echo $this->config->item('singkatan'); ?></b></a>
            <small><?php echo $this->config->item('nama_aplikasi'); ?></small>
        </div>
        <div class="card">
            <div class="body">
                <?php echo form_open('Login_Controller/SubmitLogin', array('id'=>'formLogin')); ?>
                    <div class="msg">Silahkan Login Terlebih Dahulu</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" autofocus autocomplete="off">
                        </div>
                        <div id="error-username"></div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-blue waves-effect" type="submit" data-placement-from="bottom" data-placement-align="left"data-animate-enter="" data-animate-exit="" data-color-name="alert-danger">Masuk</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-12 align-center">
                            <a>&copy;PerHarProPen - 2019</a>
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

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>js/admin.js"></script>
    

    <!-- SweetAlert -->
    <script src="<?php echo base_url('assets/sweetalert2/');?>dist/sweetalert2.all.min.js"></script>
    <!--script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js"></script-->

    <script type="text/javascript">
         $(document).ready(function(){
        $('#formLogin').validate({

    rules: {
        username: "required",
        password: "required"
    },
    messages: {
        username: "Username Tidak Boleh Kosong",
        password: "Password Tidak Boleh Kosong"
    },
    highlight: function (input) {
            console.log(input);
            $(input).parents('.form-line').addClass('error');
        },
    unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
    errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        }
});

    $('#formLogin').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $('#formLogin').attr('action'),
            type: $('#formLogin').attr('method'),
            data: $('#formLogin').serialize(),
            dataType: 'json',
            success: function(respon){
                $('input[name=csrf_test_name]').val(respon.token);
                if(respon.success == true)
                {
                   window.location.href="<?php echo site_url('Login_Controller/SuksesLogin'); ?>";
                }
                else{
                    $.each(respon.messages, function(error, value){
                        var element = $('#' + error);
                        var report  = value;
                        if(report !== ''){
                        var placementFrom = 'bottom';
                        var placementAlign = 'left';
                        var animateEnter = 'animated fadeInDown';
                        var animateExit = 'animated fadeOutUp';
                        var colorName = 'alert-danger';
                        var text      = value;
                        $('#formLogin')[0].reset();
                        showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                        }    
                    });
                }
            }
        })
    });

});

    function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
    var allowDismiss = true;

    $.notify({
        message: text
    },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            timer: 1000,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
}
    </script>
</body>
</html>