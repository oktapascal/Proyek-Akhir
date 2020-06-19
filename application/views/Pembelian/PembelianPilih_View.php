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
            <i class="material-icons">redeem</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>RINCIAN BAHAN UNTUK PESANAN <?php echo $no_pesanan; ?></h2>
				 <small>Silahkan Tekan "Beli" untuk Menyimpan Pembelian Bahan</small>
			</div>
			<div class="body table-responsive">
				<table class="table table-hover" id="TabelBahan">
				<thead>
					<tr>
						<th style="text-align: center;">Kode Bahan</th>
						<th style="text-align: center;">Nama Bahan</th>
						<th style="text-align: center;">Aksi</th>
					</tr>
				</thead>
				<tbody style="text-align: center;">
					
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('Pembelian/PembelianModal_View'); ?>

<!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-red">
                            <h2>
                                Keranjang Pembelian
                                <small>Silahkan Klik "Konfirmasi Pembelian" Untuk Konfirmasi Pembelian</small>
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <?php if(!empty($detail)){ ?>
                            <table class="table table-hover" id="TabelKeranjang">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Kode Bahan</th>
                                        <th style="text-align: center;">Nama Bahan</th>
                                        <th style="text-align: center;">Harga Beli Per Satuan Bahan</th>
                                        <th style="text-align: center;">Jumlah yang Dibeli</th>
                                        <th style="text-align: center;">Subtotal</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $jumlah = 0;
                                        $total  = 0;
                                        foreach($detail as $items){?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $items['id_bahan']; ?></td>
                                            <td style="text-align: center;"><?php echo $items['nama_bahan']; ?></td>
                                            <td style="text-align: center;"><?php echo format_rp($items['harga']); ?></td>
                                            <td style="text-align: center;"><?php echo $items['jumlah_bahan']; ?></td>
                                            <td style="text-align: center;"><?php echo format_rp($items['subtotal']); ?></td>
                                            <td style="text-align: center;"><a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/Pembelian_controller/HapusBahan/');?><?php echo $items['id_bahan'];?>"><i class="material-icons">delete_sweep</i></a></td>
                                        </tr>
                                    <?php
                                       $total  = $items['subtotal'] + $total; 
                                       $jumlah = $items['jumlah'] + $jumlah;
                                      } ?>
                                    <tr>
                                        <td style="font-weight: bold;text-align: left;" colspan="3">Total Jumlah Pembelian</td>
                                        <td style="font-weight: bold;text-align: center;"><?php echo $jumlah; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;text-align: left;" colspan="4">Total Pembelian</td>
                                        <td style="font-weight: bold;text-align: center;"><?php echo format_rp($total); ?></td>
                                        <td style="font-weight: bold;text-align: center;">-</td>
                                    </tr>
                                </tbody>
                                <tfoot><tr><th colspan="6"> <a role="button" id="konfirmasi" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Pembelian_Controller/SimpanPembelian'); ?>">KONFIRMASI PEMBELIAN</a></th></tr></tfoot>
                            </table>
                        <?php 
                            } 
                            else{
                         ?>
                         <table class="table table-hover" id="TabelKeranjang">
                         <thead><tr><th><h1 style="text-align: center;"><i style="font-size: 48px;" class="material-icons">remove_shopping_cart</i></h1></th></tr></thead>
                        </table>
                         <?php 
                            }
                         ?>
                        </div>
                    </div>
                </div>
            </div>

<script type="text/javascript">
	$(document).ready(function(){

		var dataTable;
             var urlTable  = '<?php echo site_url('Pembelian_Controller/LoadBahanPesanan'); ?>';
              dataTable = $('#TabelBahan').DataTable({
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
                            "targets":[2],
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

    function pesanbahan(id_bahan)
    {
            $.ajax({
                type: 'GET',
                url: '<?php echo site_url('/Pembelian_Controller/GetBahan/'); ?>' + id_bahan,
                dataType: 'json',
                success: function(respon){
                    $('#ModalPembelianDetail').modal('show');
                    $('#id_bahan').val(respon.id_bahan);
                    $('#total_kebutuhan').val(respon.total_kebutuhan);
                    $('#kebutuhan').val(respon.kebutuhan);
                    $('#nama_bahan').text(respon.nama_bahan);
                    $('#harga').val("");
                }
            });
    }   
</script>
</body>
</html>