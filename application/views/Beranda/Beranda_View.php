<!DOCTYPE html>
<html>
<head>
	 <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
</head>
<body>
	<div class="block-header">
       <h2>BERANDA</h2>
    </div>

    <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">people</i>
                        </div>
                        <div class="content">
                            <div class="text">PEGAWAI TETAP</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $this->Data_Model->jumlah_pegawai('Tetap'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-red hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">group_add</i>
                        </div>
                        <div class="content">
                            <div class="text">PEGAWAI KONTRAK</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $this->Data_Model->jumlah_pegawai('Kontrak'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">store</i>
                        </div>
                        <div class="content">
                            <div class="text">PRODUK</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $this->Data_Model->jumlah_data('produk'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-indigo hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">speaker_notes_off</i>
                        </div>
                        <div class="content">
                            <div class="text">BELUM DIPROSES</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $this->Data_Model->jumlah_data_kondisi('detail_pesanan','Belum Diproses'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">rate_review</i>
                        </div>
                        <div class="content">
                            <div class="text">DALAM PROSES</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $this->Data_Model->jumlah_data_kondisi('detail_pesanan','Dalam Proses'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-blue hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">check_box</i>
                        </div>
                        <div class="content">
                            <div class="text">PESANAN SELESAI</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $this->Data_Model->jumlah_data_kondisi('detail_pesanan','Selesai'); ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-amber hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">add_shopping_cart</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL PEMBELIAN</div>
                            <div class="number">Rp. <?php echo number_format_short($this->Data_Model->total_transaksi('pembelian')); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-deep-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">attach_money</i>
                        </div>
                        <div class="content">
                            <div class="text">TOTAL PENJUALAN</div>
                            <div class="number">Rp. <?php echo number_format_short($this->Data_Model->total_transaksi('penjualan')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->

            <script type="text/javascript">
            	$(document).ready(function(){
            		$('.count-to').countTo();
            	});
            </script>
<?php if ($this->session->flashdata('notifikasi_pembayaran_gaji_upah')){ ?>
<script type="text/javascript">
$(document).ready(function($){
Push.Permission.request(() => {
Push.create('NOTIFIKASI', {
body: '<?php echo $this->session->flashdata('notifikasi_pembayaran_gaji_upah'); ?>',
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