<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap Select Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/bootstrap-select/js/bootstrap-select.js"></script>
     <!--Numeric-->
    <script src="<?php echo base_url()."assets/autoNumeric/"?>autoNumeric.js"></script>  
</head>
<body>
<!--Breadchumb-->
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">local_library</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--ENDBreadchumb-->
<!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue-grey">
				<h2>PEMBAYARAN BEBAN</h2>
    			<small>Silahkan Pilih Beban yang Akan Dimasukkan</small>
			</div>
			<div class="body">
				<?php echo form_open('PembayaranBeban_Controller/PilihBeban', array('id'=>'PilihBeban')); ?>
				<div class="form-group">
					<label for="beban">Beban Perusahaan</label>
					<div class="form-line">
						<select class="form-control selectpicker show-tick" name="beban" id="beban" data-live-search="true">
							<option value="" disabled selected>-- Pilih Beban --</option>
							<?php foreach($beban as $bn){ ?>
            				<option value="<?php echo $bn['id_beban']; ?>"><?php echo $bn['nama_beban']; ?></option>
            				<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="nominal">Biaya Beban</label>
					<div class="form-line">
						<input type="text" class="form-control" id="nominal" name="nominal" autocomplete="off" placeholder="Masukkan Biaya Beban" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
					</div>
				</div>
				<div class="form-group">
					<label for="jumlah">Jumlah Produksi</label>
					<div class="form-line">
						<input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan Jumlah Produksi" min="0">
					</div>
				</div>
				<button type="submit" class="btn btn-block btn-lg btn-primary waves-effect">TAMBAH PEMBAYARAN BEBAN</button>
				<?php echo form_close(); ?>
			</div>
		</div>

		<div class="card">
			<div class="header bg-red">
				<h2>Daftar Pembayaran Beban</h2>
			</div>
			<div class="body">
				<?php if(!empty($detail)){ ?>
				<table class="table table-hover" id="PembayaranBeban">
					<thead>
						<tr>
							<th style="text-align: center;">Kode Beban</th>
							<th style="text-align: center;">Nama Beban</th>
							<th style="text-align: center;">Jumlah Produksi</th>
							<th style="text-align: center;">Subtotal</th>
							<th style="text-align: center;">Aksi</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<?php
							$total_pembayaran = 0;
							foreach($detail as $dt)
							{ ?>
							<tr>
							<td style="text-align: center;"><?php echo $dt['id_beban']; ?></td>
							<td style="text-align: center;"><?php echo $dt['nama_beban']; ?></td>	
							<td style="text-align: center;"><?php echo $dt['jumlah_produksi']; ?></td>	
							<td style="text-align: center;"><?php echo format_rp($dt['subtotal']); ?></td>
							<td style="text-align: center;"><a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/PembayaranBeban_controller/HapusBeban/');?><?php echo $dt['id_beban'];?>"><i class="material-icons">delete_sweep</i></a></td>		
							</tr>
							<?php
							$total_pembayaran = $total_pembayaran + $dt['subtotal']; 
							}
							?>
						</tr>
						<tr>
							<td style="text-align: center; font-weight: bold;" colspan="3">Total Pembayaran Beban</td>
							<td style="text-align: center; font-weight: bold;"><?php echo format_rp($total_pembayaran); ?></td>
							<td style="text-align: center; font-weight: bold;">-</td>
						</tr>
						</tbody>
					<tfoot><tr><th colspan="6"> <a role="button" id="konfirmasi" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('PembayaranBeban_Controller/SimpanPembayaranBeban'); ?>">KONFIRMASI PEMBAYARAN BEBAN</a></th></tr></tfoot>
				</table>
			<?php }
				else { ?>
				<table class="table table-hover" id="PembayaranBeban">
                         <thead><tr><th><h1 style="text-align: center;"><i style="font-size: 48px;" class="material-icons">clear</i></h1></th></tr></thead>
                </table>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
<!--ENDContent-->
<script type="text/javascript">
	$(document).ready(function(){

		$('#beban').change(function(){
        var beban = $('#beban').val();

        $.ajax({
                 url: '<?php echo site_url('/PembayaranBeban_Controller/GetTipeBeban'); ?>',
                 type: 'GET',
                 data: {'beban':beban},
                 dataType: 'json',
                 success: function(respon){
                    if(respon.check == true)
                    {
                        $('input[name=jumlah]').prop('readonly', false);
                         $('input[name=jumlah]').val('');
                    }else{   
                        $('input[name=jumlah]').prop('readonly', true);
                        $('input[name=jumlah]').val('0');
                    }
                 }
        })
  	  });

		 $('#PilihBeban').validate({
                rules:{
                    "beban":{
                        required: true
                    },
                    "nominal":{
                        required: true
                    },
                    "jumlah":{
                    	required: true
                    }
                },
                messages:{
                    "beban":{
                        required: "Beban Wajib Dipilih"
                    },
                    "nominal":{
                        required: "Biaya Beban Tidak Boleh Kosong"
                    },
                    "jumlah":{
                        required: "Jumlah Produksi Tidak Boleh Kosong"
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

		  //Submit Pilih Beban//
             $('#PilihBeban').submit(function(e){
             	  var beban    = $('#beban').val();
              	var nominal  = $('#nominal').val();
                var biaya    = nominal.replace('Rp.','');
              	var jumlah   = $('input[name=jumlah]').val();
              	var csfr     = $( "input[name='csrf_test_name']" ).val();
                e.preventDefault();
                $.ajax({
                    url: $('#PilihBeban').attr('action'),
                    type: $('#PilihBeban').attr('method'),
                    data: {'beban':beban, 'nominal':biaya, 'csrf_test_name':csfr, 'jumlah':jumlah},
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                        	 $("#PembayaranBeban").load(" #PembayaranBeban");
                            $('#PilihBeban')[0].reset();
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Data Pembayaran Beban Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
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
<?php if ($this->session->flashdata('notifikasi_hapus')){ ?>
<script type="text/javascript">
$(document).ready(function($){
var placementFrom = 'top';
var placementAlign = 'right';
var animateEnter = 'animated rotateInDownRight';
var animateExit = 'animated rotateOutDownRight';
var colorName = 'alert-success';
var text      = "<?php echo $this->session->flashdata('notifikasi_hapus'); ?>";
showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
});
</script>
<?php } ?>
<?php if ($this->session->flashdata('notifikasi_simpan')){ ?>
<script type="text/javascript">
$(document).ready(function($){
var placementFrom = 'top';
var placementAlign = 'right';
var animateEnter = 'animated rotateInDownRight';
var animateExit = 'animated rotateOutDownRight';
var colorName = 'alert-success';
var text      = "<?php echo $this->session->flashdata('notifikasi_simpan'); ?>";
showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
});
</script>
<?php } ?>
<script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
<script type="text/javascript">
	jQuery(function($){
		 $('#nominal').autoNumeric('init');
	});
</script>
</body>
</html>