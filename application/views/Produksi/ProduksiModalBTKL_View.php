<!-- Modal Detail Pesanan-->
    <div class="modal fade" id="ModalBtklDetail" tabindex="-1" role="dialog">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content">
    			<div class="modal-header bg-blue-grey">
    				<h4 class="modal-title" id="ModalBtklDetail">Rincian BTKL Pegawai <span id="id_pegawai"></span></h4>
    			</div>
    			<div class="modal-body">
    			<?php echo form_open('Produksi_Controller/SimpanBTKL', array('id'=>'TambahBTKL')); ?>
				<input type="hidden" id="pegawai" name="pegawai" class="form-control" autocomplete="off" readonly="true">
    			<label for="jumlah_pesanan">Jumlah Produk yang Dipesan</label>
    			<div class="form-group">
				<div class="form-line">
				<input type="text" name="jumlah_pesanan" id="jumlah_pesanan" class="form-control" autocomplete="off" readonly="true">
				</div>
				</div>
				<label for="harga">Jumlah Produk yang Dikerjakan Pegawai</label>
				<div class="form-group">
				<div class="form-line">
				<input type="number" name="jumlah_kerja" id="jumlah_kerja" class="form-control" autocomplete="off" placeholder="Masukkan Jumlah Produk yang Dikerjakan Pegawai" min="1">
				</div>
				</div>
				<div class="modal-footer">
    				<button type="submit" class="btn btn-success waves-effect">Konfirmasi BTKL</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
    			</div>
			<?php echo form_close(); ?>
    			</div>
    		</div>
    	</div>
    </div>
    <script type="text/javascript">
    	$(document).ready(function(){
    		$.validator.addMethod("kurangsama",
    		function (value, element, param) {
          	var $otherElement = $(param);
          	return parseInt(value, 10) <= parseInt($otherElement.val(), 10);
    		});

    		$('#TambahBTKL').validate({
                rules:{
                    "jumlah_kerja":{
                        required: true,
                        kurangsama: "#jumlah_pesanan"

                    }
                },
                messages:{
                    "jumlah_kerja":{
                        required: "Jumlah yang Dikerjakan Tidak Boleh Kosong",
                        kurangsama: "Jumlah yang Dikerjakan Tidak Lebih Dari Jumlah Pesanan"
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

            //submitTambahBTKL//
            $('#TambahBTKL').submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: $('#TambahBTKL').attr('action'),
                    type: $('#TambahBTKL').attr('method'),
                    data: $('#TambahBTKL').serialize(),
                    dataType: 'json',
                    success: function(respon){
                        $('input[name=csrf_test_name]').val(respon.token);
                        if(respon.success == true)
                        {
                            $("#TabelBTKL").load(" #TabelBTKL");
                            $('#ModalBtklDetail').modal('hide');
                            var placementFrom = 'top';
                            var placementAlign = 'right';
                            var animateEnter = 'animated rotateInDownRight';
                            var animateExit = 'animated rotateOutDownRight';
                            var colorName = 'alert-success';
                            var text      = "BTKL Berhasil Dimasukkan";
                            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                            Push.Permission.request(() => {
                            Push.create('NOTIFIKASI', {
                            body: 'BTKL Berhasil Ditambahkan',
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