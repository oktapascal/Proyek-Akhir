<?php
	function hari_ini($hari)
	{
		date_default_timezone_set("Asia/Jakarta");
		$seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
		$hari_ini = $seminggu[$hari];
		return $hari_ini;
	}

	function getBulan($bulan)
	{
		switch ($bulan) {
			case 1:	
				return "Januari";
				break;
			case 2:	
				return "Februari";
				break;
			case 3:	
				return "Maret";
				break;
			case 4:	
				return "April";
				break;
			case 5:	
				return "Mei";
				break;
			case 6:	
				return "Juni";
				break;
			case 7:	
				return "Juli";
				break;
			case 8:	
				return "Agustus";
				break;
			case 9:	
				return "September";
				break;
			case 10:	
				return "Oktober";
				break;
			case 11:	
				return "November";
				break;
			case 12:	
				return "Desember";
				break;										
		}
	}

	function tgl_indo($date)
	{
		//Membalik format tanggal SQL//
		$exp  = explode('-', $date);
		$date = "";

		if(count($exp) == 3)
		{
			$bulan = getBulan($exp[1]);
			$date  = $exp[2] . ' ' . $bulan . ' ' . $exp[0];
		}
		return $date; 	
	}
?>