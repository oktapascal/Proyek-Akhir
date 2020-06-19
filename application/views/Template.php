<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sistem Informasi PeKR | <?php echo $this->session->userdata('id_posisi'); ?></title>
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

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>css/themes/all-themes.css" rel="stylesheet" />

</head>

<body class="theme-blue-grey">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Harap Menunggu...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <?php $this->load->view($navbar); ?>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <?php $this->load->view($menu); ?>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php $this->load->view($content); ?>
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap/js/bootstrap.js"></script>
    
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>js/admin.js"></script>

    <!-- Demo Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>js/demo.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

     <!-- Jquery Validation Plugin Css -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/'); ?>plugins/jquery-countto/jquery.countTo.js"></script>
    
    <!-- SweetAlert -->
    <script src="<?php echo base_url('assets/sweetalert2/');?>dist/sweetalert2.all.min.js"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-notify/bootstrap-notify.js"></script>

    <!-- Push -->
    <script src="<?php echo base_url('assets/push/');?>push.min.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Moment Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/momentjs/moment.js"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

     <!-- JQuery Steps Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-steps/jquery.steps.js"></script>  
</body>

</html>
