<!DOCTYPE html>
<html>
<head>
	<!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
	<!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
</head>
<body>
<!--BreadChumbs-->
<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">rate_review</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--EndBreadChumbs-->
<!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-red">
				<h2>Tabel <?php echo $navigasi; ?></h2>
			</div>
			<div class="body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover dataTable" id="TabelPesanan">
					<thead>
						<tr>
							<th style="text-align: center;">Nomor Pesanan</th>
							<th style="text-align: center;">Nama Pemesan</th>
							<th style="text-align: center;">Kontak Pemesan</th>
							<th style="text-align: center;">Aksi</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th style="text-align: center;">Nomor Pesanan</th>
							<th style="text-align: center;">Nama Pemesan</th>
							<th style="text-align: center;">Kontak Pemesan</th>
							<th style="text-align: center;">Aksi</th>
						</tr>
					</tfoot>
					<tbody style="text-align: center;">
    							
    				</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!--EndContent-->
<script type="text/javascript">
	 $(document).ready(function(){
            var dataTable;
            var urlTable  = '<?php echo site_url('Pesanan_Controller/LoadPesanan'); ?>';
              dataTable = $('#TabelPesanan').DataTable({
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
                            "targets":[2,3],
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