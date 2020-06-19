<!-- Modal Detail Pesanan-->
    <div class="modal fade" id="ModalPesananDetail" tabindex="-1" role="dialog">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content">
    			<div class="modal-header bg-blue-grey">
    				<h4 class="modal-title" id="ModalPesananDetail">Detail Pesanan Produk <span id="produk"></span></h4>
    			</div>
    			<div class="modal-body">
    			<?php echo form_open('Pesanan_Controller/PesanProduk', array('id'=>'TambahPesanan')); ?>
    			<label for="kode_produk">Kode Produk</label>
    			<div class="form-group">
				<div class="form-line">
				<input type="text" name="kode_produk" id="kode_produk" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="nama_produk">Nama Produk</label>
				<div class="form-group">
				<div class="form-line">
				<input type="text" name="nama_produk" id="nama_produk" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="ukuran">Ukuran Produk</label>
				<div class="form-group">
				<div class="form-line">
				<input type="text" name="ukuran" id="ukuran" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="harga_jual">Harga Jual Produk</label>
				<div class="form-group">
				<div class="form-line">
				<input type="text" name="harga_jual" id="harga_jual" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="jumlah">Jumlah Produk yang Dipesan (Pcs)</label>
				<div class="form-group">
				<div class="form-line">
				<input type="number" name="jumlah" id="jumlah" class="form-control" autocomplete="off" min="1" placeholder="Masukkan Jumlah Pesanan">
				</div>
				</div>
				<div class="modal-footer">
    				<button type="submit" class="btn btn-success waves-effect">Pesan Produk!</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
    			</div>
			<?php echo form_close(); ?>
    			</div>
    		</div>
    	</div>
    </div>

    <script type="text/javascript">
    	$(document).ready(function(){

    	 $('#jumlah').keyup(function(){
				this.value = this.value.replace(/[^0-9.]/g,'');
			})

    		$('#TambahPesanan').validate({
                rules:{
                    "jumlah":{
                        required: true,
                        digits: true,
                        min: 1
                    }
                },
                messages:{
                    "jumlah":{
                        required: "Jumlah Pesanan Tidak Boleh Kosong",
                        digits  : "Jumlah Pesanan Hanya Berupa Angka",
                        min: "Jumlah Pesanan Minimal 1 Pcs"
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
             	$('#TambahPesanan').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahPesanan').attr('action'),
                    type: $('#TambahPesanan').attr('method'),
                    data: $('#TambahPesanan').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $("#TabelKeranjang").load(" #TabelKeranjang");
                            $('#ModalPesananDetail').modal('hide');
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "Pesanan Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'Pesanan Berhasil Ditambahkan',
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
    <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>