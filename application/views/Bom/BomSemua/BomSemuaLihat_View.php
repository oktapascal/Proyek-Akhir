<!DOCTYPE html>
<html>
<head>
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
            <i class="material-icons">extension</i> <?php echo $sub_navigasi; ?>
          </li>
          <li>
            <i class="material-icons">border_color</i> <?php echo $sub_navigasi2; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
      <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-red">
                            <h2>
                                Bill Of Material Produk <?php echo $id_produk; ?>
                                <small>Berikut Data Bill Of Material (Bahan) yang Telah Dikonfirmasi</small>
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Kode Bahan</th>
                                        <th style="text-align: center;">Nama Bahan</th>
                                        <th style="text-align: center;">Jumlah yang Dibutuhkan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php foreach($bahan as $bhn){ ?>
									<tr>
										<td style="text-align: center;"><?php echo $bhn['id_bahan']; ?></td>
										<td style="text-align: center;"><?php echo $bhn['nama_bahan']; ?></td>
										<td style="text-align: center;"><?php echo $bhn['jumlah'].' '.$bhn['satuan']; ?></td>
									</tr>
                                   <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Hover Rows -->
             <!-- Hover Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-red">
                            <h2>
                                Bill Of Material Produk <?php echo $id_produk; ?>
                                <small>Berikut Data Bill Of Material (Beban) yang Telah Dikonfirmasi</small>
                            </h2>
                        </div>
                        <div class="body table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Kode Beban</th>
                                        <th style="text-align: center;">Nama Beban</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php foreach($beban as $bbn){ ?>
									<tr>
										<td style="text-align: center;"><?php echo $bbn['id_beban']; ?></td>
										<td style="text-align: center;"><?php echo $bbn['nama_beban']; ?></td>
									</tr>
                                   <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Hover Rows -->
</body>
</html>