<!-- Modal Detail Pesanan-->
    <div class="modal fade" id="ModalPembelianDetail" tabindex="-1" role="dialog">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content">
    			<div class="modal-header bg-blue-grey">
    				<h4 class="modal-title" id="ModalBahanPenolongUpdate">Rincian Bahan <span id="nama_bahan"></span></h4>
    			</div>
    			<div class="modal-body">
    			<?php echo form_open('Pembelian_Controller/BeliBahan', array('id'=>'TambahPembelian')); ?>
    			<label for="kode_produk">Kode Produk</label>
    			<div class="form-group">
				<div class="form-line">
				<input type="text" name="id_bahan" id="id_bahan" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="kebutuhan">Total Kebutuhan Bahan</label>
				<div class="form-group">
				<div class="form-line">
                <input type="text" id="total_kebutuhan" class="form-control" autocomplete="off" readonly="true">
				<input type="hidden" name="kebutuhan" id="kebutuhan" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="harga">Harga Beli Per Satuan Bahan</label>
				<div class="form-group">
				<div class="form-line">
				<input type="text" name="harga" id="harga" class="form-control" autocomplete="off" placeholder="Masukkan Harga Beli Bahan" data-a-sign="Rp. " data-a-dec="," data-a-sep="." data-m-dec="0">
				</div>
				</div>
				<div class="modal-footer">
    				<button type="submit" class="btn btn-success waves-effect">Beli Bahan!</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
    			</div>
			<?php echo form_close(); ?>
    			</div>
    		</div>
    	</div>
    </div>
    <!--Numeric-->
    <script src="<?php echo base_url()."assets/autoNumeric/"?>autoNumeric.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){

    		$('#TambahPembelian').validate({
                rules:{
                    "harga":{
                        required: true
                    }
                },
                messages:{
                    "harga":{
                        required: "Harga Beli Per Satuan Bahan Tidak Boleh Kosong"
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

    			 //Submit Pesanan//
             	$('#TambahPembelian').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahPembelian').attr('action'),
                    type: $('#TambahPembelian').attr('method'),
                    data: $('#TambahPembelian').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $("#TabelKeranjang").load(" #TabelKeranjang");
                            $('#ModalPembelianDetail').modal('hide');
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Pesanan Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Pembelian Bahan Berhasil Ditambahkan',
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
                })
            });

    	});
    </script>
    <script type="text/javascript">
        jQuery(function($){
             $('#harga').autoNumeric('init');
        });
    </script>
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>