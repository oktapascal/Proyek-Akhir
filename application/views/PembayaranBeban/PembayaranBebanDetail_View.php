<!DOCTYPE html>
<html>
<head>
	
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
<div class="row clearfix">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="card">
			<div class="header bg-blue">
				<h2>Tabel Detail <?php echo $navigasi; ?> <?php echo $id_transaksi; ?></h2>
				<ul class="header-dropdown m-r--5">
    		<li class="dropdown">
    		<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
    			<i class="material-icons">more_vert</i>
    		</a>
    		<ul class="dropdown-menu pull-right">
                  <li><a role="button" onclick="window.history.go(-1); return false;">Kembali</a></li>
            </ul>
    		</li>
    		</ul>
			</div>
			<div class="body">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th style="text-align: center;">Kode Beban</th>
								<th style="text-align: center;">Nama Beban</th>
								<th style="text-align: center;">Subtotal</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($data as $dt){ ?>
							<tr>
								<td style="text-align: center;"><?php echo $dt['id_beban']; ?></td>
								<td style="text-align: center;"><?php echo $dt['nama_beban']; ?></td>
								<td style="text-align: center;"><?php echo format_rp($dt['subtotal']); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td style="text-align: center; font-weight: bold;" colspan="2">Total Pembayaran Beban</td>
								<td style="text-align: center; font-weight: bold;"><?php echo format_rp($dt['total']); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>