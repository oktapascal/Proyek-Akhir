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
            <i class="material-icons">assignment_turned_in</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
<!--EndBreadChumbs-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="card">
		<div class="header bg-blue-grey">
		   <h2>KONFIRMASI PESANAN <?php echo $this->session->userdata('no_pesanan_konfirmasi'); ?> PRODUK <?php echo $this->session->userdata('produk_konfirmasi'); ?><small>Hitung Biaya Tenaga Kerja Tidak Langsung Atas Produk Pesanan</small></h2>
		</div>
		<div class="body">
		<?php echo form_open('url', array('id'=>'KonfirmasiPesanan')); ?>
        <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable" id="TabelPegawaiKontrak">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Kode Pegawai</th>
                                <th style="text-align: center;">Nama Pegawai</th>
                                <th style="text-align: center;">Posisi Pegawai</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">
                            
                        </tbody>
                    </table>
        </div>
         <?php echo form_close(); ?>
		</div>
		</div>
        <br/>
        <div class="card">
                <div class="header bg-red">
                    <h2>DETAIL TENAGA KERJA LANGSUNG</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <?php if(!empty($detail_btkl)){ ?>
                        <table class="table table-hover" id="TabelBTKL">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Kode Pegawai</th>
                                    <th style="text-align: center;">Nama Pegawai</th>
                                    <th style="text-align: center;">Jumlah yang Dikerjakan</th>
                                    <th style="text-align: center;">Subtotal BTKL</th>
                                    <th style="text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal = 0;
                                foreach($detail_btkl as $btkl){ ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $btkl['id_pegawai']; ?></td>
                                    <td style="text-align: center;"><?php echo $btkl['nama_pegawai']; ?></td>
                                    <td style="text-align: center;"><?php echo $btkl['jumlah']; ?></td>
                                    <td style="text-align: center;"><?php echo format_rp($btkl['subtotal']); ?></td>
                                    <td style="text-align: center;"><a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/Produksi_controller/HapusBTKL/');?><?php echo $btkl['id_pegawai'];?>"><i class="material-icons">delete_sweep</i></a></td>
                                </tr>
                                <?php
                                $subtotal = $subtotal + $btkl['subtotal'];
                                 } ?>
                                <tr>
                                    <td style="font-weight: bold;text-align: left;" colspan="3">Total Biaya Tenaga Kerja Langsung</td>
                                    <td style="font-weight: bold;text-align: center;"><?php echo format_rp($subtotal); ?></td>
                                </tr>
                            </tbody>
                            <tfoot><tr><th colspan="6"><a role="button" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Produksi_Controller/KonfirmasiBTKL'); ?>">KONFIRMASI BTKL</a></th></tr></tfoot>
                        </table>
                    <?php }else{ ?>
                        <table class="table table-hover" id="TabelBTKL">
                        <thead><tr><th><h1 style="text-align: center;"><i style="font-size: 48px;" class="material-icons">person_add_disabled</i></h1></th></tr></thead>
                        </table>
                    <?php } ?>
                    </div>
                </div>
        </div>
	</div>
</div>
<?php $this->load->view('Produksi/ProduksiModalBTKL_View'); ?>
<script type="text/javascript">
    var dataTable;
    var urlTable  = '<?php echo site_url('Pesanan_Controller/LoadPegawaiKontrak'); ?>';
    $(document).ready(function(){
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
                            "targets":[1,2],
                            "orderable":false,
                        }
                    ],
                     "language": {
                    "lengthMenu": "Menampilkan _MENU_ data per halaman",
                    "zeroRecords": "Maaf, data yang anda cari tidak ditemukan",
                    "info": "Showing halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(Tersaring dari _MAX_ data)",
                    "loadingRecords": "Sabar yah...",
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "paginate": {
                        "first": "first",
                        "last":  "last",
                        "next":  ">",
                        "previous": "<"
                        }
                    }
                });
    });

    function setbtkl(id)
    {
        $.ajax({
                type: 'GET',
                url: '<?php echo site_url('/Produksi_Controller/GetBtkl/'); ?>' + id,
                dataType: 'json',
                success: function(respon){
                    $('#ModalBtklDetail').modal('show');
                    $('#jumlah_kerja').val("");
                    $('#jumlah_pesanan').val(respon.jumlah_pesanan);
                    $('#id_pegawai').text(respon.id_pegawai);
                    $('#pegawai').val(respon.id_pegawai);
                }
            });
    }
</script>
<?php if ($this->session->flashdata('notifikasi_produksi')){ ?>
<script type="text/javascript">
$(document).ready(function($){
Push.Permission.request(() => {
Push.create('NOTIFIKASI', {
body: '<?php echo $this->session->flashdata('notifikasi_produksi'); ?>',
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