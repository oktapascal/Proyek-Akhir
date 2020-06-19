<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Cetak Slip Gaji Pegawai Tetap</title>
	<style type="text/css">
		.line-title{
			border: 0;
			border-style: inset;
			border-top: 1px solid #000;
		}
		.table{
			font-size: 10px;
		}
	</style>
</head>
<body>
<table style="width: 100%;">
	<tr>
		<td align="center">
			<span style="line-height: 1.6; font-weight: bold; ">
				BUTIK MALLA RAMDANI
				<br>
				Studio Butik Dan Jahit
				<br>
				BANDUNG
			</span>
		</td>
	</tr>
</table>
<br class="line-title">
<p align="center">
	SLIP GAJI PEGAWAI TETAP
</p>
<table class="table table-bordered">
	<thead>
    	<tr>
    	<th style="text-align: center; font-size: 20px" colspan="4">SLIP GAJI</th>
    	</tr>
    	<tr>
    	<th style="text-align: center;" colspan="4">===========================================================================================================	</th>
    	</tr>
    	<tr>
    	<th style="text-align: left;">Nama Perusahaan</th>
    	<th style="text-align: left;">Butik Malla Ramdani</th>
    	<th style="text-align: left;">Kode Pegawai</th>
    	<th style="text-align: left;"><?php echo $id_pegawai; ?></th>
    	</tr>
    	<tr>
    	<th style="text-align: left;">Tanggal Gaji</th>
    	<th style="text-align: left;"><?php echo date('d-m-Y', strtotime($data['tanggal_gaji'])); ?></th>
    	<th style="text-align: left;">Nama Pegawai</th>
    	<th style="text-align: left;"><?php echo $data['nama_pegawai']; ?></th>
    	</tr>
    	<tr>
    	<th style="text-align: left">Kode Pembayaran Gaji</th>
    	<th style="text-align: left;"><?php echo $data['id_transaksi']; ?></th>
    	<th style="text-align: left;">Jabatan Pegawai</th>
    	<th style="text-align: left;"><?php echo $data['nama_posisi']; ?></th>
    	</tr>
    	<tr>
    	<th style="text-align: left">Kode Gaji</th>
    	<th style="text-align: left;"><?php echo $id_gaji; ?></th>
    	<th style="text-align: left;">Status Pegawai</th>
    	<th style="text-align: left;"><?php echo $data['status']; ?></th>
    	</tr>
    	<tr>
    	<th style="text-align: center;" colspan="4">===========================================================================================================	</th>
    	</tr>
    </thead>
    <tbody>
    	<tr>
    	<td style="text-align: left; font-weight: bold;" colspan="4">Gaji Pokok Harian :</td>
    	</tr>
    	<tr>
    	<td style="text-align: left" colspan="3"><?php echo $jumlah['jumlah_kehadiran']; ?> X <?php echo format_rp($data['tarif_per_hari']); ?></td>
    	<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['subtotal_perhari']); ?></td>
    	</tr>
    	<tr>
    	<td style="text-align: left; font-weight: bold;" colspan="4">Tunjangan Pegawai Tetap :</td>
    	</tr>
    	<tr>
    	<td style="text-align: left;">Tunjangan Kesehatan</td>
    	<td style="text-align: left; font-weight: bold;" colspan="3"><?php echo format_rp($data['tunjangan_kesehatan']); ?></td>
    	</tr>
    	<tr>
    	<td style="text-align: left;">Tunjangan Makan</td>
    	<td style="text-align: left; font-weight: bold;" colspan="3"><?php echo format_rp($data['tunjangan_makan']); ?></td>
    	</tr>
    	<tr>
    	<td style="text-align: left; font-weight: bold;" colspan="3">Total Tunjangan</td>
    	<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['subtotal_tunjangan']); ?></td>
    	</tr>
    	<tr>
    	<td style="text-align: left; font-weight: bold;" colspan="3">Bonus</td>
    	<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['bonus']); ?></td>
    	</tr>
    	<tr>
    	<td style="text-align: left; font-weight: bold;" colspan="3">Lembur</td>
    	<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['lembur']); ?></td>
    	</tr>
    	<tr>
    	<td style="text-align: left; font-weight: bold;" colspan="3">Total Gaji yang Diterima</td>
    	<td style="text-align: left; font-weight: bold;"><?php echo format_rp($data['subtotal_gaji']); ?></td>
    	</tr>
    </tbody>
</table>
</body>
</html>