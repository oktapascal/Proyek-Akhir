 <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
<nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand">Selamat Datang Admin <?php echo $this->session->userdata('id_posisi'); ?></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" id="notifikasi" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count" id="jumlah"><?php echo $this->Notifikasi_Model->JumlahNotif(); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFIKASI</li>
                            <li class="body">
                                <ul class="menu">
                                    <?php foreach($notifikasi as $notif){ ?>
                                    <li>
                                        <a href="javascript:void(0);" role="button">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons"><?php echo $notif['icon']; ?></i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><?php echo $notif['notifikasi']; ?></h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> <?php echo $notif['selisih']; ?> menit yang lalu
                                                </p>
                                            </div>
                                        </a>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                </ul>
            </div>
        </div>
    </nav>
    <script type="text/javascript">
        $(function(){
            $('#notifikasi').on('click',function(){
                $.ajax({
                    url: '<?php echo base_url('index.php/Notifikasi_Controller/UpdateNotifikasi'); ?>',
                    success: function(){
                         $("#jumlah").load(" #jumlah");
                    }
                });
            })
        });
    </script>