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
 <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-blue-grey">
                            <h2>
                                DAFTAR PRODUK BUTIK MALLA RAMDANI
                                <small>Silahkan Tekan "Pesan" untuk Memesan Produk</small>
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-hover" id="TabelProduk">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Kode Produk</th>
                                        <th style="text-align: center;">Nama Produk</th>
                                        <th style="text-align: center;">Ukuran Produk</th>
                                        <th style="text-align: center;">Harga Jual Produk</th>
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
            <!-- #END# Hover Rows -->
             <?php $this->load->view('Pesanan/PesananTambahDetail_View'); ?>

             <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-red">
                            <h2>
                                Keranjang Pesanan
                                <small>Silahkan Konfirmasi Untuk Melanjutkan Pesanan</small>
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <?php if(!empty($keranjang)){ ?>
                            <table class="table table-hover" id="TabelKeranjang">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Kode Produk</th>
                                        <th style="text-align: center;">Nama Produk</th>
                                        <th style="text-align: center;">Ukuran Produk</th>
                                        <th style="text-align: center;">Jumlah yang Dipesan</th>
                                        <th style="text-align: center;">Subtotal</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $jumlah = 0;
                                        $total  = 0;
                                        foreach($keranjang as $items){?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $items['id_produk']; ?></td>
                                            <td style="text-align: center;"><?php echo $items['nama_produk']; ?></td>
                                            <td style="text-align: center;"><?php echo $items['ukuran']; ?></td>
                                            <td style="text-align: center;"><?php echo $items['jumlah']; ?></td>
                                            <td style="text-align: center;"><?php echo format_rp($items['subtotal']); ?></td>
                                            <td style="text-align: center;"><a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="<?php echo site_url('/Pesanan_controller/HapusPesanan/');?><?php echo $items['id_produk'];?>"><i class="material-icons">delete_sweep</i></a></td>
                                        </tr>
                                    <?php
                                       $total  = $items['subtotal'] + $total; 
                                       $jumlah = $items['jumlah'] + $jumlah;
                                      } ?>
                                    <tr>
                                        <td style="font-weight: bold;text-align: left;" colspan="3">Total Jumlah Pesanan</td>
                                        <td style="font-weight: bold;text-align: center;"><?php echo $jumlah; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold;text-align: left;" colspan="4">Total Pesanan</td>
                                        <td style="font-weight: bold;text-align: center;"><?php echo format_rp($total); ?></td>
                                        <td style="font-weight: bold;text-align: center;">-</td>
                                    </tr>
                                </tbody>
                                <tfoot><tr><th colspan="6"><a role="button" class="btn btn-success btn-lg waves-effect" href="<?php echo site_url('Pesanan_Controller/LanjutkanPesanan'); ?>">LANJUTKAN PESANAN</a></th></tr></tfoot>
                            </table>
                        <?php 
                        }
                        else{
                        ?>
                         <table class="table table-hover" id="TabelKeranjang">
                        <thead><tr><th><h1 style="text-align: center;"><i style="font-size: 48px;" class="material-icons">remove_shopping_cart</i></h1></th></tr></thead>
                        </table>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
<!--ENDCONTENT-->
<script type="text/javascript">
  $(document).ready(function(){
     var dataTable;
             var urlTable  = '<?php echo site_url('Pesanan_Controller/LoadProduk'); ?>';
              dataTable = $('#TabelProduk').DataTable({
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
                            "targets":[3,4],
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
  function pesanproduk(id_produk)
        {
            $.ajax({
                type: 'GET',
                url: '<?php echo site_url('/Pesanan_Controller/GetProduk/'); ?>' + id_produk,
                dataType: 'json',
                success: function(respon){
                    $('#ModalPesananDetail').modal('show');
                    $('#produk').text(respon.nama_produk);
                    $('#kode_produk').val(respon.id_produk);
                    $('#nama_produk').val(respon.nama_produk);
                    $('#ukuran').val(respon.ukuran);
                    $('#harga_jual').val(respon.harga_jual);
                    $('#jumlah').val("");
                }
            });
        }   
</script>
</body>
</html>