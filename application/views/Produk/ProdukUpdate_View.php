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
            <i class="material-icons">school</i> <?php echo $sub_navigasi; ?>
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
                            <h2>UPDATE PRODUK<small>Sebelum Update, Pastikan Anda Sudah Yakin Untuk Menngubah Data Produk ini.</small></h2>
                        </div>
                        <div class="body">
                           <?php echo form_open('Produk_Controller/UpdateProduk', array('id'=>'UpdateProduk')); ?>
                            <div class="form-group">
                            <label for="kode_produk">Kode Produk</label>
                            <div class="form-line">
                            <input type="text" name="kode_produk" id="kode_produk" class="form-control" autocomplete="off" value="<?php echo $data['id_produk']; ?>" readonly>
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <div class="form-line">
                            <input type="text" name="nama_produk" id="nama_produk_update" class="form-control" autocomplete="off" value="<?php echo $data['nama_produk']; ?>">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="ukuran">Ukuran Produk</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="ukuran" id="ukuran">
                            <option value="" disabled>-- Pilih Ukuran Produk --</option>
                            <option <?php if($ukuran['ukuran'] == $ukuran['ukuran']){ 
                                echo 'selected="selected"'; 
                            } ?> value="<?php echo $ukuran['ukuran']; ?>"><?php echo $ukuran['ukuran']; ?></option>
                            <?php foreach($all_ukuran as $au){ ?>
                            <option value="<?php echo $au['ukuran']; ?>"><?php echo $au['ukuran']; ?></option>
                            <?php } ?>
                            </select>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">UPDATE PRODUK</button>
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

             $('#UpdateProduk').validate({
                rules:{
                    "nama_produk":{
                        required: true,
                        lettersonly: "[a-zA-Z]+",
                        minlength: 5,
                        maxlength: 50
                    },
                    "ukuran":{
                        required: true
                    }
                },
                messages:{
                    "nama_produk":{
                        required: "Nama Produk Tidak Boleh Kosong",
                        maxlength: "Nama Produk Maksimal {0} Karakter",
                        minlength: "Nama Produk Minimal {0} Karakter",
                        lettersonly: "Nama Produk Tidak Mengandung Angka"
                    },
                    "ukuran":{
                        required: "Ukuran Produk Tidak Boleh Kosong"
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

             //Submit Update Produk//
             $('#UpdateProduk').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#UpdateProduk').attr('action'),
                    type: $('#UpdateProduk').attr('method'),
                    data: $('#UpdateProduk').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#UpdateProduk')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Produk Berhasil Diubah";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                            setTimeout(function(){location.href="<?php echo site_url('Produk_Controller/index') ?>"} , 2000);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Produk Berhasil Diubah',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/Pegawai_Controller/index'); ?>');
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