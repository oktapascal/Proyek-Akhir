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
<!--Beadchumbs-->
	<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li>
            <i class="material-icons">extension</i> <?php echo $sub_navigasi; ?>
          </li>
          <li>
            <i class="material-icons">border_color</i> <?php echo $sub_navigasi2; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!-- Multi Column -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-blue-grey">
                            <h2>
                                Formulir&nbsp;<?php echo $navigasi; ?>&nbsp;Produk&nbsp;<?php echo $id_produk; ?>
                                <small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Bill Of Material Produk ini.</small>
                            </h2>
                        </div>
                        <div class="body">
                            <?php echo form_open('Bom_Controller/TambahBomBeban',array('id'=>'TambahBomBeban')); ?>
                            <div class="row clearfix">
                                    <div class="form-group">
                                      <label for="bahan">Beban Produk</label>
                                        <div class="form-line">
                                            <select class="form-control selectpicker show-tick" name="beban" id="beban" required>
                                            <option value="none" disabled selected>--Pilih Beban--</option>
                                            <?php foreach($beban as $bbn){ ?>
                                            <option value="<?php echo $bbn['id_beban']; ?>"><?php echo $bbn['nama_beban']; ?></option>
                                            <?php } ?>
                                          </select>
                                        </div>
                                    </div>
                            </div>
                            <div class="row clearfix">
                              <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg waves-effect">SIMPAN BILL OF MATERIAL</button>
                              </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Multi Column -->

            <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-red">
                            <h2>
                                Tabel Bill Of Material Produk <?php echo $id_produk; ?>
                                <small>Silahkan Konfirmasi Untuk Menyimpan Bill Of Material</small>
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-hover" id="TabelBom">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">ID Beban</th>
                                        <th style="text-align: center;">Nama Beban</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($bombeban as $items){ ?>
                                        <tr>
                                        <td style="text-align: center;"><?php echo $items['id_beban']; ?></td>
                                        <td style="text-align: center;"><?php echo $items['nama_beban']; ?></td>
                                        <td style="text-align: center;">
                                        <a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/Bom_controller/hapusbeban/');?><?php echo $items['id_beban'];?>"><i class="material-icons">delete_sweep</i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <a role="button" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Bom_Controller/SimpanBomBeban'); ?>">KONFIRMASI BILL OF MATERIAL</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Hover Rows -->
      
    <script type="text/javascript">
      $(document).ready(function(){
        $('#TambahBomBeban').validate({
                rules:{
                    "beban":{
                        required: true
                    }
                },
                messages:{
                    "beban":{
                        required: "Beban Harus Dipilih"
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

        $('#TambahBomBeban').submit(function(e){
          e.preventDefault();
          $.ajax({
                    url: $('#TambahBomBeban').attr('action'),
                    type: $('#TambahBomBeban').attr('method'),
                    data: $('#TambahBomBeban').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahBomBeban')[0].reset();
                            $("#TabelBom").load(" #TabelBom");
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Bill Of Material Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                                body: 'Data Bill Of Material Berhasil Dimasukkan',
                                icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                                timeout: 10000,
                                onClick: () => {
                                     
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
          });
        });
      });
    </script>
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>
</html>