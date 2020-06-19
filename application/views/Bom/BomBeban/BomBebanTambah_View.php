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
    <!--FORM-->
      <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-blue-grey">
                            <h2>
                                PILIH PRODUK
                                <small>Silahkan Pilih Produk yang Akan Ditentukan Bill Of Material (Beban)</small>
                            </h2>
                        </div>
                        <div class="body">
                           <?php echo form_open('Bom_Controller/PilihProduk', array('id'=>'PilihProduk')); ?>
                           <div class="form-group">
                            <label for="produk">Produk</label>
                            <div class="form-line">
                            <select class="form-control selectpicker show-tick" name="produk" id="produk" data-live-search="true">
                            <option value="" disabled selected>-- Pilih Produk --</option>
                            <?php foreach($produk as $pr){ ?>
                            <option value="<?php echo $pr['id_produk']; ?>"><?php echo $pr['nama_produk']; ?> (<?php echo $pr['ukuran']; ?>)</option>
							             <?php } ?>
                            </select>
                            </div>
                            </div>
                            <button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">LANJUTKAN</button>
                           <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
    <!--ENDFORM-->
    <script type="text/javascript">
    	$(document).ready(function(){

        $('#PilihProduk').validate({
                rules:{
                    "produk":{
                        required: true
                    }
                },
                messages:{
                    "produk":{
                        required: "Produk Wajib Dipilih"
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
        
    		//Submit Pilih Produk//
             $('#PilihProduk').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#PilihProduk').attr('action'),
                    type: $('#PilihProduk').attr('method'),
                    data: $('#PilihProduk').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            window.location.replace('<?php echo site_url('Bom_Controller/PilihBomBeban'); ?>')
                        }
                        else{
                             var placementFrom = 'top';
                             var placementAlign = 'right';
                             var animateEnter = 'animated rotateInUpRight';
                             var animateExit = 'animated rotateOutUpRight';
                             var colorName = 'alert-danger';
                             var text      = "Produk Harus Dipilih";
                             showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                        }
                    }
                })
            });

    	});
    </script>
     <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>

<?php if ($this->session->flashdata('notifikasi_beban')){ ?>
<script type="text/javascript">
$(document).ready(function($){
Push.Permission.request(() => {
Push.create('NOTIFIKASI', {
body: '<?php echo $this->session->flashdata('notifikasi_beban'); ?>',
icon: '<?php echo base_url('assets/push/notification-circle-blue-512.png'); ?>',
timeout: 10000,
onClick: () => {
                                     
 }
});
});
});
</script>
<?php } ?>
</body>
</html>