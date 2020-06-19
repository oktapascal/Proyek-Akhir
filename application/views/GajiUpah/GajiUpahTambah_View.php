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
            <i class="material-icons">contact_mail</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-blue-grey">
    				<h2>Tabel <?php echo $navigasi; ?></h2><small>Silahkan Klik Konfirmasi Untuk Menyimpan Data Gaji & Upah</small>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
                        <caption>Pegawai Tetap</caption>
    					<table class="table table-bordered table-striped table-hover dataTable" id="TabelPenggajian">
    						<thead>
    							<tr>
    								<th style="text-align: center;">Kode Pegawai</th>
    								<th style="text-align: center;">Nama Pegawai</th>
    								<th style="text-align: center;">Tunjangan Kesehatan</th>
    								<th style="text-align: center;">Tunjangan Makan</th>
    								<th style="text-align: center;">Tarif Harian</th>
    								<th style="text-align: center;">Bonus Penjualan</th>
    								<th style="text-align: center;">Total Gaji</th>
    							</tr>
    						</thead>
    						<tbody style="text-align: center;">
    							
    						</tbody>
    					</table>
                         <caption>Pegawai Kontrak</caption>
    					<table class="table table-bordered table-striped table-hover dataTable" id="TabelPengupahan">
    						<thead>
    							<tr>
    								<th style="text-align: center;">Kode Pegawai</th>
    								<th style="text-align: center;">Nama Pegawai</th>
    								<th style="text-align: center;">Tunjangan Makan</th>
    								<th style="text-align: center;">Total Tarif Per Produk</th>
    								<th style="text-align: center;">Total Upah</th>
    							</tr>
    						</thead>
    						<tbody style="text-align: center;">
    							
    						</tbody>
    					</table>
    					<a role="button" id="konfirmasi" class="btn btn-primary btn-lg waves-effect" href="<?php echo site_url('GajiUpah_Controller/SimpanGajiUpah'); ?>">KONFIRMASI GAJI & UPAH</a>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <!--ENDContent-->
    <script type="text/javascript">
        var dataTableGaji;
        var urlTableGaji  = '<?php echo site_url('GajiUpah_Controller/LoadGajiPegawaiTetap'); ?>';
        var dataTableUpah;
        var urlTableUpah  = '<?php echo site_url('GajiUpah_Controller/LoadUpahPegawaiKontrak'); ?>';
    	$(document).ready(function(){
            //Penggajian//
             dataTableGaji = $('#TabelPenggajian').DataTable({
                    bAutoWidth: false,
                    responsive: true,
                    "processing":true,
                    "serverSide":true,
                    "order":[],
                    "ajax":{
                        url:urlTableGaji,
                        type:"GET"
                    },
                    "columnDefs":[
                        {
                            "targets":[0,1,2,3,4,5,6],
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
             //Pegupahan//
             dataTableGaji = $('#TabelPengupahan').DataTable({
                    bAutoWidth: false,
                    responsive: true,
                    "processing":true,
                    "serverSide":true,
                    "order":[],
                    "ajax":{
                        url:urlTableUpah,
                        type:"GET"
                    },
                    "columnDefs":[
                        {
                            "targets":[0,1,2,3,4],
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
    </script>
</body>
</html>