<!DOCTYPE html>
<html>
<head>
	 <!-- JQuery DataTable Css -->
    <link href="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

     <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/AdminBSB/');?>plugins/jquery/jquery.min.js"></script>

  	<!-- SweetAlert -->
    <script src="<?php echo base_url('assets/sweetalert2/');?>dist/sweetalert2.all.min.js"></script>

     <!-- Numeral -->
    <script src="<?php echo base_url()."assets/numeral"?>/min/numeral.min.js"></script>
</head>
<body>
	<div class="block-header">
        <h2><?php echo strtoupper($navigasi)?></h2>
        <ol class="breadcrumb">
         <li>
            <i class="material-icons">home</i> Beranda
         </li>
          <li class="active">
            <i class="material-icons">assignment_ind</i> <?php echo $sub_navigasi; ?>
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
                               <li><a role="button" name="tambah" id="tambah">Tambah Posisi dan Tarif</a></li>
                            </ul>
    					</li>
    				</ul>
    			</div>
    			<div class="body">
    				<div class="table-responsive">
    					<table class="table table-bordered table-striped table-hover dataTable" id="TabelTarifPosisi">
    						<thead>
    							<tr>
    								<th style="text-align: center;">Kode Posisi</th>
    								<th style="text-align: center;">Nama Posisi</th>
                                    <th style="text-align: center;">Status Posisi</th>
                                    <th style="text-align: center;">Aksi</th>
    							</tr>
    						</thead>
    						<tfoot >
    							<tr>
    								<th style="text-align: center;">Kode Posisi</th>
                                    <th style="text-align: center;">Nama Posisi</th>
                                    <th style="text-align: center;">Status Posisi</th>
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
    <?php $this->load->view('TarifPosisi/TarifPosisiDetail_View'); ?>

   	<script type="text/javascript">
        $(document).ready(function(){
            var dataTable;
            var urlTable  = '<?php echo site_url('TarifPosisi_Controller/LoadTarifPosisi'); ?>';
             dataTable = $('#TabelTarifPosisi').DataTable({
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

             $('#tambah').click(function(){
            location.replace('<?php echo site_Url('/TarifPosisi_Controller/TambahTarifPosisi'); ?>');
        });

        });

         function lihatdata(id_posisi)
        {
            $.ajax({
                type: 'GET',
                url: '<?php echo site_url('TarifPosisi_Controller/DetailTarifPosisi/'); ?>' + id_posisi,
                dataType: 'json',
                success: function(posisi){
                    var tunjangan_kesehatan = numeral(posisi.tunjangan_kesehatan).format('0,0');
                    var tunjangan_makan     = numeral(posisi.tunjangan_makan).format('0,0');
                    var tarif_per_produk    = numeral(posisi.tarif_per_produk).format('0,0');
                    var tarif_per_hari      = numeral(posisi.tarif_per_hari).format('0,0');
                    $('#ModalTarifPosisiDetail').modal('show');
                    $('#kode_posisi').text(posisi.kode_posisi);
                    document.getElementById("tunjangan_kesehatan").innerHTML = "Rp. "+ tunjangan_kesehatan;
                    document.getElementById("tunjangan_makan").innerHTML = "Rp. "+ tunjangan_makan;
                    document.getElementById("tarif_per_produk").innerHTML = "Rp. "+ tarif_per_produk;
                    document.getElementById("tarif_per_hari").innerHTML = "Rp. "+ tarif_per_hari;
                }
            });
        }       
    </script>

</body>
</html>