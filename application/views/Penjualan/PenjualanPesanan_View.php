<!DOCTYPE html>
<html>
<head>
	<!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	 <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
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
            <i class="material-icons">shopping_basket</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-red">
    				<h2>Daftar Produk Selesai Atas Pesanan <?php echo $this->session->userdata('pesanan_selesai'); ?></h2>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
    				<table class="table table-bordered table-striped table-hover dataTable" id="TabelPesananSelesai">
    					<thead>
    						<tr>
    							<th style="text-align: center;">Kode Produk</th>
    							<th style="text-align: center;">Nama Produk</th>
    							<th style="text-align: center;">Jumlah</th>
    							<th style="text-align: center;">Aksi</th>
    						</tr>
    					</thead>
    					<tbody style="text-align: center;">
    						
    					</tbody>
    				</table>		
    				</div>
    			</div>
    		</div>
			
    		<div class="card">
    			<div class="header bg-blue-grey">
    				<h2>Keranjang Penjualan Pesanan <?php echo $this->session->userdata('pesanan_selesai'); ?></h2>
    			</div>
    			<div class="body">
    			<div class="table-responsive">
    				<?php if(!empty($penjualan)){ ?>
    				<table class="table table-hover" id="TabelKeranjangPesanan">
    					<thead>
    						<tr>
    							<th style="text-align: center;">Kode Produk</th>
    							<th style="text-align: center;">Nama Produk</th>
    							<th style="text-align: center;">Jumlah</th>
    							<th style="text-align: center;">Harga Jual Per Produk</th>
    							<th style="text-align: center;">Subtotal</th>
    							<th style="text-align: center;">Aksi</th>
    						</tr>
    					</thead>
    					<tbody>
    						<?php 
    						$total = 0;
    						foreach($penjualan as $pnj){ ?>
							<tr>
								<td style="text-align: center;"><?php echo $pnj['id_produk']; ?></td>
								<td style="text-align: center;"><?php echo $pnj['nama_produk']; ?></td>
								<td style="text-align: center;"><?php echo $pnj['jumlah']; ?></td>
								<td style="text-align: center;"><?php echo format_rp($pnj['harga_jual']); ?></td>
								<td style="text-align: center;"><?php echo format_rp($pnj['subtotal']); ?></td>
								<td style="text-align: center;"><a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/Penjualan_controller/HapusProdukPesanan/');?><?php echo $pnj['id_produk'];?>"><i class="material-icons">delete_sweep</i></a></td>
							</tr>
    						<?php 
    						$total = $total + $pnj['subtotal'];
    						} ?>
    						 <tr>
                                <td style="font-weight: bold;text-align: center;" colspan="4">Total Penjualan</td>
                                <td style="font-weight: bold;text-align: center;"><?php echo format_rp($total); ?></td>
                                 <td style="font-weight: bold;text-align: center;">-</td>
                             </tr>
    					</tbody>
    					<tfoot><tr><th colspan="6"> <a role="button" id="konfirmasi" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Penjualan_Controller/SimpanPenjualan'); ?>">KONFIRMASI PENJUALAN</a></th></tr></tfoot>
    				</table>
    			<?php }else{ ?>
					<table class="table table-hover" id="TabelKeranjangPesanan">
                         <thead><tr><th><h1 style="text-align: center;"><i style="font-size: 48px;" class="material-icons">remove_shopping_cart</i></h1></th></tr></thead>
                        </table>
    			<?php } ?>
    			</div>	
    			</div>
    		</div>
    	</div>
    </div>
    <!--EndContent-->
    <script type="text/javascript">
    	$(document).ready(function(){
    		var dataTable;
            var urlTable  = '<?php echo site_url('Penjualan_Controller/LoadPesananSelesai'); ?>';
              dataTable = $('#TabelPesananSelesai').DataTable({
                    bAutoWidth: false,
                    responsive: true,
                    "processing":true,
                    "serverSide":true,
                    "order":[],
                    "ajax":{
                        url:urlTable,
                        type:"GET"
                    },
                    "columnDefs":[
                        {
                            "targets":[3],
                            "orderable":false,
                        }
                    ],
                    "language": {
                    "lengthMenu": "Menampilkan _MENU_ data per halaman",
                    "zeroRecords": "Maaf, data yang anda cari tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(Tersaring dari _MAX_ data)",
                    "loadingRecords": "Sabar yah...",
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Awal",
                        "last":  "Akhir",
                        "next":  "Selanjutnya",
                        "previous": "Sebelumnya"
                        }
                    }
                });
    	});

    	function jualproduk(id_produk)
    	{
    		$.ajax({
    			type: 'GET',
                url: '<?php echo site_url('/Penjualan_Controller/TambahPenjualan/'); ?>' + id_produk,
                dataType: 'json',
                success: function(respon){
                	if(respon.success == true)
                	{
                		$("#TabelKeranjangPesanan").load(" #TabelKeranjangPesanan");
                		 var placementFrom = 'top';
                         var placementAlign = 'right';
                         var animateEnter = 'animated rotateInDownRight';
                         var animateExit = 'animated rotateOutDownRight';
                         var colorName   = 'alert-success';
                         var text      = "Penjualan Berhasil Ditambahkan";
                         showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);

                	}else{
                		var placementFrom = 'top';
                        var placementAlign = 'right';
                        var animateEnter = 'animated rotateInDownRight';
                        var animateExit = 'animated rotateOutDownRight';
                        var colorName   = 'alert-danger';
                        var text      = "Penjualan Gagal Ditambahkan";
                        showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                	}
                }
    		});
    	}
    </script>
      <script src="<?php echo base_url('assets/js-file/notification.js'); ?>"></script>
</body>
</html>