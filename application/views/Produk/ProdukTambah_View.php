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
                            <h2>TAMBAH PRODUK<small>Sebelum Simpan, Pastikan Anda Sudah Yakin Untuk Menyimpan Data Produk ini.</small></h2>
                        </div>
                        <div class="body">
                        <?php echo form_open('Produk_Controller/SimpanProduk', array('id'=>'TambahProduk')); ?>
                            <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <div class="form-line">
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" autocomplete="off" placeholder="Masukkan Nama Produk">
                            </div>
                            </div>
                            <div class="form-group">
                            <label for="ukuran">Ukuran Produk</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="ukuran" id="ukuran" required="true">
                            <option value="" disabled selected>-- Pilih Ukuran Produk --</option>
                            <option value="M">M (Medium)</option>
                            <option value="L">L (Large)</option>
                            <option value="XL">XL (Extra Large)</option>
                            </select>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">SIMPAN PRODUK</button>
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


            $('#TambahProduk').validate({
                rules:{
                    "nama_produk":{
                        required: true,
                        maxlength: 50,
                        minlength: 5,
                        lettersonly: "[a-zA-Z]+"
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
                        required: "Ukuran Produk Harus Dipilih"
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

            //Submit Tambah Produk//
             $('#TambahProduk').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahProduk').attr('action'),
                    type: $('#TambahProduk').attr('method'),
                    data: $('#TambahProduk').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $('#TambahProduk')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Produk Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Data Produk Berhasil Dimasukkan',
                            icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
                            timeout: 10000,
                            onClick: () => {
                                     location.replace('<?php echo site_Url('/Produk_Controller/index'); ?>');
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