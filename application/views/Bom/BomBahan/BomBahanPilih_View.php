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
                            <?php echo form_open('Bom_Controller/TambahBom',array('id'=>'TambahBom')); ?>
                            <div class="row clearfix">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="bahan">Bahan Produk</label>
                                        <div class="form-line">
                                            <select class="form-control selectpicker show-tick" name="bahan" id="bahan" required>
                                            <option value="none" disabled selected>--Pilih Bahan--</option>
                                            <?php foreach($bahan as $bhn){ ?>
                                            <option value="<?php echo $bhn['id_bahan']; ?>"><?php echo $bhn['nama_bahan']; ?></option>
                                            <?php } ?>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="bahan">Jumlah Bahan yang Dibutuhkan</label>
                                        <div class="form-line">
                                            <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan Jumlah Bahan" min="1" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="bahan">Satuan Bahan</label>
                                        <div class="form-line">
                                            <input type="text" id="satuan" name="satuan" class="form-control" placeholder="Satuan Bahan" readonly="readonly">
                                        </div>
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
                                        <th style="text-align: center;">ID Bahan</th>
                                        <th style="text-align: center;">Nama Bahan</th>
                                        <th style="text-align: center;">Jumlah yang Dibutuhkan</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($bombahan as $items){ ?>
                                        <tr>
                                        <td style="text-align: center;"><?php echo $items['id_bahan']; ?></td>
                                        <td style="text-align: center;"><?php echo $items['nama_bahan']; ?></td>
                                        <td style="text-align: center;"><?php echo $items['jumlah'].' '.$items['satuan']; ?></td>
                                        <td style="text-align: center;">
                                        <a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/Bom_controller/hapus/');?><?php echo $items['id_bahan'];?>"><i class="material-icons">delete_sweep</i></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <a role="button" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Bom_Controller/SimpanBomBahan'); ?>">KONFIRMASI BILL OF MATERIAL</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Hover Rows -->
      
    <script type="text/javascript">
      $(document).ready(function(){
        $('#bahan').on('change', function(){
        $.ajax({
          type: 'GET',
          url: '<?php echo site_url('/Bom_Controller/GetSatuan')?>',
          dataType : 'json',
          data: $('#bahan').serialize(),
          success: function(respon){
            $('#satuan').val(respon.satuan);
          }
        });
      });

        $('#TambahBom').validate({
                rules:{
                    "bahan":{
                        required: true
                    },
                    "jumlah":{
                        required: true
                    },
                    "satuan":{
                        required: true
                    }
                },
                messages:{
                    "bahan":{
                        required: "Bahan Harus Dipilih"
                    },
                    "jumlah":{
                        required: "Jumlah Bahan Tidak Boleh Kosong"
                    },
                    "satuan":{
                        required: "Satuan Tidak Boleh Kosong"
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

        $('#TambahBom').submit(function(e){
          e.preventDefault();
          $.ajax({
                    url: $('#TambahBom').attr('action'),
                    type: $('#TambahBom').attr('method'),
                    data: $('#TambahBom').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahBom')[0].reset();
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
    
    //Hapus Bom//

    </script>
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>
</html>