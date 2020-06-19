<!DOCTYPE html>
<html>
<head>
	 <!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>
</head>
<body>
	<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">supervisor_account</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>

    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header bg-red">
    				<h2>Tabel <?php echo $navigasi; ?></h2>
    				<ul class="header-dropdown m-r--5">
    					<li class="dropdown">
    						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    							<i class="material-icons">more_vert</i>
    						</a>
    						<ul class="dropdown-menu pull-right">
                               <li><a role="button" data-toggle="modal" name="tambah" id="tambah" rel="modal:open">Tambah Pegawai Kontrak</a></li>
                            </ul>
    					</li>
    				</ul>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
    					<table class="table table-bordered table-striped table-hover dataTable" id="TabelPegawaiKontrak">
    						<thead>
    							<tr>
    								<th style="text-align: center;">Kode Pegawai</th>
    								<th style="text-align: center;">Nama Pegawai</th>
                                    <th style="text-align: center;">Posisi Pegawai</th>
                                    <th style="text-align: center;">Tanggal Masuk</th>
                                    <th style="text-align: center;">Aksi</th>
    							</tr>
    						</thead>
    						<tfoot >
    							<tr>
    								<th style="text-align: center;">Kode Pegawai</th>
                                    <th style="text-align: center;">Nama Pegawai</th>
                                    <th style="text-align: center;">Posisi Pegawai</th>
                                    <th style="text-align: center;">Tanggal Masuk</th>
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
    <?php $this->load->view('Pegawai/PegawaiKontrak/PegawaiKontrakDetail_View'); ?>

   	<script type="text/javascript">
        $(document).ready(function(){
            var dataTable;
            var urlTable  = '<?php echo site_url('PegawaiKontrak_Controller/LoadPegawaiKontrak'); ?>';
              dataTable = $('#TabelPegawaiKontrak').DataTable({
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
                            "targets":[4],
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

              $('#tambah').click(function(){
                location.replace('<?php echo site_Url('/PegawaiKontrak_Controller/TambahPegawaiKontrak'); ?>');
        });
        });

         function lihatdata(id_pegawai)
        {
            $.ajax({
                type: 'GET',
                url: '<?php echo site_url('PegawaiKontrak_Controller/DetailPegawaiKontrak/'); ?>' + id_pegawai,
                dataType: 'json',
                success: function(pegawai){
                    $('#ModalPegawaiKontrakDetail').modal('show');
                    $('#kode_pegawai').text(pegawai.kode_pegawai);
                    $('#nomor_telepon').text(pegawai.no_telp);
                    $('#mulai_kerja').text(pegawai.tanggal_awal);
                    $('#akhir_Kerja').text(pegawai.tanggal_akhir);
                    $('#alamat').text(pegawai.alamat);
                    $('#nik_pegawai').text(pegawai.nik_pegawai);
                    $('#status_pernikahan').text(pegawai.status_pernikahan);
                    $('#tanggal_lahir').text(pegawai.tanggal_lahir);
                }
            });
        }       
    </script>
</body>
</html>