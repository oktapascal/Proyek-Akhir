<!DOCTYPE html>
<html>
<head>
	<?php 
	$bulan = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April','05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
	$no_bulan = date('m', strtotime($this->session->userdata('tanggal_jurnal')));
	$tahun    = date('Y', strtotime($this->session->userdata('tanggal_jurnal')));
	?>
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
            <i class="material-icons">assignment</i> <?php echo $sub_navigasi; ?>
          </li>
        </ol>
    </div>
    <!--ENDBeadchumbs-->
    <!--Content-->
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-red">
				<h2>Jurnal Umum</h2>
				<?php 
					$periode = $this->session->userdata('tanggal_jurnal'); 
					if(isset($no_bulan, $tahun)){
						if($no_bulan != '' AND $tahun != '')
						{
				?>
				<ul class="header-dropdown m-r--5">
    					<li class="dropdown">
    						<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    							<i class="material-icons">more_vert</i>
    						</a>
    						<ul class="dropdown-menu pull-right">
                               <li><a href="<?php echo site_url('Laporan_Controller/Cetak_Jurnal/'); ?><?php echo $no_bulan?>/<?php echo $tahun; ?>" name="cetak" id="cetak">Cetak Jurnal Umum</a></li>
                            </ul>
    					</li>
    				</ul>
    				<?php 
    			}
    			}
    			?>
			</div>
			<div class="body">
				<?php if(!empty($jurnal)){?>
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th style="text-align: center;" colspan="5">Butik Malla Ramdani</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="5">Jurnal Umum</th>
						</tr>
						<tr>
							<th style="text-align: center;" colspan="5">
								Periode <?php echo $bulan[$no_bulan];?> Tahun <?php echo $tahun; ?>
							</th>
						</tr>
						<tr>
							<th style="text-align: center;">Tanggal Jurnal</th>
							<th style="text-align: center;">Keterangan</th>
							<th style="text-align: center;">Reff</th>
							<th style="text-align: center;">Debet</th>
							<th style="text-align: center;">Kredit</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total_debit  = 0;
						$total_kredit = 0; 
						$jarak = '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
						foreach($jurnal as $jurnal){
						$nama_akun = $this->Laporan_Model->GetNamaAkun($jurnal['no_akun']);
						?>
						<tr>
							<td style="text-align: center;"><?php echo date('d-m-Y', strtotime($jurnal['tanggal'])); ?></td>
							<?php 
							if($jurnal['posisi_db_cr'] == "Debet"){
							$total_debit = $total_debit + $jurnal['nominal'];
							?>
							<td><?php echo $nama_akun['nama_akun']; ?></td>
							<td style="text-align: center;"><?php echo $jurnal['no_akun']; ?></td>
							<td style="text-align: right;"><?php echo format_rp($jurnal['nominal']); ?></td>
							<td style="text-align: center;">-</td>
							<?php }else{ 
								$total_kredit = $total_kredit + $jurnal['nominal'];
							?>
							<td><?php echo "".$jarak."".$nama_akun['nama_akun'].""; ?></td>
							<td style="text-align: center;"><?php echo $jurnal['no_akun']; ?></td>
							<td style="text-align: center;">-</td>
							<td style="text-align: right;"><?php echo format_rp($jurnal['nominal']); ?></td>
							<?php } ?>
						</tr>
					<?php }?>
					<tr>
						<td style="text-align: center; font-weight: bold;" colspan="3">Total</td>
						<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_debit); ?></td>
						<td style="text-align: right; font-weight: bold;"><?php echo format_rp($total_kredit); ?></td>
					</tr>
					</tbody>
				</table>
				<?php } else{ ?>
					<table class="table table-hover table-bordered">
						<tr>
							<thead>
								<th style="text-align: center;"><h1 style="text-align: center;"><i style="font-size: 48px;" class="material-icons">cancel</i></h1></th>
							</thead>
						</tr>
					</table>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!--ENDContent-->
</body>
</html>